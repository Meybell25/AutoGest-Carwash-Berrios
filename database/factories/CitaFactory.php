<?php

namespace Database\Factories;

use App\Models\Cita;
use App\Models\Usuario;
use App\Models\Vehiculo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para el modelo Cita
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cita>
 */
class CitaFactory extends Factory
{
    protected $model = Cita::class;

    /**
     * Define el estado por defecto del modelo
     */
    public function definition(): array
    {
        // Agregar microsegundos aleatorios para garantizar unicidad (unique constraint en fecha_hora)
        $fecha = $this->faker->dateTimeBetween('now', '+30 days');
        $fecha->modify('+' . $this->faker->numberBetween(0, 999999) . ' microseconds');

        return [
            'usuario_id' => Usuario::factory(),
            'vehiculo_id' => Vehiculo::factory(),
            'fecha_hora' => $fecha,
            'estado' => Cita::ESTADO_PENDIENTE,
            'observaciones' => $this->faker->optional()->sentence(),
        ];
    }

    /**
     * Estado: Cita pendiente
     */
    public function pendiente(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => Cita::ESTADO_PENDIENTE,
        ]);
    }

    /**
     * Estado: Cita confirmada
     */
    public function confirmada(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => Cita::ESTADO_CONFIRMADA,
        ]);
    }

    /**
     * Estado: Cita en proceso
     */
    public function enProceso(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => Cita::ESTADO_EN_PROCESO,
        ]);
    }

    /**
     * Estado: Cita finalizada
     */
    public function finalizada(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => Cita::ESTADO_FINALIZADA,
            'fecha_hora' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ]);
    }

    /**
     * Estado: Cita cancelada
     */
    public function cancelada(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => Cita::ESTADO_CANCELADA,
            'observaciones' => 'Cita cancelada por el cliente',
        ]);
    }

    /**
     * Cita para hoy
     */
    public function hoy(): static
    {
        return $this->state(function (array $attributes) {
            $fecha = now()->setTime(
                $this->faker->numberBetween(8, 17),
                $this->faker->randomElement([0, 15, 30, 45]),
                $this->faker->numberBetween(0, 59)
            );
            $fecha->addMicroseconds($this->faker->numberBetween(0, 999999));

            return ['fecha_hora' => $fecha];
        });
    }

    /**
     * Cita para mañana
     */
    public function manana(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_hora' => now()->addDay()->setTime(
                $this->faker->numberBetween(8, 17),
                $this->faker->randomElement([0, 15, 30, 45])
            ),
        ]);
    }

    /**
     * Cita pasada (expirada)
     */
    public function pasada(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_hora' => $this->faker->dateTimeBetween('-30 days', '-1 day'),
        ]);
    }

    /**
     * Con observaciones específicas
     */
    public function conObservaciones(string $observaciones): static
    {
        return $this->state(fn (array $attributes) => [
            'observaciones' => $observaciones,
        ]);
    }

    /**
     * Para un usuario específico
     */
    public function paraUsuario(int $usuarioId): static
    {
        return $this->state(fn (array $attributes) => [
            'usuario_id' => $usuarioId,
        ]);
    }

    /**
     * Para un vehículo específico
     */
    public function paraVehiculo(int $vehiculoId): static
    {
        return $this->state(fn (array $attributes) => [
            'vehiculo_id' => $vehiculoId,
        ]);
    }

    /**
     * Cita de hace N días
     */
    public function haceNDias(int $dias): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_hora' => now()->subDays($dias)->setTime(
                $this->faker->numberBetween(8, 17),
                $this->faker->randomElement([0, 15, 30, 45])
            ),
        ]);
    }

    /**
     * Cita en N días
     */
    public function enNDias(int $dias): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_hora' => now()->addDays($dias)->setTime(
                $this->faker->numberBetween(8, 17),
                $this->faker->randomElement([0, 15, 30, 45])
            ),
        ]);
    }
}
