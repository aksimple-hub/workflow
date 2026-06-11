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
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'tecnico_id'     => $this->tecnico->id,
            'tecnico_nombre' => $this->tecnico->name,
            'tecnico_email'  => $this->tecnico->email,
            'mensaje'        => 'Nuevo técnico pendiente de validación: ' . $this->tecnico->name,
            'tipo'           => 'nuevo_usuario',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🔔 Nuevo técnico pendiente de validación — ' . $this->tecnico->name)
            ->greeting('Hola, ' . $notifiable->name)
            ->line('Un nuevo técnico se ha registrado en la plataforma y está esperando que lo actives.')
            ->line('**Nombre:** ' . $this->tecnico->name)
            ->line('**Email:** ' . $this->tecnico->email)
            ->action('Revisar y activar técnico', route('admin.tecnico.show', $this->tecnico->id))
            ->line('Accede a su perfil para revisar sus datos y currículum antes de activar la cuenta.')
            ->salutation('Sistema Workflow');
    }
}
