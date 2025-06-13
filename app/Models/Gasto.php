<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    use HasFactory;

    protected $table = 'gastos';

    protected $fillable = [
        'usuario_id',
        'tipo',
        'detalle',
        'monto',
        'fecha_gasto',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_gasto' => 'datetime',
    ];

    // Tipos de gastos constantes
    const TIPO_STOCK = 'stock';
    const TIPO_SUELDOS = 'sueldos';
    const TIPO_PERSONAL = 'personal';
    const TIPO_MANTENIMIENTO = 'mantenimiento';
    const TIPO_OTRO = 'otro';

    /**
     * Obtener los tipos de gastos disponibles
     */
    public static function getTipos(): array
    {
        return [
            self::TIPO_STOCK => 'Stock/Inventario',
            self::TIPO_SUELDOS => 'Sueldos',
            self::TIPO_PERSONAL => 'Personal',
            self::TIPO_MANTENIMIENTO => 'Mantenimiento',
            self::TIPO_OTRO => 'Otro',
        ];
    }

    /**
     * Scope para filtrar por tipo
     */
    public function scopeByTipo($query, string $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope para gastos de un rango de fechas
     */
    public function scopeBetweenDates($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_gasto', [$fechaInicio, $fechaFin]);
    }

    /**
     * Scope para gastos del mes actual
     */
    public function scopeDelMesActual($query)
    {
        return $query->whereYear('fecha_gasto', now()->year)
                     ->whereMonth('fecha_gasto', now()->month);
    }

    /**
     * Accessor para mostrar el tipo formateado
     */
    public function getTipoFormateadoAttribute(): string
    {
        $tipos = self::getTipos();
        return $tipos[$this->tipo] ?? $this->tipo;
    }

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}