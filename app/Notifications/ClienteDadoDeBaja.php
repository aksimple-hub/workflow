<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClienteDadoDeBaja extends Notification
{
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'mensaje' => 'Tu cuenta ha sido desactivada por el administrador. Contacta con soporte si crees que es un error.',
            'tipo'    => 'baja',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⚠️ Tu cuenta ha sido desactivada — Workflow')
            ->greeting('Hola, ' . $notifiable->name)
            ->line('Tu cuenta ha sido **desactivada** por el administrador.')
            ->line('Si necesitas más información o crees que es un error, contacta con el administrador de la plataforma.')
            ->salutation('Sistema WorkFlow');
    }
}
