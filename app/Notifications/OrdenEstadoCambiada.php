<?php

namespace App\Notifications;

use App\Models\OrdenTrabajo;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrdenEstadoCambiada extends Notification
{
    public function __construct(
        public OrdenTrabajo $orden,
        public string $nuevoEstado
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        $mensajes = [
            'en_camino'            => 'El técnico está en camino para tu servicio: ' . $this->orden->titulo,
            'pendiente_valoracion' => 'Tu servicio ha sido completado: ' . $this->orden->titulo . '. Por favor, valóralo.',
            'cancelada'            => 'Tu solicitud de servicio ha sido cancelada: ' . $this->orden->titulo,
        ];

        return [
            'orden_id'    => $this->orden->id,
            'orden_titulo'=> $this->orden->titulo,
            'estado'      => $this->nuevoEstado,
            'mensaje'     => $mensajes[$this->nuevoEstado] ?? 'Actualización en tu servicio: ' . $this->orden->titulo,
            'tipo'        => 'orden_estado',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        if ($this->nuevoEstado === 'en_camino') {
            return (new MailMessage)
                ->subject('🚗 El técnico está en camino — ' . $this->orden->titulo)
                ->greeting('¡Hola, ' . $notifiable->name . '!')
                ->line('Tu técnico está en camino para realizar el servicio.')
                ->line('**Servicio:** ' . $this->orden->titulo)
                ->action('Ver detalle', route('cliente.orden.show', $this->orden))
                ->salutation('Sistema WorkFlow');
        }

        if ($this->nuevoEstado === 'pendiente_valoracion') {
            return (new MailMessage)
                ->subject('⭐ Servicio completado — ' . $this->orden->titulo)
                ->greeting('¡Hola, ' . $notifiable->name . '!')
                ->line('El técnico ha finalizado el servicio. Por favor, valora la atención recibida.')
                ->line('**Servicio:** ' . $this->orden->titulo)
                ->action('Valorar servicio', route('cliente.orden.valorar', $this->orden))
                ->salutation('Sistema WorkFlow');
        }

        return (new MailMessage)
            ->subject('Actualización en tu servicio — ' . $this->orden->titulo)
            ->greeting('Hola, ' . $notifiable->name)
            ->line('Ha habido una actualización en tu servicio: ' . $this->orden->titulo)
            ->action('Ver detalle', route('cliente.orden.show', $this->orden))
            ->salutation('Sistema WorkFlow');
    }
}
