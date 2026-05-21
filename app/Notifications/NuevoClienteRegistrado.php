<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NuevoClienteRegistrado extends Notification
{
    public function __construct(public User $cliente) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'cliente_id'     => $this->cliente->id,
            'cliente_nombre' => $this->cliente->name,
            'cliente_email'  => $this->cliente->email,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('👤 Nuevo cliente registrado — ' . $this->cliente->name)
            ->greeting('Hola, ' . $notifiable->name)
            ->line('Un nuevo cliente se ha registrado en la plataforma y está esperando validación.')
            ->line('**Nombre:** ' . $this->cliente->name)
            ->line('**Email:** ' . $this->cliente->email)
            ->action('Revisar y activar cliente', route('admin.cliente.show', $this->cliente->id))
            ->line('Accede a su perfil para verificar sus datos antes de activar la cuenta.')
            ->salutation('Sistema Workflow');
    }
}
