<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'duracion_min',
        'activo',
        'categoria',
    ];

    protected $casts = [
        'precio' => 'float',
        'duracion_min' => 'integer',
        'activo' => 'boolean',
    ];

    // Relaciones
    public function citas()
    {
        return $this->belongsToMany(Cita::class, 'cita_servicio', 'servicio_id', 'cita_id')
                    ->withPivot(['precio', 'descuento', 'observacion']);
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeByCategoria($query, string $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    // Accessors
    public function getDuracionFormattedAttribute(): string
    {
        $horas = intval($this->duracion_min / 60);
        $minutos = $this->duracion_min % 60;
        
        if ($horas > 0) {
            return $minutos > 0 ? "{$horas}h {$minutos}min" : "{$horas}h";
        }
        
        return "{$minutos}min";
    }
}