<?php
// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Cita;
use App\Models\Vehiculo;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'total_usuarios' => Usuario::count(),
            'total_clientes' => Usuario::where('rol', 'cliente')->count(),
            'total_empleados' => Usuario::where('rol', 'empleado')->count(),
            'total_citas' => Cita::count(),
            'citas_pendientes' => Cita::where('estado', 'pendiente')->count(),
            'total_vehiculos' => Vehiculo::count(),
            'total_servicios' => Servicio::where('activo', true)->count(),
        ];

        $citas_recientes = Cita::with(['usuario', 'vehiculo'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'citas_recientes'));
    }

    public function usuarios(): View
    {
        $usuarios = Usuario::with(['vehiculos', 'citas'])
            ->paginate(10);

        return view('admin.usuarios', compact('usuarios'));
    }
}
