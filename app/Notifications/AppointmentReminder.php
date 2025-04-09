<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public $appointment;

    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Appointment Reminder')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('This is a reminder for your appointment scheduled on:')
            ->line('🗓️ Date: ' . $this->appointment->date)
            ->line('⏰ Time: ' . $this->appointment->time)
            ->line('📍 Location: ' . ($this->appointment->location ?? 'Our Clinic'))
            ->line('If you need to cancel or reschedule, please contact us.')
            ->salutation('See you soon! 👨‍⚕️');
    }
}
