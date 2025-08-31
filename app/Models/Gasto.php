<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
     * Obtener motivos disponibles (alias para compatibilidad)
     */
    public static function getMotivosDisponibles(): array
    {
        return self::getTipos();
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
     * Scope para gastos del año actual
     */
    public function scopeDelAnoActual($query)
    {
        return $query->whereYear('fecha_gasto', now()->year);
    }

    /**
     * Scope para gastos de hoy
     */
    public function scopeDeHoy($query)
    {
        return $query->whereDate('fecha_gasto', today());
    }

    /**
     * Scope para gastos de esta semana
     */
    public function scopeDeLaSemana($query)
    {
        return $query->whereBetween('fecha_gasto', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Accessor para mostrar el tipo formateado
     */
    public function getTipoFormateadoAttribute(): string
    {
        $tipos = self::getTipos();
        return $tipos[$this->tipo] ?? $this->tipo;
    }

    /**
     * Accessor para mostrar el monto formateado
     */
    public function getMontoFormateadoAttribute(): string
    {
        return '$' . number_format($this->monto, 2);
    }

    /**
     * Accessor para verificar si es un gasto reciente (últimos 7 días)
     */
    public function getEsRecienteAttribute(): bool
    {
        return $this->fecha_gasto->diffInDays(now()) <= 7;
    }

    /**
     * Accessor para obtener el color del tipo (para UI)
     */
    public function getColorTipoAttribute(): string
    {
        return match($this->tipo) {
            self::TIPO_STOCK => '#3498db',
            self::TIPO_SUELDOS => '#27ae60',
            self::TIPO_PERSONAL => '#f39c12',
            self::TIPO_MANTENIMIENTO => '#9b59b6',
            self::TIPO_OTRO => '#95a5a6',
            default => '#95a5a6'
        };
    }

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Métodos estáticos para estadísticas rápidas
     */
    public static function totalMesActual(): float
    {
        return self::delMesActual()->sum('monto');
    }

    public static function totalAnoActual(): float
    {
        return self::delAnoActual()->sum('monto');
    }

    public static function promedioMensual(): float
    {
        return self::delMesActual()->avg('monto') ?? 0;
    }

    public static function gastoMayorMes(): float
    {
        return self::delMesActual()->max('monto') ?? 0;
    }

    public static function gastoMenorMes(): float
    {
        return self::delMesActual()->min('monto') ?? 0;
    }

    public static function conteoMesActual(): int
    {
        return self::delMesActual()->count();
    }

    /**
     * Obtener estadísticas por tipo para el mes actual
     */
    public static function estadisticasPorTipo(): array
    {
        return self::selectRaw('tipo, COUNT(*) as cantidad, SUM(monto) as total, AVG(monto) as promedio')
            ->delMesActual()
            ->groupBy('tipo')
            ->get()
            ->mapWithKeys(function ($item) {
                $tipos = self::getTipos();
                return [
                    $item->tipo => [
                        'nombre' => $tipos[$item->tipo] ?? $item->tipo,
                        'cantidad' => $item->cantidad,
                        'total' => $item->total,
                        'promedio' => round($item->promedio, 2),
                        'color' => (new self(['tipo' => $item->tipo]))->color_tipo
                    ]
                ];
            })
            ->toArray();
    }

    /**
     * Obtener los últimos gastos
     */
    public static function ultimosGastos(int $cantidad = 5)
    {
        return self::with('usuario')
            ->latest('fecha_gasto')
            ->limit($cantidad)
            ->get();
    }

    /**
     * Buscar gastos por término
     */
    public function scopeBuscar($query, string $termino)
    {
        return $query->where('detalle', 'LIKE', "%{$termino}%")
                     ->orWhereHas('usuario', function ($q) use ($termino) {
                         $q->where('nombre', 'LIKE', "%{$termino}%")
                           ->orWhere('email', 'LIKE', "%{$termino}%");
                     });
    }
}
