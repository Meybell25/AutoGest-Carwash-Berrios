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
        'monto' => 'float',
        'monto_recibido' => 'float',
        'vuelto' => 'float',
        'fecha_pago' => 'datetime',
    ];

    // Desactivar timestamps automáticos ya que usamos fecha_pago
    public $timestamps = false;

    // Métodos de pago
    const METODO_EFECTIVO = 'efectivo';
    const METODO_TRANSFERENCIA = 'transferencia';
    const METODO_PASARELA = 'pasarela';

    // Estados de pago
    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_PAGADO = 'pagado';
    const ESTADO_RECHAZADO = 'rechazado';

    // Relaciones
    public function cita()
    {
        return $this->belongsTo(Cita::class, 'cita_id');
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('estado', self::ESTADO_PENDIENTE);
    }

    public function scopePagados($query)
    {
        return $query->where('estado', self::ESTADO_PAGADO);
    }

    public function scopeRechazados($query)
    {
        return $query->where('estado', self::ESTADO_RECHAZADO);
    }

    public function scopeByMetodo($query, $metodo)
    {
        return $query->where('metodo', $metodo);
    }

    public function scopeEfectivo($query)
    {
        return $query->where('metodo', self::METODO_EFECTIVO);
    }

    public function scopeTransferencia($query)
    {
        return $query->where('metodo', self::METODO_TRANSFERENCIA);
    }

    public function scopePasarela($query)
    {
        return $query->where('metodo', self::METODO_PASARELA);
    }

    // Métodos
    public function marcarComoPagado(): bool
    {
        return $this->update([
            'estado' => self::ESTADO_PAGADO,
            'fecha_pago' => now(),
        ]);
    }

    public function marcarComoRechazado(): bool
    {
        return $this->update(['estado' => self::ESTADO_RECHAZADO]);
    }

    public function calcularVuelto(): float
    {
        if ($this->metodo === self::METODO_EFECTIVO && $this->monto_recibido) {
            return max(0, $this->monto_recibido - $this->monto);
        }
        return 0;
    }

    public function isPendiente(): bool
    {
        return $this->estado === self::ESTADO_PENDIENTE;
    }

    public function isPagado(): bool
    {
        return $this->estado === self::ESTADO_PAGADO;
    }

    public function isRechazado(): bool
    {
        return $this->estado === self::ESTADO_RECHAZADO;
    }

    public function isEfectivo(): bool
    {
        return $this->metodo === self::METODO_EFECTIVO;
    }

    public function isTransferencia(): bool
    {
        return $this->metodo === self::METODO_TRANSFERENCIA;
    }

    public function isPasarela(): bool
    {
        return $this->metodo === self::METODO_PASARELA;
    }
}