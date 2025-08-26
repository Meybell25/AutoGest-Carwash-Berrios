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
    /**
     * Marcar cita como expirada - 
     */
    public function marcarComoExpirada()
    {
        // Verificar si ya tiene mensaje de expiración para evitar duplicados
        $tieneExpiracionPrevia = str_contains($this->observaciones ?? '', 'Cita expirada') ||
            str_contains($this->observaciones ?? '', 'Cita no atendida');

        if ($tieneExpiracionPrevia) {
            // Si ya tiene mensaje de expiración, solo actualizar estado sin duplicar mensaje
            return $this->update(['estado' => self::ESTADO_CANCELADA]);
        }

        $motivo = ($this->estado == self::ESTADO_PENDIENTE)
            ? 'Cita expirada por inacción'
            : 'Cita no atendida - Cancelada automáticamente';

        $observaciones = $this->observaciones
            ? $this->observaciones . "\n" . $motivo
            : $motivo;

        return $this->update([
            'estado' => self::ESTADO_CANCELADA,
            'observaciones' => $observaciones
        ]);
    }

    /*
     * Scope para citas expiradas que necesitan ser procesadas
     */
    public function scopeExpiradas($query)
    {
        return $query->whereIn('estado', [self::ESTADO_PENDIENTE, self::ESTADO_CONFIRMADA])
            ->where('fecha_hora', '<', now())
            ->where(function ($q) {
                $q->whereNull('observaciones')
                    ->orWhere('observaciones', 'NOT LIKE', '%Cita expirada%')
                    ->orWhere('observaciones', 'NOT LIKE', '%Cita no atendida%');
            });
    }

    /*
     * Scope para citas activas (no canceladas ni finalizadas)
     */
    public function scopeActivas($query)
    {
        return $query->whereIn('estado', [self::ESTADO_PENDIENTE, self::ESTADO_CONFIRMADA, self::ESTADO_EN_PROCESO]);
    }

    /**
     * Verificar si la cita ya fue marcada como expirada
     */
    public function yaEstaExpirada(): bool
    {
        return str_contains($this->observaciones ?? '', 'Cita expirada') ||
            str_contains($this->observaciones ?? '', 'Cita no atendida');
    }

    /**
     * Determinar si una cita pendiente ha expirado (más de 24 horas sin confirmar)
     */
    public function pendienteExpirada(): bool
    {
        return $this->estado === self::ESTADO_PENDIENTE &&
            $this->fecha_hora->lt(now()) &&
            !$this->yaEstaExpirada();
    }

    /**
     * Determinar si una cita confirmada no fue atendida (más de 24 horas después de la fecha)
     */
    public function confirmadasNoAtendida(): bool
    {
        return $this->estado === self::ESTADO_CONFIRMADA &&
            $this->fecha_hora->lt(now()->subHours(24)) &&
            !$this->yaEstaExpirada();
    }
}
