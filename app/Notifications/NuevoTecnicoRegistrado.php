<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NuevoTecnicoRegistrado extends Notification
{
    public function __construct(public User $tecnico) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'tecnico_id'     => $this->tecnico->id,
            'tecnico_nombre' => $this->tecnico->name,
            'tecnico_email'  => $this->tecnico->email,
        ];
    }
}
