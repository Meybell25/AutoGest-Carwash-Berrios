<?php
// app/Models/Vehiculo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    protected $table = 'vehiculos';

     public $timestamps = false;
    protected $fillable = [
        'usuario_id',
        'placa',
        'marca',
        'modelo',
        'tipo',
        'color',
        'descripcion',
        'fecha_registro',
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',
    ];

    // Tipos de vehículos permitidos
    const TIPO_SEDAN = 'sedan';
    const TIPO_PICKUP = 'pickup';
    const TIPO_CAMION = 'camion';
    const TIPO_MOTO = 'moto';

    public static function getTipos(): array
    {
        return [
            self::TIPO_SEDAN => 'Sedán',
            self::TIPO_PICKUP => 'Pickup',
            self::TIPO_CAMION => 'Camión',
            self::TIPO_MOTO => 'Motocicleta',
        ];
    }

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'vehiculo_id');
    }

    // Scopes
    public function scopeByTipo($query, string $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopeByUsuario($query, int $usuarioId)
    {
        return $query->where('usuario_id', $usuarioId);
    }

    // Accessors
    public function getTipoFormattedAttribute(): string
    {
        return self::getTipos()[$this->tipo] ?? $this->tipo;
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->marca} {$this->modelo} ({$this->placa})";
    }
}