<?php

// app/Notifications/AppointmentReminderWhatsapp.php

namespace App\Notifications;

use App\Models\Appointment;
use App\Models\Appointments;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Twilio\Rest\Client;

class AppointmentReminderWhatsapp extends Notification
{
    use Queueable;

    protected $appointment;

    public function __construct(Appointments $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Define los canales por los que se enviará la notificación.
     * No vamos a usar 'database' aquí, solo 'whatsapp'.
     */
    public function via($notifiable)
    {
        return ['whatsapp'];  // Solo WhatsApp, no database ni otro canal
    }

    /**
     * Enviar el mensaje por WhatsApp.
     */
    public function toWhatsapp($notifiable)
    {
        $phoneNumber = $notifiable->phone_number;

        // Creamos el cliente de Twilio usando las credenciales del .env
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $from = 'whatsapp:' . env('TWILIO_WHATSAPP_FROM');
        $to = 'whatsapp:' . $phoneNumber;

        $client = new Client($sid, $token);

        // Definir el mensaje de la cita
        $message = "Recordatorio de Cita: Tienes una cita programada para el " . $this->appointment->date . " a las " . $this->appointment->time . ". ¡Nos vemos pronto!";

        // Enviar el mensaje a través de Twilio
        $client->messages->create(
            $to,
            [
                'from' => $from,
                'body' => $message
            ]
        );
    }
}
