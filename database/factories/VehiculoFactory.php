<?php

namespace Database\Factories;

use App\Models\Vehiculo;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para el modelo Vehiculo
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehiculo>
 */
class VehiculoFactory extends Factory
{
    protected $model = Vehiculo::class;

    /**
     * Define el estado por defecto del modelo
     */
    public function definition(): array
    {
        $marcas = ['Toyota', 'Honda', 'Nissan', 'Mazda', 'Hyundai', 'Kia', 'Ford', 'Chevrolet', 'Mitsubishi', 'Suzuki'];
        $modelos = ['Corolla', 'Civic', 'Sentra', 'CX-5', 'Tucson', 'Sportage', 'F-150', 'Spark', 'Lancer', 'Swift'];
        $colores = ['Blanco', 'Negro', 'Gris', 'Plateado', 'Rojo', 'Azul', 'Verde', 'Amarillo'];

        return [
            'usuario_id' => Usuario::factory(),
            'placa' => $this->generarPlaca(),
            'marca' => $this->faker->randomElement($marcas),
            'modelo' => $this->faker->randomElement($modelos),
            'tipo' => $this->faker->randomElement([
                Vehiculo::TIPO_SEDAN,
                Vehiculo::TIPO_PICKUP,
                Vehiculo::TIPO_CAMION,
                Vehiculo::TIPO_MOTO,
            ]),
            'color' => $this->faker->randomElement($colores),
            'descripcion' => $this->faker->optional()->sentence(),
            'fecha_registro' => now(),
        ];
    }

    /**
     * Generar placa con formato salvadoreño: ABC-123
     */
    private function generarPlaca(): string
    {
        $letras = strtoupper($this->faker->lexify('???'));
        $numeros = $this->faker->numerify('###');
        return "{$letras}-{$numeros}";
    }

    /**
     * Tipo: Sedán
     */
    public function sedan(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => Vehiculo::TIPO_SEDAN,
        ]);
    }

    /**
     * Tipo: Pickup
     */
    public function pickup(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => Vehiculo::TIPO_PICKUP,
        ]);
    }

    /**
     * Tipo: Camión
     */
    public function camion(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => Vehiculo::TIPO_CAMION,
        ]);
    }

    /**
     * Tipo: Moto
     */
    public function moto(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => Vehiculo::TIPO_MOTO,
        ]);
    }

    /**
     * Marca específica
     */
    public function marca(string $marca): static
    {
        return $this->state(fn (array $attributes) => [
            'marca' => $marca,
        ]);
    }

    /**
     * Modelo específico
     */
    public function modelo(string $modelo): static
    {
        return $this->state(fn (array $attributes) => [
            'modelo' => $modelo,
        ]);
    }

    /**
     * Color específico
     */
    public function color(string $color): static
    {
        return $this->state(fn (array $attributes) => [
            'color' => $color,
        ]);
    }

    /**
     * Con placa específica
     */
    public function placa(string $placa): static
    {
        return $this->state(fn (array $attributes) => [
            'placa' => $placa,
        ]);
    }

    /**
     * Para usuario específico
     */
    public function paraUsuario(int $usuarioId): static
    {
        return $this->state(fn (array $attributes) => [
            'usuario_id' => $usuarioId,
        ]);
    }

    /**
     * Toyota Corolla (vehículo común)
     */
    public function toyotaCorolla(): static
    {
        return $this->state(fn (array $attributes) => [
            'marca' => 'Toyota',
            'modelo' => 'Corolla',
            'tipo' => Vehiculo::TIPO_SEDAN,
            'color' => 'Blanco',
        ]);
    }

    /**
     * Ford F-150 (pickup)
     */
    public function fordF150(): static
    {
        return $this->state(fn (array $attributes) => [
            'marca' => 'Ford',
            'modelo' => 'F-150',
            'tipo' => Vehiculo::TIPO_PICKUP,
            'color' => 'Negro',
        ]);
    }
}
