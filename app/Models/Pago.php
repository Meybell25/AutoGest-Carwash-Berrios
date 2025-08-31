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
        'observaciones',
        'detalles_adicionales'
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'monto_recibido' => 'decimal:2',
        'vuelto' => 'decimal:2',
        'fecha_pago' => 'datetime',
        'detalles_adicionales' => 'array'
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
     * Obtener todos los estados disponibles
     */
    public static function getEstados(): array
    {
        return [
            self::ESTADO_PENDIENTE => 'Pendiente',
            self::ESTADO_PAGADO => 'Pagado',
            self::ESTADO_RECHAZADO => 'Rechazado/Reembolsado',
        ];
    }

    /**
     * Obtener todos los métodos de pago disponibles
     */
    public static function getMetodos(): array
    {
        return [
            self::METODO_EFECTIVO => 'Efectivo',
            self::METODO_TRANSFERENCIA => 'Transferencia Bancaria',
            self::METODO_PASARELA => 'Pasarela de Pago (Tarjeta)',
        ];
    }

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
     * Verificar si el pago fue rechazado/reembolsado
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
     * Verificar si el pago es por transferencia
     */
    public function isTransferencia(): bool
    {
        return $this->metodo === self::METODO_TRANSFERENCIA;
    }

    /**
     * Verificar si el pago es por pasarela
     */
    public function isPasarela(): bool
    {
        return $this->metodo === self::METODO_PASARELA;
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
        $this->vuelto = $this->calcularVuelto();
        return $this->save();
    }

    /**
     * Obtener el estado formateado
     */
    public function getEstadoFormattedAttribute(): string
    {
        return self::getEstados()[$this->estado] ?? $this->estado;
    }

    /**
     * Obtener el método formateado
     */
    public function getMetodoFormattedAttribute(): string
    {
        return self::getMetodos()[$this->metodo] ?? $this->metodo;
    }

    /**
     * Obtener información del banco (si es transferencia)
     */
    public function getBancoEmisorAttribute(): ?string
    {
        if ($this->isTransferencia() && isset($this->detalles_adicionales['banco_emisor'])) {
            return $this->detalles_adicionales['banco_emisor'];
        }
        return null;
    }

    /**
     * Obtener tipo de tarjeta (si es pasarela)
     */
    public function getTipoTarjetaAttribute(): ?string
    {
        if ($this->isPasarela() && isset($this->detalles_adicionales['tipo_tarjeta'])) {
            return $this->detalles_adicionales['tipo_tarjeta'];
        }
        return null;
    }

    /**
     * Obtener descuentos aplicados
     */
    public function getDescuentosAplicadosAttribute(): array
    {
        return $this->detalles_adicionales['descuentos_aplicados'] ?? [];
    }

    /**
     * Obtener total antes de descuentos
     */
    public function getTotalAntesDescuentosAttribute(): float
    {
        return $this->detalles_adicionales['total_antes_descuentos'] ?? $this->monto;
    }

    /**
     * Obtener total de descuentos aplicados
     */
    public function getTotalDescuentosAttribute(): float
    {
        return $this->detalles_adicionales['total_descuentos'] ?? 0;
    }

    /**
     * Obtener ID del admin que procesó el pago
     */
    public function getAdminIdAttribute(): ?int
    {
        return $this->detalles_adicionales['admin_id'] ?? null;
    }

    /**
     * Verificar si tiene vuelto
     */
    public function tieneVuelto(): bool
    {
        return $this->vuelto > 0;
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
     * Scope para pagos rechazados
     */
    public function scopeRechazados($query)
    {
        return $query->where('estado', self::ESTADO_RECHAZADO);
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

    /**
     * Scope para pagos de hoy
     */
    public function scopeHoy($query)
    {
        return $query->whereDate('fecha_pago', today());
    }

    /**
     * Scope para pagos del mes actual
     */
    public function scopeDelMes($query)
    {
        return $query->whereMonth('fecha_pago', now()->month)
                    ->whereYear('fecha_pago', now()->year);
    }

    /**
     * Scope para pagos con vuelto
     */
    public function scopeConVuelto($query)
    {
        return $query->where('vuelto', '>', 0);
    }

    // Relaciones
    public function cita()
    {
        return $this->belongsTo(Cita::class, 'cita_id');
    }

    /**
     * Obtener el admin que procesó el pago
     */
    public function administrador()
    {
        if ($this->admin_id) {
            return $this->belongsTo(\App\Models\Usuario::class, 'detalles_adicionales->admin_id');
        }
        return null;
    }

    /**
     * Validar los datos del pago antes de guardar
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($pago) {
            // Calcular vuelto automáticamente
            if ($pago->monto_recibido && $pago->monto) {
                $pago->vuelto = max(0, $pago->monto_recibido - $pago->monto);
            }

            // Validar que el monto recibido no sea menor al total (excepto para pasarela)
            if ($pago->metodo !== self::METODO_PASARELA && $pago->monto_recibido < $pago->monto) {
                throw new \InvalidArgumentException('El monto recibido no puede ser menor al monto total para pagos en ' . $pago->metodo);
            }

            // Para transferencia y pasarela, monto recibido = monto total
            if (in_array($pago->metodo, [self::METODO_TRANSFERENCIA, self::METODO_PASARELA])) {
                $pago->monto_recibido = $pago->monto;
                $pago->vuelto = 0;
            }
        });
    }
}