<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TecnicoDadoDeBaja extends Notification
{
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'mensaje' => 'Tu cuenta de técnico ha sido desactivada por el administrador. Contacta con soporte si crees que es un error.',
            'tipo'    => 'baja',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⚠️ Tu cuenta ha sido desactivada — Workflow')
            ->greeting('Hola, ' . $notifiable->name)
            ->line('Tu cuenta de técnico ha sido **desactivada** por el administrador.')
            ->line('No podrás acceder a la plataforma hasta que tu cuenta sea reactivada.')
            ->line('Si crees que esto es un error, contacta con el administrador de la plataforma.')
            ->salutation('Sistema WorkFlow');
    }
}
