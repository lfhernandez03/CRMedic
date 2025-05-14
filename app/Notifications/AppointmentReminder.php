<?php

namespace App\Notifications;

use App\Models\Appointments;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentReminder extends Notification
{
    use Queueable;

    public $appointment;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Appointments  $appointment
     * @return void
     */
    public function __construct(Appointments $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('⏰ Recordatorio de Cita Médica')
            ->greeting('Hola ' . $this->appointment->patient->name . ' 👋')
            ->line('Este es un recordatorio de tu cita médica.')
            ->line('📅 Fecha: ' . \Carbon\Carbon::parse($this->appointment->date)->format('d/m/Y'))
            ->line('🕐 Hora: ' . $this->appointment->time)
            ->line('👨‍⚕️ Doctor: ' . optional($this->appointment->doctor)->name)
            ->line('Por favor asegúrate de asistir o notificar si no puedes hacerlo. ¡Gracias!');
    }

    /**
     * Get the array representation of the notification (opcional).
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'date' => $this->appointment->date,
            'time' => $this->appointment->time,
        ];
    }
}
