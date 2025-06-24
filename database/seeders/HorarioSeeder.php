<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Horario;

class HorarioSeeder extends Seeder
{
    public function run(): void
    {
        // Definimos bloques horarios
        $bloques = [
            ['hora_inicio' => '08:00', 'hora_fin' => '12:00'],
            ['hora_inicio' => '13:00', 'hora_fin' => '17:00'],
        ];

        // Cargamos para todos los días de la semana (0 a 6)
        for ($dia = 0; $dia <= 6; $dia++) {
            foreach ($bloques as $bloque) {
                Horario::create([
                    'dia_semana' => $dia,
                    'hora_inicio' => $bloque['hora_inicio'],
                    'hora_fin' => $bloque['hora_fin'],
                    'activo' => true,
                ]);
            }
        }
    }
}
