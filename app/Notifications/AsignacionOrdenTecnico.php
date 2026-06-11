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
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'orden_id'      => $this->orden->id,
            'orden_titulo'  => $this->orden->titulo,
            'cliente_nombre'=> optional($this->orden->cliente)->nombre,
            'mensaje'       => 'Nueva orden asignada: ' . $this->orden->titulo,
            'tipo'          => 'nueva_orden',
        ];
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
