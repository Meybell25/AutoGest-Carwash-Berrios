<?php

namespace Database\Factories;

use App\Models\DiaNoLaborable;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para el modelo DiaNoLaborable
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DiaNoLaborable>
 */
class DiaNoLaborableFactory extends Factory
{
    protected $model = DiaNoLaborable::class;

    /**
     * Define el estado por defecto del modelo
     */
    public function definition(): array
    {
        return [
            'fecha' => $this->faker->dateTimeBetween('now', '+90 days')->format('Y-m-d'),
            'motivo' => $this->faker->randomElement([
                DiaNoLaborable::MOTIVO_FERIADO,
                DiaNoLaborable::MOTIVO_MANTENIMIENTO,
                DiaNoLaborable::MOTIVO_VACACIONES,
                DiaNoLaborable::MOTIVO_EMERGENCIA,
                DiaNoLaborable::MOTIVO_EVENTO_ESPECIAL,
                DiaNoLaborable::MOTIVO_OTRO,
            ]),
        ];
    }

    /**
     * Motivo: Feriado
     */
    public function feriado(): static
    {
        return $this->state(fn (array $attributes) => [
            'motivo' => DiaNoLaborable::MOTIVO_FERIADO,
        ]);
    }

    /**
     * Motivo: Mantenimiento
     */
    public function mantenimiento(): static
    {
        return $this->state(fn (array $attributes) => [
            'motivo' => DiaNoLaborable::MOTIVO_MANTENIMIENTO,
        ]);
    }

    /**
     * Motivo: Vacaciones
     */
    public function vacaciones(): static
    {
        return $this->state(fn (array $attributes) => [
            'motivo' => DiaNoLaborable::MOTIVO_VACACIONES,
        ]);
    }

    /**
     * Motivo: Emergencia
     */
    public function emergencia(): static
    {
        return $this->state(fn (array $attributes) => [
            'motivo' => DiaNoLaborable::MOTIVO_EMERGENCIA,
        ]);
    }

    /**
     * Motivo: Evento especial
     */
    public function eventoEspecial(): static
    {
        return $this->state(fn (array $attributes) => [
            'motivo' => DiaNoLaborable::MOTIVO_EVENTO_ESPECIAL,
        ]);
    }

    /**
     * Motivo: Otro
     */
    public function otro(): static
    {
        return $this->state(fn (array $attributes) => [
            'motivo' => DiaNoLaborable::MOTIVO_OTRO,
        ]);
    }

    /**
     * Fecha específica
     */
    public function fecha(string $fecha): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha' => $fecha,
        ]);
    }

    /**
     * Para hoy
     */
    public function hoy(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha' => now()->format('Y-m-d'),
        ]);
    }

    /**
     * Para mañana
     */
    public function manana(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha' => now()->addDay()->format('Y-m-d'),
        ]);
    }

    /**
     * En N días
     */
    public function enNDias(int $dias): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha' => now()->addDays($dias)->format('Y-m-d'),
        ]);
    }

    /**
     * Hace N días (fecha pasada)
     */
    public function haceNDias(int $dias): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha' => now()->subDays($dias)->format('Y-m-d'),
        ]);
    }

    /**
     * Día futuro aleatorio
     */
    public function futuro(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha' => $this->faker->dateTimeBetween('now', '+180 days')->format('Y-m-d'),
        ]);
    }

    /**
     * Día pasado
     */
    public function pasado(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha' => $this->faker->dateTimeBetween('-90 days', 'yesterday')->format('Y-m-d'),
        ]);
    }

    /**
     * Primer día del próximo mes
     */
    public function proximoMes(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha' => now()->addMonth()->startOfMonth()->format('Y-m-d'),
        ]);
    }

    /**
     * Feriado específico: Día de la Independencia (15 de septiembre)
     */
    public function independencia(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha' => now()->year . '-09-15',
            'motivo' => DiaNoLaborable::MOTIVO_FERIADO,
        ]);
    }

    /**
     * Feriado específico: Navidad (25 de diciembre)
     */
    public function navidad(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha' => now()->year . '-12-25',
            'motivo' => DiaNoLaborable::MOTIVO_FERIADO,
        ]);
    }

    /**
     * Feriado específico: Año Nuevo (1 de enero)
     */
    public function anoNuevo(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha' => (now()->year + 1) . '-01-01',
            'motivo' => DiaNoLaborable::MOTIVO_FERIADO,
        ]);
    }

    /**
     * Feriado específico: Día del Trabajador (1 de mayo)
     */
    public function diaTrabajador(): static
    {
        $fecha = now()->year . '-05-01';
        // Si ya pasó este año, usar el próximo
        if (now()->format('Y-m-d') > $fecha) {
            $fecha = (now()->year + 1) . '-05-01';
        }

        return $this->state(fn (array $attributes) => [
            'fecha' => $fecha,
            'motivo' => DiaNoLaborable::MOTIVO_FERIADO,
        ]);
    }
}
