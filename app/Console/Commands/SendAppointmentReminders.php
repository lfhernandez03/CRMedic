<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointments;
use App\Notifications\AppointmentReminder;
use Carbon\Carbon;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:remind';
    protected $description = 'Send appointment reminders to patients scheduled for tomorrow';

    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $appointments = Appointments::with('patient')
            ->whereDate('date', $tomorrow)
            ->get();

        foreach ($appointments as $appointment) {
            if ($appointment->patient && $appointment->patient->email) {
                $appointment->patient->notify(new AppointmentReminder($appointment));
            }
        }

        $this->info('Reminders sent successfully.');
    }
}
