<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pago;
use App\Models\Cita;
use Illuminate\Support\Str;

class PagoSeeder extends Seeder
{
    public function run(): void
    {
        $citas = Cita::all();

        foreach ($citas as $cita) {
            // Randomizamos el método de pago
            $metodo = collect(['efectivo', 'transferencia', 'pasarela'])->random();
            $monto = rand(10, 100); // monto aleatorio de prueba

            $pago = new Pago();
            $pago->cita_id = $cita->id;
            $pago->monto = $monto;
            $pago->metodo = $metodo;
            
            if ($metodo === 'efectivo') {
                $pago->monto_recibido = $monto + rand(1, 20);
                $pago->vuelto = $pago->monto_recibido - $monto;
            } elseif (in_array($metodo, ['transferencia', 'pasarela'])) {
                $pago->referencia = Str::random(10);
            }

            // Asignar estado aleatorio
            $estado = collect(['pendiente', 'completado', 'rechazado'])->random();
            $pago->estado = $estado;

            $pago->fecha_pago = now()->subDays(rand(0, 30));

            $pago->save();
        }
    }
}
