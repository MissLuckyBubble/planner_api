<?php

namespace App\Console\Commands;

use App\Mail\RateAppointmentMail;
use App\Mail\RemindAppointmentMail;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AppointmentRemind extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointment:make-reminders';

    protected $description = 'Remind Customers for their appointments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $appointments = Appointment::where('status', '=', 'Запазен')
            ->where('date', '=', Carbon::tomorrow()->format('Y-m-d'))
            ->where('start_time', '<=', Carbon::now()->format('H:i'))
            ->get();

        foreach ($appointments as $appointment) {
            if ($appointment->reminders < 1) {
                $appointment->reminders = 1;
                $appointment->save();
                \Mail::to($appointment->customer->user->email)
                    ->send(new RemindAppointmentMail($appointment->customer, $appointment->business, $appointment));
            }
        }
        $appointments = Appointment::where('status', '=', 'Запазен')
            ->where('date', '=', Carbon::now()->format('Y-m-d'))
            ->get();

        $current_time = Carbon::now();
        foreach ($appointments as $appointment) {
            if (Carbon::parse($appointment->start_time)
                    ->between($current_time->copy()->addHour(),
                        $current_time->copy()->addHours(2)) && $appointment->reminders < 2) {
                $appointment->reminders = 2;
                $appointment->save();
                \Mail::to($appointment->customer->user->email)
                    ->send(new RemindAppointmentMail($appointment->customer, $appointment->business, $appointment));
            }
        }
    }
}
