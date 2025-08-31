<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Horario;

class HorarioSeeder extends Seeder
{
    public function run(): void
    {
        // Lunes (1) a Viernes (5): 08:00-12:00 y 13:00-17:00
        foreach (range(1, 5) as $dia) {
            Horario::updateOrCreate(
                ['dia_semana' => $dia, 'hora_inicio' => '08:00:00'],
                ['hora_fin' => '12:00:00', 'activo' => true]
            );
            Horario::updateOrCreate(
                ['dia_semana' => $dia, 'hora_inicio' => '13:00:00'],
                ['hora_fin' => '17:00:00', 'activo' => true]
            );
        }

        // Sábado (6): 08:00-12:00
        Horario::updateOrCreate(
            ['dia_semana' => 6, 'hora_inicio' => '08:00:00'],
            ['hora_fin' => '12:00:00', 'activo' => true]
        );

        // Domingo (0): sin horarios (no se crean registros)
    }
}

