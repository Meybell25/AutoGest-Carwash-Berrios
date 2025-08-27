<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cita;

class CleanDuplicateExpirationMessages extends Command
{
    protected $signature = 'citas:clean-duplicates';
    protected $description = 'Limpia mensajes duplicados de expiración en las observaciones de las citas';

   public function handle()
{
    $this->info('Buscando citas con mensajes de expiración...');
    
    // Buscar TODAS las citas que tengan mensajes de expiración
    $citas = Cita::where('observaciones', 'LIKE', '%Cita expirada%')
                 ->orWhere('observaciones', 'LIKE', '%No atendida%')
                 ->get();

    $this->info('Encontradas ' . $citas->count() . ' citas con mensajes de expiración.');

    foreach ($citas as $cita) {
        $original = $cita->observaciones;
        
        // Estrategia agresiva: Reemplazar cualquier duplicado
        $limpio = preg_replace('/(Cita expirada[^\w]*)+/', 'Cita expirada - No atendida', $original);
        $limpio = preg_replace('/(No atendida[^\w]*)+/', 'No atendida', $limpio);
        
        // Si hay múltiples líneas iguales, dejarlas como una sola
        $lineas = explode("\n", $limpio);
        $lineasUnicas = array_unique($lineas);
        $limpio = implode("\n", $lineasUnicas);
        
        if ($limpio !== $original) {
            $cita->observaciones = $limpio;
            $cita->save();
            $this->info("Limpia cita ID: {$cita->id}");
        }
    }

    $this->info('Proceso de limpieza completado.');
}
}