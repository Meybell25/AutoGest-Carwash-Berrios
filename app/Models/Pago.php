<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';

    protected $fillable = [
        'cita_id',
        'monto',
        'monto_recibido',
        'vuelto',
        'metodo',
        'referencia',
        'estado',
        'pasarela_id',
        'fecha_pago',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'monto_recibido' => 'decimal:2',
        'vuelto' => 'decimal:2',
        'fecha_pago' => 'datetime',
    ];

    // Estados de pago
    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_PAGADO = 'pagado';
    const ESTADO_RECHAZADO = 'rechazado';

    // Métodos de pago
    const METODO_EFECTIVO = 'efectivo';
    const METODO_TRANSFERENCIA = 'transferencia';
    const METODO_PASARELA = 'pasarela';

    /**
     * Verificar si el pago está pagado
     */
    public function isPagado(): bool
    {
        return $this->estado === self::ESTADO_PAGADO;
    }

    /**
     * Verificar si el pago está pendiente
     */
    public function isPendiente(): bool
    {
        return $this->estado === self::ESTADO_PENDIENTE;
    }

    /**
     * Verificar si el pago fue rechazado
     */
    public function isRechazado(): bool
    {
        return $this->estado === self::ESTADO_RECHAZADO;
    }

    /**
     * Verificar si el pago es en efectivo
     */
    public function isEfectivo(): bool
    {
        return $this->metodo === self::METODO_EFECTIVO;
    }

    /**
     * Calcular el vuelto automáticamente
     */
    public function calcularVuelto(): float
    {
        if ($this->monto_recibido > $this->monto) {
            return $this->monto_recibido - $this->monto;
        }
        return 0.00;
    }

    /**
     * Marcar pago como pagado
     */
    public function marcarComoPagado(): bool
    {
        $this->estado = self::ESTADO_PAGADO;
        $this->fecha_pago = now();
        return $this->save();
    }

    /**
     * Scope para pagos pagados
     */
    public function scopePagados($query)
    {
        return $query->where('estado', self::ESTADO_PAGADO);
    }

    /**
     * Scope para pagos pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', self::ESTADO_PENDIENTE);
    }

    /**
     * Scope por método de pago
     */
    public function scopeByMetodo($query, string $metodo)
    {
        return $query->where('metodo', $metodo);
    }

    /**
     * Scope para pagos de un periodo
     */
    public function scopeEnPeriodo($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_pago', [$fechaInicio, $fechaFin]);
    }

    // Relaciones
    public function cita()
    {
        return $this->belongsTo(Cita::class, 'cita_id');
    }
}