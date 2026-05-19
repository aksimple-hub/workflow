<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TecnicoAprobado extends Notification
{
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'mensaje' => 'Tu cuenta de técnico ha sido aprobada. Ya puedes iniciar sesión y empezar a trabajar.',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('✅ Tu cuenta de técnico ha sido activada — Workflow')
            ->greeting('¡Hola, ' . $notifiable->name . '!')
            ->line('Buenas noticias: un administrador ha revisado tus datos y ha **activado tu cuenta** en la plataforma.')
            ->line('Ya puedes iniciar sesión y empezar a recibir órdenes de trabajo.')
            ->action('Iniciar sesión ahora', route('login'))
            ->line('Si tienes cualquier duda, contacta con tu administrador.')
            ->salutation('El equipo de Workflow');
    }
}
