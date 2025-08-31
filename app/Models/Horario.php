<?php
// app/Models/Horario.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $table = 'horarios';

    protected $fillable = [
        'dia_semana',
        'hora_inicio',
        'hora_fin',
        'activo',
    ];

    protected $casts = [
        'dia_semana' => 'integer',
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
        'activo' => 'boolean',
    ];

    // Días de la semana (0 = Domingo, 1 = Lunes, ..., 6 = Sábado)
    const DOMINGO = 0;
    const LUNES = 1;
    const MARTES = 2;
    const MIERCOLES = 3;
    const JUEVES = 4;
    const VIERNES = 5;
    const SABADO = 6;

    // Array de nombres de días
    const NOMBRES_DIAS = [
        0 => 'Domingo',
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
    ];

    // Accessors
    public function getNombreDiaAttribute(): string
    {
        return self::NOMBRES_DIAS[$this->dia_semana] ?? 'Desconocido';
    }

    public function getHoraInicioFormateadaAttribute(): string
    {
        return $this->hora_inicio ? $this->hora_inicio->format('H:i') : '';
    }

    public function getHoraFinFormateadaAttribute(): string
    {
        return $this->hora_fin ? $this->hora_fin->format('H:i') : '';
    }

    public function getHorarioCompletoAttribute(): string
    {
        return $this->nombre_dia . ': ' . $this->hora_inicio_formateada . ' - ' . $this->hora_fin_formateada;
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeInactivos($query)
    {
        return $query->where('activo', false);
    }

    public function scopeByDia($query, int $dia)
    {
        return $query->where('dia_semana', $dia);
    }

    public function scopeOrdenadoPorDia($query)
    {
        return $query->orderBy('dia_semana')->orderBy('hora_inicio');
    }

    // Métodos
    public function estaAbierto($hora = null): bool
    {
        if (!$this->activo) {
            return false;
        }

        $hora = $hora ?? now()->format('H:i');

        return $hora >= $this->hora_inicio_formateada && 
               $hora <= $this->hora_fin_formateada;
    }

    public function getDuracionMinutos(): int
    {
        if (!$this->hora_inicio || !$this->hora_fin) {
            return 0;
        }

        $inicio = strtotime($this->hora_inicio_formateada);
        $fin = strtotime($this->hora_fin_formateada);

        return ($fin - $inicio) / 60;
    }

    // Métodos estáticos
    public static function getHorarioHoy(): ?self
    {
        $diaHoy = now()->dayOfWeek;
        return self::activos()->byDia($diaHoy)->first();
    }

    public static function estaAbiertoAhora(): bool
    {
        $horarioHoy = self::getHorarioHoy();

        if (!$horarioHoy) {
            return false;
        }

        return $horarioHoy->estaAbierto();
    }

    public static function getHorariosActivos(): array
    {
        return self::activos()
            ->ordenadoPorDia()
            ->get()
            ->groupBy('dia_semana')
            ->map(function ($horarios) {
                return $horarios->map(function ($horario) {
                    return [
                        'dia' => $horario->nombre_dia,
                        'horario' => $horario->hora_inicio_formateada . ' - ' . $horario->hora_fin_formateada,
                        'activo' => $horario->activo
                    ];
                });
            })
            ->toArray();
    }

    public static function getProximaApertura(): ?string
    {
        if (self::estaAbiertoAhora()) {
            return null;
        }

        $diaActual = now()->dayOfWeek;
        $horaActual = now()->format('H:i');

        // Buscar en el día actual
        $horarioHoy = self::activos()
            ->byDia($diaActual)
            ->where('hora_inicio', '>', $horaActual)
            ->orderBy('hora_inicio')
            ->first();

        if ($horarioHoy) {
            return 'Hoy a las ' . $horarioHoy->hora_inicio_formateada;
        }

        // Buscar en los próximos días
        for ($i = 1; $i <= 7; $i++) {
            $dia = ($diaActual + $i) % 7;
            $horario = self::activos()
                ->byDia($dia)
                ->orderBy('hora_inicio')
                ->first();

            if ($horario) {
                $nombreDia = self::NOMBRES_DIAS[$dia];
                return $nombreDia . ' a las ' . $horario->hora_inicio_formateada;
            }
        }

        return null;
    }
}