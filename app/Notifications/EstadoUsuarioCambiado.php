<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EstadoUsuarioCambiado extends Notification
{
    use Queueable;

    protected $estado;

    public function __construct($estado)
    {
        $this->estado = $estado;
    }

    public function via($notifiable)
    {
        // Solo usamos la base de datos (tu tabla personalizada)
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'usuario_id' => $notifiable->id,
            'mensaje' => 'Tu estado de cuenta ha cambiado a: ' . ($this->estado ? 'ACTIVO' : 'INACTIVO'),
            'canal' => 'sistema',
            'leido' => false,
            'fecha_envio' => now()
        ];
    }
}