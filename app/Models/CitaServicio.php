<?php
// app/Models/CitaServicio.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitaServicio extends Model
{
    use HasFactory;

    protected $table = 'cita_servicio';

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
}