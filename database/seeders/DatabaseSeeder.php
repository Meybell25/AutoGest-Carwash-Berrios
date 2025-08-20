<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear Admin por defecto
        Usuario::create([
    'nombre' => 'Meybell',
    'email' => 'mey@example.com',
    'telefono' => '+503 7000-1234',
    'password' => Hash::make('123456'),
    'rol' => Usuario::ROL_CLIENTE,
    'estado' => true,
]);


        // Crear empleados de prueba
        Usuario::factory()
            ->empleado()
            ->count(3)
            ->create();

        // Crear clientes de prueba
        Usuario::factory()
            ->cliente()
            ->count(15)
            ->create();

        // Crear algunos usuarios inactivos para pruebas
        Usuario::factory()
            ->cliente()
            ->inactivo()
            ->count(2)
            ->create();

        

    }
}
