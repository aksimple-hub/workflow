<?php

namespace App\Notifications;

use App\Models\OrdenTrabajo;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrdenCanceladaAdmin extends Notification
{
    public function __construct(
        public OrdenTrabajo $orden,
        public string $canceladoPor, // 'tecnico' | 'cliente'
        public ?string $motivo = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'orden_id'      => $this->orden->id,
            'orden_titulo'  => $this->orden->titulo,
            'cancelado_por' => $this->canceladoPor,
            'motivo'        => $this->motivo,
            'cliente_nombre'=> optional($this->orden->cliente)->nombre,
            'tecnico_nombre'=> optional($this->orden->tecnico)->name,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $quien = $this->canceladoPor === 'tecnico'
            ? 'El técnico ' . optional($this->orden->tecnico)->name
            : 'El cliente ' . optional($this->orden->cliente)->nombre;

        $mail = (new MailMessage)
            ->subject('⚠️ Cancelación de orden: ' . $this->orden->titulo)
            ->greeting('Hola, ' . $notifiable->name)
            ->line($quien . ' ha cancelado la orden **' . $this->orden->titulo . '**.');

        if ($this->motivo) {
            $mail->line('**Motivo:** ' . $this->motivo);
        }

        $mail->line('**Estado anterior:** ' . $this->orden->getOriginal('estado', 'cancelada'))
             ->action('Ver orden', route('admin.orden.show', $this->orden->id))
             ->line('Accede al panel para reasignar o gestionar la incidencia.')
             ->salutation('Sistema WorkFlow');

        return $mail;
    }
}
