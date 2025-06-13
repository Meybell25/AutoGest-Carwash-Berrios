<?php
// app/Http/Controllers/EmpleadoController.php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmpleadoController extends Controller
{
    public function dashboard(): View
    {
        $citas_hoy = Cita::whereDate('fecha_hora', today())
            ->with(['usuario', 'vehiculo', 'servicios'])
            ->orderBy('fecha_hora')
            ->get();

        $stats = [
            'citas_hoy' => $citas_hoy->count(),
            'citas_pendientes' => Cita::where('estado', 'pendiente')->count(),
            'citas_confirmadas' => Cita::where('estado', 'confirmada')->count(),
            'citas_en_proceso' => Cita::where('estado', 'en_proceso')->count(),
        ];

        return view('empleado.dashboard', compact('citas_hoy', 'stats'));
    }

    public function citas(): View
    {
        $citas = Cita::with(['usuario', 'vehiculo', 'servicios'])
            ->orderBy('fecha_hora', 'desc')
            ->paginate(15);

        return view('empleado.citas', compact('citas'));
    }
}
