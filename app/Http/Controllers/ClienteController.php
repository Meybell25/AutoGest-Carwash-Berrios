<?php
// app/Http/Controllers/ClienteController.php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Vehiculo;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth; // ← Agregar esta línea
use Illuminate\Support\Facades\Log;

class ClienteController extends Controller
{
public function dashboard(): View
{
    $user = Auth::user();
    
    try {
        // Usa relaciones directamente (están bien definidas)
        $vehiculos = $user->vehiculos()->latest()->take(3)->get();
        $citas = $user->citas()->with(['vehiculo', 'servicios'])->latest()->take(3)->get();

        return view('cliente.dashboard', [
            'user' => $user,
            'stats' => [
                'total_vehiculos' => $vehiculos->count(),
                'total_citas' => $user->citas()->count(),
                'citas_pendientes' => $user->citas()->where('estado', 'pendiente')->count(),
                'citas_confirmadas' => $user->citas()->where('estado', 'confirmada')->count(),
            ],
            'mis_vehiculos' => $vehiculos,
            'mis_citas' => $citas
        ]);
    } catch (\Exception $e) {
        //Log del error
        Log::error('Error en dashboard: ' . $e->getMessage());
        
        // Retorna vista con datos vacíos
        return view('cliente.dashboard', [
            'user' => $user,
            'stats' => [
                'total_vehiculos' => 0,
                'total_citas' => 0,
                'citas_pendientes' => 0,
                'citas_confirmadas' => 0,
            ],
            'mis_vehiculos' => collect(),
            'mis_citas' => collect()
        ]);
    }
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