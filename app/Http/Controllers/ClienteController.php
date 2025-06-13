<?php
// app/Http/Controllers/ClienteController.php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Vehiculo;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth; // ← Agregar esta línea

class ClienteController extends Controller
{
    public function dashboard(): View
    {
        $user = Auth::user(); // ← Cambiar auth()->user() por Auth::user()
        
        $mis_vehiculos = Vehiculo::where('usuario_id', $user->id)->get();
        
        $mis_citas = Cita::where('usuario_id', $user->id)
            ->with(['vehiculo', 'servicios'])
            ->orderBy('fecha_hora', 'desc')
            ->limit(5)
            ->get();

        $stats = [
            'total_vehiculos' => $mis_vehiculos->count(),
            'total_citas' => Cita::where('usuario_id', $user->id)->count(),
            'citas_pendientes' => Cita::where('usuario_id', $user->id)
                ->where('estado', 'pendiente')->count(),
            'citas_confirmadas' => Cita::where('usuario_id', $user->id)
                ->where('estado', 'confirmada')->count(),
        ];

        return view('cliente.dashboard', compact('mis_vehiculos', 'mis_citas', 'stats'));
    }

    public function vehiculos(): View
    {
        $vehiculos = Vehiculo::where('usuario_id', Auth::id()) // ← Cambiar auth()->id() por Auth::id()
            ->with('citas')
            ->paginate(10);

        return view('cliente.vehiculos', compact('vehiculos'));
    }

    public function citas(): View
    {
        $citas = Cita::where('usuario_id', Auth::id()) // ← Cambiar auth()->id() por Auth::id()
            ->with(['vehiculo', 'servicios'])
            ->orderBy('fecha_hora', 'desc')
            ->paginate(10);

        return view('cliente.citas', compact('citas'));
    }
}