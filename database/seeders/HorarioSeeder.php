<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Horario;

class HorarioSeeder extends Seeder
{
    public function run(): void
    {
        $dias = [1,2,3,4,5,6]; // Lunes a Sábado

        // Tamaño del bloque en minutos (variable por ENV, default 30)
        $slotMinutes = (int) env('HORARIO_SLOT_MINUTES', 30);
        if ($slotMinutes <= 0) { $slotMinutes = 30; }

        foreach ($dias as $dia) {
            // Mañana 08:00-12:00
            $this->generarBloques($dia, '08:00', '12:00', $slotMinutes);
            // Tarde 13:00-17:00
            $this->generarBloques($dia, '13:00', '17:00', $slotMinutes);
        }
    }

    private function generarBloques(int $dia, string $inicio, string $fin, int $slotMinutes = 30): void
    {
        $actual = \Carbon\Carbon::createFromFormat('H:i', $inicio);
        $limite = \Carbon\Carbon::createFromFormat('H:i', $fin);

        while ($actual < $limite) {
            $horaInicio = $actual->format('H:i:s');
            $horaFin = $actual->copy()->addMinutes($slotMinutes);
            if ($horaFin > $limite) {
                break;
            }
            Horario::updateOrCreate(
                [
                    'dia_semana' => $dia,
                    'hora_inicio' => $horaInicio,
                ],
                [
                    'hora_fin' => $horaFin->format('H:i:s'),
                    'activo' => true,
                ]
            );
            $actual->addMinutes($slotMinutes);
        }
    }
}
