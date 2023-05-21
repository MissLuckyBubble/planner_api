<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentNoCustomerRequest;
use App\Http\Requests\AppointmentRequest;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\GetAppointmentsRequest;
use App\Http\Requests\StoreRateRequest;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\BusinessResource;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\RatingResource;
use App\Mail\AppointmentMail;
use App\Mail\CancelAppointmentMail;
use App\Models\Appointment;
use App\Models\AppointmentNoCustomer;
use App\Models\Business;
use App\Models\Customer_Has_Favorite_Business;
use App\Models\Rating;
use App\Models\Service;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
    use HttpResponses;

    public function getProfile()
    {
        $customer = Auth::user()->customer;
        return new CustomerResource($customer);
    }

    public function editProfile(CustomerRequest $request)
    {
        $customer = Auth::user()->customer;
        $request->validated($request->all());

        $customer->update([
            'name' => $request->name,
            'sex' => $request->sex,
            'birth_day' => $request->birth_day
        ]);


        $customer = new CustomerResource($customer);

        return $this->success([
            'customer' => $customer
        ]);
    }

    public function add_delete_FavoritePlace(Business $business)
    {
        $customer = Auth::user()->customer;
        $customer_has_fav_busi = Customer_Has_Favorite_Business::where([
            'customer_id' => $customer->id,
            'business_id' => $business->id
        ])->first();
        if ($customer_has_fav_busi) {
            return $customer_has_fav_busi->delete();
        }
        $customer_has_fav_busi = Customer_Has_Favorite_Business::create([
            'customer_id' => $customer->id,
            'business_id' => $business->id
        ]);
        return $this->success([
            'id' => $customer_has_fav_busi->id,
            'customer' => $customer,
            'business' => $business
        ], 'success', 200);
    }

    public function createAppointment(Business $business, AppointmentRequest $request)
    {
        if(Auth::user()->role_id != 1){
            return $this->error('','Грешка. Трябва да сте клиент за да може да си запазите час.', 403);
        }

        $customer = Auth::user()->customer;

        $request->validated($request->all());

        $request_services = explode(",", $request->services);

        $services = Service::whereIn('id', $request_services)
            ->whereHas('service_category', function ($query) use ($business) {
                $query->where('business_id', $business->id);
            })->get();
        if ($services->count() != count($request_services)) {
            return $this->error('', 'Някой от избраните услуги не съществуват.', 400);
        }

        $totalPrice = 0;
        $totalDuration = 0;
        foreach ($services as $service) {
            $totalPrice += $service->price;
            $totalDuration += $service->duration_minutes;
        }

        $appController = new AppointmentController();
        if ($appController->checkBusinessAvailability($business->id,$request->date,$request->start_time,$totalDuration)){
            return $appController->checkBusinessAvailability($business->id,$request->date,$request->start_time,$totalDuration);
        }

        $appointment = Appointment::create([
            'customer_id' => $customer->id,
            'business_id' => $business->id,
            'date' => Carbon::createFromFormat('Y/m/d', $request->date),
            'start_time' => Carbon::createFromFormat('H:i', $request->start_time),
            'end_time' => Carbon::parse($request->start_time)->addMinutes($totalDuration),
            'total_price' => $totalPrice,
            'duration' => $totalDuration,
            'status' => 'Запазен',
        ]);

        $appointment->services()->attach($request_services);

        Mail::to($customer->user->email)->send(new AppointmentMail($customer,$business,$appointment));

        return $this->success([
            new AppointmentResource($appointment)
        ]);
    }

    public function getAllAppointments(GetAppointmentsRequest $request)
    {
        $request->validated($request->all());

        $user = Auth::user();

        if($user->role_id != 1){
            return $this->error('','Грешка. Трябва да сте клиент за да направите тази заявка.', 403);
        }

        $appointment_query = Appointment::where('customer_id', $user->customer->id);

        if ($request->date) {
            $appointment_query->whereDate('date', '=', $request->date);
        } else {
            if ($request->date_after) {
                $appointment_query->whereDate('date', '>=', $request->date_after);
            }
            if ($request->date_before) {
                $appointment_query->whereDate('date', '<=', $request->date_before);
            }
        }
        if ($request->status) {
            $appointment_query->where('status', '=', $request->status);
        }
        if ($request->sortBy) {
            $sortByColumns = [
                'Цена' => 'total_price',
                'Дата' => 'date',
                'Продължителност' => 'duration',
                'Статус' => 'status'
            ];
            $sortBy = $sortByColumns[$request->sortBy];
        } else $sortBy = 'date';
        if ($request->sortOrder) {
            $sortOrderColumns = [
                'Възходящо' => 'asc',
                'Низходящо' => 'desc',
            ];
            $sortOrder = $sortOrderColumns[$request->sortOrder];
        } else $sortOrder = 'desc';
        $appointmets = $appointment_query->orderBy($sortBy, $sortOrder)->orderBy('start_time', 'desc')->get();
        return $this->success(AppointmentResource::collection($appointmets), 'Success', 200);
    }

    public function cancelAppointment(Appointment $appointment){
        $customer = Auth::user()->customer;
        if($customer->id != $appointment->customer->id){
            return $this->error('', 'You are not authorized to make this request', 403);
        }else
            if($appointment->status != 'Запазен')
                return $this->error('', 'Час със Статус: "'. $appointment->status .'" не може да бъде променян', 400);
            $appointment->update(['status' => 'Отказан от Клиента']);
        \Mail::to($appointment->business->user->email)->send(new CancelAppointmentMail($customer,$appointment->business,$appointment));
        return new AppointmentResource($appointment);
    }

    public function getFavoriteBusinesses(){
        $customer = Auth::user()->customer;
        $customer_has_favorite_businesses = $customer->customer_has_favorite_busineses;

        $businesses = [];
        if($customer_has_favorite_businesses)
        foreach ($customer_has_favorite_businesses as $favorite) {
            $business = Business::find($favorite->business_id);

            if ($business) {
                $businesses[] = $business;
            }
        }else {
            return $this->error('','Няма записи',404);
        }

        return BusinessResource::collection($businesses);
    }

}
