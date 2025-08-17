<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pago;
use Carbon\Carbon;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';

    protected $fillable = [
        'usuario_id',
        'vehiculo_id',
        'fecha_hora',
        'estado',
        'observaciones',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Estados de las citas
    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_CONFIRMADA = 'confirmada';
    const ESTADO_EN_PROCESO = 'en_proceso';
    const ESTADO_FINALIZADA = 'finalizada';
    const ESTADO_CANCELADA = 'cancelada';

    public static function getEstados(): array
    {
        return [
            self::ESTADO_PENDIENTE => 'Pendiente',
            self::ESTADO_CONFIRMADA => 'Confirmada',
            self::ESTADO_EN_PROCESO => 'En Proceso',
            self::ESTADO_FINALIZADA => 'Finalizada',
            self::ESTADO_CANCELADA => 'Cancelada',
        ];
    }
    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'cita_servicio', 'cita_id', 'servicio_id')
            ->withPivot(['precio', 'descuento', 'observacion']);
    }

    public function pago()
    {
        return $this->hasOne(Pago::class, 'cita_id');
    }

    // Scopes
    public function scopeByEstado($query, string $estado)
    {
        return $query->where('estado', $estado);
    }

    public function scopeByUsuario($query, int $usuarioId)
    {
        return $query->where('usuario_id', $usuarioId);
    }

    public function scopeByFecha($query, $fecha)
    {
        return $query->whereDate('fecha_hora', $fecha);
    }

    public function scopeHoy($query)
    {
        return $query->whereDate('fecha_hora', today());
    }

    // Accessors
    public function getEstadoFormattedAttribute(): string
    {
        return self::getEstados()[$this->estado] ?? $this->estado;
    }

    public function getTotalAttribute(): float
    {
        return $this->servicios->sum(function ($servicio) {
            return $servicio->pivot->precio - $servicio->pivot->descuento;
        });
    }

    public function marcarComoExpirada()
    {
        $motivo = ($this->estado == self::ESTADO_PENDIENTE)
            ? '. Cita expirada por inacción'
            : '. Cita expirada - No atendida';

        $observaciones = $this->observaciones
            ? $this->observaciones . "\n" . $motivo
            : $motivo;

        return $this->update([
            'estado' => self::ESTADO_CANCELADA,
            'observaciones' => $observaciones
        ]);
    }

    public function scopeExpiradas($query)
    {
        return $query->whereIn('estado', [self::ESTADO_PENDIENTE, self::ESTADO_CONFIRMADA])
            ->where('fecha_hora', '<', now());
    }

    public function scopeActivas($query)
    {
        return $query->whereIn('estado', [self::ESTADO_PENDIENTE, self::ESTADO_CONFIRMADA, self::ESTADO_EN_PROCESO]);
    }
}
