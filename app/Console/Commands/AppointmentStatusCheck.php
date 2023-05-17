<?php

namespace App\Console\Commands;

use App\Mail\RateAppointmentMail;
use App\Models\Appointment;
use App\Models\AppointmentNoCustomer;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AppointmentStatusCheck extends Command
{
    protected $signature = 'appointment:status-check';

    protected $description = 'Update appointment status based on end time.';

    public function handle()
    {

        $appointments = Appointment::where(
            'date','<=', Carbon::today())
            ->where('status', '=', 'Запазен')->get();
        foreach ($appointments as $appointment) {
            if($appointment->end_time <= Carbon::now()->addMinutes(30)->format('H:i')){
                $appointment->status = 'Приключен';
                $appointment->save();
                \Mail::to($appointment->customer->user->email)
                    ->send(new RateAppointmentMail($appointment->customer,$appointment->business,$appointment));
            }
        }
        $appointments = AppointmentNoCustomer::where('date', Carbon::today())->where('status', '=', 'Запазен')->get();

        foreach ($appointments as $appointment) {

            if ($appointment->end_time <= Carbon::now()->addMinutes(30)->format('H:i')) {
                $appointment->status = 'Приключен';
                $appointment->save();
            }
        }
    }
}
