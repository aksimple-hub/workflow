<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TecnicoRegistrado extends Notification
{
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('📋 Registro recibido — Estamos revisando tu cuenta')
            ->greeting('¡Hola, ' . $notifiable->name . '!')
            ->line('Hemos recibido tu solicitud de registro como técnico en **Workflow**.')
            ->line('Tu cuenta está actualmente **pendiente de validación**. Un administrador revisará tus datos y tu currículum antes de activarla.')
            ->line('Te avisaremos por este mismo correo en cuanto tu cuenta esté lista.')
            ->line('---')
            ->line('Si no has sido tú quien ha realizado este registro, ignora este mensaje.')
            ->salutation('El equipo de Workflow');
    }
}
