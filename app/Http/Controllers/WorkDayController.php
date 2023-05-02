<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomDayOffRequest;
use App\Http\Requests\StoreWorkDayRequest;
use App\Http\Resources\CustomDaysOffResource;
use App\Http\Resources\WorkDayResource;
use App\Models\Business;
use App\Models\CustomDayOff;
use App\Models\WeekDay;
use App\Models\WorkDay;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WorkDayController extends Controller
{
    use HttpResponses;

    public function update(StoreWorkDayRequest $request, WorkDay $workday)
    {
        if ($this->isNotAuthorized($workday)) return $this->isNotAuthorized($workday);

        $request->validated($request->all());

        $workday->update([
            'is_off' => false,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'pause_start' => $request->pause_start,
            'pause_end' => $request->pause_end,
        ]);

        return $this->success([
            'work day' => $workday,
        ]);
    }

    public function setDayOff(WorkDay $workday)
    {

        if ($this->isNotAuthorized($workday)) return $this->isNotAuthorized($workday);

        $workday->update([
            'is_off' => true,
            'start_time' => '00:00',
            'end_time' => '00:00',
            'pause_start' => null,
            'pause_end' => null,
        ]);

        return $this->success([
            'work day' => $workday,
        ]);
    }

    private function isNotAuthorized($workday)
    {
        if (Auth::user()->business->id !== $workday->business->id) {
            return $this->error('', 'You are not authorized to make this request', 403);
        }
    }

    public function customDayOff(StoreCustomDayOffRequest $request)
    {
        $business = Auth::user()->business;

        $request->validated($request->all());

        $existing_record = $business->custom_days_off->where('date', $request->date)->isNotEmpty();

        if ($existing_record) {
            return $this->error('', 'Вече съществува персонализиран почивен ден за този бизнес на този ден', 400);
        }

        $dayOff = CustomDayOff::create([
            'date' => $request->date,
            'business_id' => $business->id
        ]);
        return $this->success([
            'Custom Day Of' => $dayOff
        ]);
    }

    public function deleteCustomDayOff(CustomDayOff $customDayOff)
    {
        if ($this->isNotAuthorized($customDayOff)) return $this->isNotAuthorized($customDayOff);
        return $customDayOff->delete();
    }

    public function getCustomDaysOff()
    {
        $now = Carbon::now();
        $business = Auth::user()->business;

        $oldCustomDaysOff = $business->custom_days_off->where('date', '<=', $now->format('Y-m-d'));
        $newCustomDaysOff = $business->custom_days_off->where('date', '>=', $now->format('Y-m-d'));

        return [
            'old' => $oldCustomDaysOff,
            'new' => $newCustomDaysOff,
        ];
    }

    public function getSchedule()
    {
        return WorkDayResource::collection(WorkDay::where('business_id', Auth::user()->business->id)->get());
    }

    public function getScheduleByBusiness(Business $business)
    {
        return [
            'business_id' => $business->id,
            'schedule' => WorkDayResource::collection(WorkDay::where('business_id', $business->id)->get())
        ];
    }

    public function getTwoWeekSchedule()
    {
        $now = Carbon::now();
        $tomorrow = $now->addDay();

        $schedule = [];

        for ($i = 0; $i < 14; $i++) {
            $date = $tomorrow->format('Y-m-d');
            $weekday = WeekDay::where('name_eng', $tomorrow->format('l'))->first();

            $workDay = WorkDayResource::collection(WorkDay::where('business_id', Auth::user()->business->id)
                ->where('weekday_id', $weekday->id)->get())->first();
            $customDayOff = CustomDayOff::where('business_id', Auth::user()->business->id)
                ->where('date', $date)
                ->first();

            if ($customDayOff || (bool)$workDay->is_off) {
                $schedule[] = [
                    'date' => $date,
                    'workday' => false,
                    'is_off' => true,
                    'data' => null
                ];
            } else {
                $schedule[] = [
                    'date' => $date,
                    'workday' => true,
                    'is_off' => false,
                    'data' => $workDay
                ];
            }


            $tomorrow->addDay();
        }
        return $schedule;
    }
}
