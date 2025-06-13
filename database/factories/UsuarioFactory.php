<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'telefono' => $this->faker->phoneNumber(),
            'email_verified_at' => now(),
            'password' => bcrypt('password123'), // password por defecto
            'rol' => 'cliente',
            'estado' => true,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Estado para crear un admin
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'rol' => Usuario::ROL_ADMIN,
            'nombre' => 'Administrador',
            'email' => 'admin@sistemacitas.com',
        ]);
    }

    /**
     * Estado para crear un empleado
     */
    public function empleado(): static
    {
        return $this->state(fn (array $attributes) => [
            'rol' => Usuario::ROL_EMPLEADO,
            'nombre' => 'Empleado ' . $this->faker->lastName(),
            'email' => 'empleado' . rand(1, 100) . '@sistemacitas.com',
        ]);
    }

    /**
     * Estado para crear un cliente
     */
    public function cliente(): static
    {
        return $this->state(fn (array $attributes) => [
            'rol' => Usuario::ROL_CLIENTE,
        ]);
    }

    /**
     * Estado para usuario inactivo
     */
    public function inactivo(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => false,
        ]);
    }

    /**
     * Email verificado
     */
    public function emailVerificado(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => $this->faker->dateTime(),
        ]);
    }

    /**
     * Email no verificado
     */
    public function emailNoVerificado(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}