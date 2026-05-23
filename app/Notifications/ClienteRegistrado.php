<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClienteRegistrado extends Notification
{
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('📋 Bienvenido a Workflow — Tu cuenta está pendiente de validación')
            ->greeting('¡Hola, ' . $notifiable->name . '!')
            ->line('Te has registrado correctamente en **Workflow**.')
            ->line('Tu cuenta está **pendiente de validación** por parte de un administrador. Mientras tanto, puedes acceder a la plataforma pero no podrás realizar solicitudes de servicio hasta que tu cuenta sea activada.')
            ->line('Te avisaremos por este correo en cuanto esté lista.')
            ->action('Acceder a mi cuenta', route('dashboard'))
            ->line('Si no has sido tú quien ha realizado este registro, ignora este mensaje.')
            ->salutation('El equipo de Workflow');
    }
}
