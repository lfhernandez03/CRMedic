<?php

namespace App\Console\Commands;

use App\Models\Appointments;
use Illuminate\Console\Command;
use App\Notifications\AppointmentReminderWhatsapp;

class SendAppointmentRemindersWhatssApp extends Command
{

    protected $signature = 'appointments:remind:whatsapp';
    protected $description = 'Envía un recordatorio de cita a los usuarios por WhatsApp';

    public function handle()
    {
        // Obtener todas las citas programadas para hoy
        $appointments = Appointments::where('date', '=', today())->get();

        foreach ($appointments as $appointment) {
            // Verificar si la cita tiene un paciente asociado
            if ($appointment->patient) {
                // Enviar la notificación solo si la cita tiene un paciente asociado
                $appointment->patient->notify(new AppointmentReminderWhatsapp($appointment));
            } else {
                // Si no tiene paciente asociado, puedes registrar un mensaje o manejar el caso como prefieras
                $this->info('La cita con ID ' . $appointment->id . ' no tiene paciente asociado.');
            }
        }

        $this->info('Recordatorios de citas enviados correctamente.');
    }
}
