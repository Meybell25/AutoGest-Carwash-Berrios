<?php
// app/Models/Notificacion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';

    protected $fillable = [
        'usuario_id',
        'mensaje',
        'canal',
        'leido', 
        'fecha_envio',
    ];

    protected $casts = [
        'leido' => 'boolean',
        'fecha_envio' => 'datetime',
    ];

    // Desactivar timestamps automáticos ya que usamos fecha_envio
    public $timestamps = false;

    // Canales de notificación
    const CANAL_SISTEMA = 'sistema';
    const CANAL_EMAIL = 'email';
    const CANAL_SMS = 'sms';
    const CANAL_PUSH = 'push';

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Scopes
    public function scopeNoLeidas($query)
    {
        return $query->where('leido', false);
    }

    public function scopeLeidas($query)
    {
        return $query->where('leido', true);
    }

    public function scopeByUsuario($query, $usuarioId)
    {
        return $query->where('usuario_id', $usuarioId);
    }

    public function scopeByCanal($query, $canal)
    {
        return $query->where('canal', $canal);
    }

    public function scopeRecientes($query, $dias = 30)
    {
        return $query->where('fecha_envio', '>=', now()->subDays($dias));
    }

    public function scopeHoy($query)
    {
        return $query->whereDate('fecha_envio', today());
    }

    // Métodos
    public function marcarComoLeida(): bool
    {
        return $this->update(['leido' => true]);
    }

    public function marcarComoNoLeida(): bool
    {
        return $this->update(['leido' => false]);
    }

    // Métodos estáticos para crear notificaciones
    public static function crear(int $usuarioId, string $mensaje, string $canal = self::CANAL_SISTEMA): self
    {
        return self::create([
            'usuario_id' => $usuarioId,
            'mensaje' => $mensaje,
            'canal' => $canal,
            'leido' => false,
            'fecha_envio' => now(),
        ]);
    }

    public static function citaCreada(int $usuarioId, $fechaCita): self
    {
        return self::crear(
            $usuarioId,
            "Tu cita ha sido programada para el " . $fechaCita->format('d/m/Y H:i')
        );
    }

    public static function citaConfirmada(int $usuarioId, $fechaCita): self
    {
        return self::crear(
            $usuarioId,
            "Tu cita del " . $fechaCita->format('d/m/Y H:i') . " ha sido confirmada"
        );
    }

    public static function citaCancelada(int $usuarioId, $fechaCita): self
    {
        return self::crear(
            $usuarioId,
            "Tu cita del " . $fechaCita->format('d/m/Y H:i') . " ha sido cancelada"
        );
    }

    public static function recordatorioCita(int $usuarioId, $fechaCita): self
    {
        return self::crear(
            $usuarioId,
            "Recordatorio: Tienes una cita mañana a las " . $fechaCita->format('H:i')
        );
    }
}