<?php

namespace App\Notifications;

use App\Models\OrdenTrabajo;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrdenAplazadaAdmin extends Notification
{
    public function __construct(
        public OrdenTrabajo $orden,
        public string $motivo,
        public ?string $nota = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'tipo'          => 'orden_aplazada',
            'orden_id'      => $this->orden->id,
            'orden_titulo'  => $this->orden->titulo,
            'motivo'        => $this->motivo,
            'nota'          => $this->nota,
            'tecnico_nombre'=> optional($this->orden->tecnico)->name,
            'cliente_nombre'=> optional($this->orden->cliente)->nombre,
            'mensaje'       => 'El técnico ' . optional($this->orden->tecnico)->name . ' ha aplazado: ' . $this->orden->titulo,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('📅 Servicio aplazado - ' . $this->orden->titulo)
            ->greeting('Hola, ' . $notifiable->name)
            ->line('El técnico **' . optional($this->orden->tecnico)->name . '** no ha podido realizar el servicio **' . $this->orden->titulo . '**.')
            ->line('**Motivo:** ' . $this->motivo);

        if ($this->nota) {
            $mail->line('**Nota adicional:** ' . $this->nota);
        }

        $mail->line('**Cliente:** ' . optional($this->orden->cliente)->nombre)
             ->action('Reagendar orden', route('admin.orden.show', $this->orden->id))
             ->line('La orden queda pendiente de reagendación. Puedes reasignar técnico y fecha desde el panel.')
             ->salutation('Sistema WorkFlow');

        return $mail;
    }
}
