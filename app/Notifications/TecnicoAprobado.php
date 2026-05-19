<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class TecnicoAprobado extends Notification
{
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'mensaje' => 'Tu cuenta de técnico ha sido aprobada. Ya puedes iniciar sesión y empezar a trabajar.',
        ];
    }
}
