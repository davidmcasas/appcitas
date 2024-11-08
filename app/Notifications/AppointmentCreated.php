<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentCreated extends Notification implements ShouldQueue
{
    use Queueable;


    private Appointment $appointment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->onQueue('notifications');
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Confirmación de cita')
                    ->line('Se ha registrado tu cita el día ' . $this->appointment->date->format('d/m/Y') . ' a las ' . $this->appointment->date->format('H:i') . '.')
                    ->line('Tipo de cita: ' . ($this->appointment->type === 'first' ? 'Primera Cita' : 'Revisión') . '.')
                    ->salutation('Gracias');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
