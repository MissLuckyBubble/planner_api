<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentNoCustomerRequest;
use App\Http\Requests\GetAppointmentsRequest;

use App\Http\Resources\AppointmentResource;
use App\Mail\CancelAppointmentMail;
use App\Models\Appointment;
use App\Models\AppointmentNoCustomer;
use App\Models\CustomDayOff;
use App\Models\Service;
use App\Models\WorkDay;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class AppointmentController extends Controller
{
    use HttpResponses;

    private function overlappingAppointmentsQuery($businessId, $date, $requestedTime, $requestedEndTime)
    {
        return function ($query) use ($businessId, $date, $requestedTime, $requestedEndTime) {
            $query->where('business_id', $businessId)
                ->where('date', $date)
                ->where('status', '<>', 'Отказан')
                ->where(function ($query) use ($requestedTime, $requestedEndTime) {
                    $query->where(function ($query) use ($requestedTime, $requestedEndTime) {
                        $query->where('start_time', '>=', $requestedTime)
                            ->where('start_time', '<', $requestedEndTime);
                    })
                        ->orWhere(function ($query) use ($requestedTime, $requestedEndTime) {
                            $query->where('end_time', '>', $requestedTime)
                                ->where('end_time', '<=', $requestedEndTime);
                        })
                        ->orWhere(function ($query) use ($requestedTime, $requestedEndTime) {
                            $query->where('start_time', '<', $requestedTime)
                                ->where('end_time', '>', $requestedEndTime);
                        })
                        ->orWhere(function ($query) use ($requestedEndTime) {
                            $query->where('end_time', '=', $requestedEndTime);
                        });
                });
        };
    }

    public function checkBusinessAvailability($businessId, $requestedDate, $requestedStartTime, $totalDuration)
    {
        $requestedTime = Carbon::createFromFormat('H:i', $requestedStartTime);
        $requestedEndTime = Carbon::parse($requestedTime)->addMinutes($totalDuration);

        $requestedDayOfWeek = Carbon::parse($requestedDate)->dayOfWeekIso;

        $businessWorkday = WorkDay::where('business_id', $businessId)
            ->where('weekday_id', $requestedDayOfWeek)
            ->where('is_off', false)
            ->first();

        $customDayOff = CustomDayOff::where('business_id', $businessId)
            ->where('date', $requestedDate)
            ->first();

        if (!$businessWorkday || $customDayOff) {
            return $this->error('', 'Бизнесът не работи на този ден.', 400);
        }

        $workdayStartTime = Carbon::parse($businessWorkday->start_time);
        $workdayEndTime = Carbon::parse($businessWorkday->end_time);

        if ($businessWorkday->pause_start && $businessWorkday->pause_end) {
            $workdayPauseStart = Carbon::parse($businessWorkday->pause_start);
            $workdayPauseEnd = Carbon::parse($businessWorkday->pause_end);
        }

        if ($requestedTime < $workdayStartTime || $requestedTime >= $workdayEndTime || $requestedEndTime > $workdayEndTime) {
            return $this->error('', 'Избраният час е извънработното време на бизнесът за този ден.', 400);
        }

        if (isset($workdayPauseStart) && isset($workdayPauseEnd)) {
            if (($requestedTime > $workdayPauseStart && $requestedTime < $workdayPauseEnd) ||
                ($requestedEndTime > $workdayPauseStart && $requestedEndTime < $workdayPauseEnd)) {
                return $this->error('', 'Бизнесът е почивка за избраният часови период.', 403);
            }
        }

        $overlappingAppointments = Appointment::where(
            $this->overlappingAppointmentsQuery($businessId, $requestedDate, $requestedTime, $requestedEndTime))->get();
        $overlappingAppointmentsNoCustomer = AppointmentNoCustomer::where(
            $this->overlappingAppointmentsQuery($businessId, $requestedDate, $requestedTime, $requestedEndTime))->get();

        if ($overlappingAppointments->count() > 0 || $overlappingAppointmentsNoCustomer->count() > 0) {
            return $this->error('', 'Бизнесът е зает за този период.', 400);
        }
    }


    public function createAppointment(AppointmentNoCustomerRequest $request)
    {
        if (Auth::user()->role_id != 2) {
            return $this->error('', 'Грешка. Не може да добавяте часове, ако не сте бизнес.', 400);
        }

        $business = Auth::user()->business;

        $request->validated($request->all());


        if ($this->checkServices($request->services, $business)) {
            $services = $this->checkServices($request->services, $business);
        } else return $this->error('', 'Някой от избраните услуги не съществуват.', 400);

        $totalPrice = 0;
        $totalDuration = 0;
        foreach ($services as $service) {
            $totalPrice += $service->price;
            $totalDuration += $service->duration_minutes;
        }

        $requestedEndTime = Carbon::parse($request->start_time)->addMinutes($totalDuration);
        $overlappingAppointments = Appointment::where(
            $this->overlappingAppointmentsQuery($business->id, $request->date, $request->start_time, $requestedEndTime))->get();
        $overlappingAppointmentsNoCustomer = AppointmentNoCustomer::where(
            $this->overlappingAppointmentsQuery($business->id, $request->date, $request->start_time, $requestedEndTime))->get();

        if ($overlappingAppointments->count() > 0 || $overlappingAppointmentsNoCustomer->count() > 0) {
            return $this->error('', 'Бизнесът е зает за този период.', 400);
        }

        $appointment = AppointmentNoCustomer::create([
            'business_id' => $business->id,
            'date' => Carbon::createFromFormat('Y/m/d', $request->date),
            'start_time' => Carbon::createFromFormat('H:i', $request->start_time),
            'end_time' => Carbon::parse($request->start_time)->addMinutes($totalDuration),
            'total_price' => $totalPrice,
            'duration' => $totalDuration,
            'name' => $request->name,
            'phoneNumber' => $request->phoneNumber,
            'status' => 'Запазен',
        ]);

        $appointment->services()->attach($services);

        return $this->success([
            new AppointmentResource($appointment)
        ]);
    }

    public function getAppointment(Appointment $appointment)
    {
        if (Auth::user()->role_id == 1) {
            if ($appointment->customer->id != Auth::user()->customer->id) {
                return $this->error('', 'You are not authorized to make this request', 400);
            }
        } else {
            if ($this->BusinessisNotAuthorized($appointment)) {
                return $this->BusinessisNotAuthorized($appointment);
            }
        }
        return $this->success([
            new AppointmentResource($appointment)
        ]);
    }

    public function getAllAppointments(GetAppointmentsRequest $request)
    {
        $request->validated($request->all());
        $user = Auth::user();

        if($user->role_id != 2){
            return $this->error('','Грешка. Трябва да сте бизнес за да направите тази заявка.', 403);
        }

        $appointment_query = Appointment::where('business_id', $user->business->id);
        $appointmentNoCustomer_query = AppointmentNoCustomer::where('business_id', $user->business->id);
        if ($request->date) {
            $appointment_query->where('date', '=', $request->date);
            $appointmentNoCustomer_query->where('date', '=', $request->date);
        } else {
            if ($request->date_after) {
                $appointment_query->where('date', '>', $request->date_after);
                $appointmentNoCustomer_query->where('date', '>', $request->date_after);
            }
            if ($request->date_before) {
                $appointment_query->where('date', '<', $request->date_before);
                $appointmentNoCustomer_query->where('date', '<', $request->date_before);
            }
        }
        if ($request->status) {
            $appointment_query->where('status', '=', $request->status);
            $appointmentNoCustomer_query->where('status', '=', $request->status);
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
        $appointments = $appointment_query->get();
        $appointmentsNoCustomer = $appointmentNoCustomer_query->get();
        $all = AppointmentResource::collection($appointments)->merge(AppointmentResource::collection($appointmentsNoCustomer));
        $all = $all->sortBy('start_time')->sortBy($sortBy);
        if ($sortOrder === 'desc') {
            $all = $all->reverse();
        }
        return $this->success($all, 'Success', 200);
    }

    public function deleteAppointmentNoCustomer(AppointmentNoCustomer $appointment){
        if (!$appointment->id) {
            return $this->error('Invalid appointment.', '', 400);
        }
        return $this->BusinessisNotAuthorized($appointment) ? $this->BusinessisNotAuthorized($appointment) : $appointment->delete();
    }

    public function cancelAppointment(Appointment $appointment){
        if($this->BusinessisNotAuthorized($appointment)){
            return $this->BusinessisNotAuthorized($appointment);
        }else if($appointment->status != 'Запазен'){
            return $this->error('', 'Час със Статус: "'. $appointment->status .'" не може да бъде променян', 400);
        }
        $appointment->update([
            'status' => 'Отказан от Фирмата'
        ]);

        \Mail::to($appointment->customer->user->email)->send(
            new CancelAppointmentMail($appointment->customer,$appointment->business,$appointment));

        return new AppointmentResource($appointment);
    }
    private  function BusinessisNotAuthorized($appointment){
        if(Auth::user()->business->id !== $appointment->business->id){
            return $this->error('', 'You are not authorized to make this request', 403);
        }
    }

    public function editAppointmentNoCustomer(AppointmentNoCustomer $appointment, AppointmentNoCustomerRequest $request){
        $request->validated($request->all());

        if($this->BusinessisNotAuthorized($appointment)){
            return $this->BusinessisNotAuthorized($appointment);
        }

        if (Auth::user()->role_id != 2) {
            return $this->error('', 'Грешка. Не може да редактирате часове, ако не сте бизнес.', 400);
        }

        $business = Auth::user()->business;

        $request->validated($request->all());

        if ($this->checkServices($request->services, $business)) {
            $services = $this->checkServices($request->services, $business);
        } else return $this->error('', 'Някой от избраните услуги не съществуват.', 400);

        $totalPrice = 0;
        $totalDuration = 0;
        foreach ($services as $service) {
            $totalPrice += $service->price;
            $totalDuration += $service->duration_minutes;
        }

        $requestedEndTime = Carbon::parse($request->start_time)->addMinutes($totalDuration);
        $overlappingAppointments = Appointment::where(
            $this->overlappingAppointmentsQuery($business->id, $request->date, $request->start_time, $requestedEndTime))->where('id', '!=', $appointment->id)->get();
        $overlappingAppointmentsNoCustomer = AppointmentNoCustomer::where(
            $this->overlappingAppointmentsQuery($business->id, $request->date, $request->start_time, $requestedEndTime))->where('id', '!=', $appointment->id)->get();

        if ($overlappingAppointments->count() > 0 || $overlappingAppointmentsNoCustomer->count() > 0) {
            return $this->error('', 'Бизнесът е зает за този период.', 400);
        }

        $appointment->services()->detach();
        $appointment->services()->attach($services);

        $appointment->update([
            'business_id' => $business->id,
            'date' => Carbon::createFromFormat('Y/m/d', $request->date),
            'start_time' => Carbon::createFromFormat('H:i', $request->start_time),
            'end_time' => Carbon::parse($request->start_time)->addMinutes($totalDuration),
            'total_price' => $totalPrice,
            'duration' => $totalDuration,
            'name' => $request->name,
            'phoneNumber' => $request->phoneNumber,
            'status' => 'Запазен',
        ]);

        return $this->success([
            new AppointmentResource($appointment)
        ]);
    }

    public function checkServices($request_services, $business)
    {
        $request_services = explode(",", $request_services);
        $services = Service::whereIn('id', $request_services)
            ->whereHas('service_category', function ($query) use ($business) {
                $query->where('business_id', $business->id);
            })->get();
        if ($services->count() != count($request_services)) {
            return false;
        } else return $services;
    }

}
