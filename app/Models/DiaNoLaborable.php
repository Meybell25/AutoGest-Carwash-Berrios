<?php
// app/Models/DiaNoLaborable.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DiaNoLaborable extends Model
{
    use HasFactory;

    protected $table = 'dias_no_laborables';

    protected $fillable = [
        'fecha',
        'motivo',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // Tipos de motivos comunes
    const MOTIVO_FERIADO = 'feriado';
    const MOTIVO_MANTENIMIENTO = 'mantenimiento';
    const MOTIVO_VACACIONES = 'vacaciones';
    const MOTIVO_EMERGENCIA = 'emergencia';
    const MOTIVO_EVENTO_ESPECIAL = 'evento_especial';
    const MOTIVO_OTRO = 'otro';

    // Accessors
    public function getFechaFormateadaAttribute(): string
    {
        return $this->fecha ? $this->fecha->format('d/m/Y') : '';
    }

    public function getFechaCompleraAttribute(): string
    {
        return $this->fecha ? $this->fecha->format('l, d \de F \de Y') : '';
    }

    public function getDiasRestantesAttribute(): int
    {
        if (!$this->fecha) {
            return 0;
        }

        $hoy = Carbon::today();
        
        if ($this->fecha->isPast()) {
            return 0;
        }

        return $hoy->diffInDays($this->fecha);
    }

    public function getEsPasadoAttribute(): bool
    {
        return $this->fecha ? $this->fecha->isPast() : false;
    }

    public function getEsHoyAttribute(): bool
    {
        return $this->fecha ? $this->fecha->isToday() : false;
    }

    public function getEsFuturoAttribute(): bool
    {
        return $this->fecha ? $this->fecha->isFuture() : false;
    }

    // Scopes
    public function scopeFuturos($query)
    {
        return $query->where('fecha', '>=', Carbon::today());
    }

    public function scopePasados($query)
    {
        return $query->where('fecha', '<', Carbon::today());
    }

    public function scopeHoy($query)
    {
        return $query->whereDate('fecha', Carbon::today());
    }

    public function scopeProximoMes($query)
    {
        return $query->whereBetween('fecha', [
            Carbon::today(),
            Carbon::today()->addMonth()
        ]);
    }

    public function scopeProximaSemana($query)
    {
        return $query->whereBetween('fecha', [
            Carbon::today(),
            Carbon::today()->addWeek()
        ]);
    }

    public function scopeByMotivo($query, string $motivo)
    {
        return $query->where('motivo', $motivo);
    }

    public function scopeEnRango($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
    }

    public function scopeOrdenadoPorFecha($query, $direccion = 'asc')
    {
        return $query->orderBy('fecha', $direccion);
    }

    // Métodos estáticos
    public static function esNoLaborable($fecha): bool
    {
        $fecha = Carbon::parse($fecha)->format('Y-m-d');
        
        return self::where('fecha', $fecha)->exists();
    }

    public static function getProximosNoLaborables($limite = 5): \Illuminate\Database\Eloquent\Collection
    {
        return self::futuros()
            ->ordenadoPorFecha()
            ->limit($limite)
            ->get();
    }

    public static function getNoLaborablesDelMes($mes = null, $año = null): \Illuminate\Database\Eloquent\Collection
    {
        $mes = $mes ?? Carbon::now()->month;
        $año = $año ?? Carbon::now()->year;

        $inicioMes = Carbon::create($año, $mes, 1);
        $finMes = $inicioMes->copy()->endOfMonth();

        return self::enRango($inicioMes, $finMes)
            ->ordenadoPorFecha()
            ->get();
    }

    public static function contarNoLaborablesEnRango($fechaInicio, $fechaFin): int
    {
        return self::enRango($fechaInicio, $fechaFin)->count();
    }

    public static function getFechasNoLaborables($fechaInicio, $fechaFin): array
    {
        return self::enRango($fechaInicio, $fechaFin)
            ->pluck('fecha')
            ->map(function ($fecha) {
                return $fecha->format('Y-m-d');
            })
            ->toArray();
    }

    // Método para obtener días laborables en un rango
    public static function getDiasLaborablesEnRango($fechaInicio, $fechaFin): array
    {
        $fechaInicio = Carbon::parse($fechaInicio);
        $fechaFin = Carbon::parse($fechaFin);
        
        $diasLaborables = [];
        $fechasNoLaborables = self::getFechasNoLaborables($fechaInicio, $fechaFin);
        
        while ($fechaInicio->lte($fechaFin)) {
            // Excluir domingos (0) y días no laborables específicos
            if ($fechaInicio->dayOfWeek !== 0 && 
                !in_array($fechaInicio->format('Y-m-d'), $fechasNoLaborables)) {
                $diasLaborables[] = $fechaInicio->format('Y-m-d');
            }
            $fechaInicio->addDay();
        }
        
        return $diasLaborables;
    }

    // Obtener motivos disponibles
    public static function getMotivosDisponibles(): array
    {
        return [
            self::MOTIVO_FERIADO => 'Feriado Nacional',
            self::MOTIVO_MANTENIMIENTO => 'Mantenimiento de Instalaciones',
            self::MOTIVO_VACACIONES => 'Vacaciones del Personal',
            self::MOTIVO_EMERGENCIA => 'Emergencia',
            self::MOTIVO_EVENTO_ESPECIAL => 'Evento Especial',
            self::MOTIVO_OTRO => 'Otro',
        ];
    }
}