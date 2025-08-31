<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitaServicio extends Model
{
    use HasFactory;

    protected $table = 'cita_servicio';
    
    // Deshabilitar timestamps ya que es tabla pivot
    public $timestamps = false;

    protected $fillable = [
        'cita_id',
        'servicio_id',
        'precio',
        'descuento',
        'observacion',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'descuento' => 'decimal:2',
    ];

    // Relaciones
    public function cita()
    {
        return $this->belongsTo(Cita::class, 'cita_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    // Accessors
    public function getPrecioFinalAttribute(): float
    {
        return $this->precio - $this->descuento;
    }

    public function getDescuentoPorcentajeAttribute(): float
    {
        if ($this->precio > 0) {
            return round(($this->descuento / $this->precio) * 100, 2);
        }
        return 0;
    }

    // Scopes
    public function scopeConDescuento($query)
    {
        return $query->where('descuento', '>', 0);
    }

    public function scopeSinDescuento($query)
    {
        return $query->where('descuento', 0);
    }

    // Métodos de validación
    public function validarDescuento(): bool
    {
        return $this->descuento >= 0 && $this->descuento <= $this->precio;
    }

    public function aplicarDescuentoPorcentaje(float $porcentaje): void
    {
        $porcentaje = max(0, min(100, $porcentaje)); // Limitar entre 0-100%
        $this->descuento = ($this->precio * $porcentaje) / 100;
    }
}