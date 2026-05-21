<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClienteAprobado extends Notification
{
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'mensaje' => '¡Tu cuenta ha sido validada! Ya puedes realizar solicitudes de servicio.',
            'tipo'    => 'cliente_aprobado',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('✅ Tu cuenta ha sido validada — Workflow')
            ->greeting('¡Hola, ' . $notifiable->name . '!')
            ->line('Un administrador ha revisado tus datos y ha **validado tu cuenta**.')
            ->line('Ya tienes acceso completo a la plataforma. Puedes realizar solicitudes de servicio cuando lo necesites.')
            ->action('Hacer una solicitud', route('solicitud.nueva'))
            ->line('Gracias por confiar en Workflow.')
            ->salutation('El equipo de Workflow');
    }
}
