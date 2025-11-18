<?php

namespace Database\Factories;

use App\Models\Servicio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para el modelo Servicio
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Servicio>
 */
class ServicioFactory extends Factory
{
    protected $model = Servicio::class;

    /**
     * Define el estado por defecto del modelo
     */
    public function definition(): array
    {
        $servicios = [
            ['nombre' => 'Lavado Básico', 'precio' => 15.00, 'duracion' => 30, 'categoria' => 'lavado'],
            ['nombre' => 'Lavado Completo', 'precio' => 25.00, 'duracion' => 45, 'categoria' => 'lavado'],
            ['nombre' => 'Lavado Premium', 'precio' => 35.00, 'duracion' => 60, 'categoria' => 'lavado'],
            ['nombre' => 'Encerado', 'precio' => 20.00, 'duracion' => 40, 'categoria' => 'detallado'],
            ['nombre' => 'Pulido', 'precio' => 30.00, 'duracion' => 50, 'categoria' => 'detallado'],
            ['nombre' => 'Limpieza de Tapicería', 'precio' => 40.00, 'duracion' => 60, 'categoria' => 'interior'],
            ['nombre' => 'Aspirado', 'precio' => 10.00, 'duracion' => 20, 'categoria' => 'interior'],
            ['nombre' => 'Limpieza de Motor', 'precio' => 25.00, 'duracion' => 45, 'categoria' => 'motor'],
        ];

        $servicio = $this->faker->randomElement($servicios);

        return [
            'nombre' => $servicio['nombre'],
            'descripcion' => $this->faker->optional()->sentence(),
            'precio' => $servicio['precio'],
            'duracion_min' => $servicio['duracion'],
            'activo' => true,
            'categoria' => $servicio['categoria'],
        ];
    }

    /**
     * Servicio activo
     */
    public function activo(): static
    {
        return $this->state(fn (array $attributes) => [
            'activo' => true,
        ]);
    }

    /**
     * Servicio inactivo
     */
    public function inactivo(): static
    {
        return $this->state(fn (array $attributes) => [
            'activo' => false,
        ]);
    }

    /**
     * Categoría: Lavado
     */
    public function lavado(): static
    {
        return $this->state(fn (array $attributes) => [
            'categoria' => 'lavado',
            'nombre' => $this->faker->randomElement(['Lavado Básico', 'Lavado Completo', 'Lavado Premium']),
            'precio' => $this->faker->randomFloat(2, 15, 40),
            'duracion_min' => $this->faker->numberBetween(20, 60),
        ]);
    }

    /**
     * Categoría: Detallado
     */
    public function detallado(): static
    {
        return $this->state(fn (array $attributes) => [
            'categoria' => 'detallado',
            'nombre' => $this->faker->randomElement(['Encerado', 'Pulido', 'Detallado Completo']),
            'precio' => $this->faker->randomFloat(2, 20, 50),
            'duracion_min' => $this->faker->numberBetween(30, 90),
        ]);
    }

    /**
     * Categoría: Interior
     */
    public function interior(): static
    {
        return $this->state(fn (array $attributes) => [
            'categoria' => 'interior',
            'nombre' => $this->faker->randomElement(['Aspirado', 'Limpieza de Tapicería', 'Limpieza Profunda']),
            'precio' => $this->faker->randomFloat(2, 10, 45),
            'duracion_min' => $this->faker->numberBetween(20, 60),
        ]);
    }

    /**
     * Con precio específico
     */
    public function precio(float $precio): static
    {
        return $this->state(fn (array $attributes) => [
            'precio' => $precio,
        ]);
    }

    /**
     * Con duración específica
     */
    public function duracion(int $minutos): static
    {
        return $this->state(fn (array $attributes) => [
            'duracion_min' => $minutos,
        ]);
    }

    /**
     * Con nombre específico
     */
    public function nombre(string $nombre): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => $nombre,
        ]);
    }

    /**
     * Lavado Básico predefinido
     */
    public function lavadoBasico(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => 'Lavado Básico',
            'descripcion' => 'Lavado exterior completo del vehículo',
            'precio' => 15.00,
            'duracion_min' => 30,
            'activo' => true,
            'categoria' => 'lavado',
        ]);
    }

    /**
     * Encerado predefinido
     */
    public function encerado(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => 'Encerado',
            'descripcion' => 'Aplicación de cera protectora en la carrocería',
            'precio' => 20.00,
            'duracion_min' => 40,
            'activo' => true,
            'categoria' => 'detallado',
        ]);
    }

    /**
     * Limpieza de Tapicería predefinido
     */
    public function limpiezaTapiceria(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => 'Limpieza de Tapicería',
            'descripcion' => 'Limpieza profunda de asientos y tapicería interior',
            'precio' => 40.00,
            'duracion_min' => 60,
            'activo' => true,
            'categoria' => 'interior',
        ]);
    }
}
