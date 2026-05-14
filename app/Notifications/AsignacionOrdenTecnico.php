<?php

namespace App\Notifications;

use App\Models\OrdenTrabajo;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AsignacionOrdenTecnico extends Notification
{

    public function __construct(public OrdenTrabajo $orden) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nueva orden asignada: ' . $this->orden->titulo)
            ->view('emails.asignacion-orden', [
                'tecnico' => $notifiable,
                'orden'   => $this->orden,
            ]);
    }
}
