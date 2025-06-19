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
            // Estas son redundantes, puedes eliminarlas si no las usas
            'usuarios_totales' => Usuario::count(),
            'citas_hoy' => Cita::whereDate('created_at', today())->count(),
            'ingresos_hoy' => Cita::whereDate('created_at', today())
                ->with('servicios')
                ->get()
                ->sum(function ($cita) {
                    return $cita->servicios->sum('precio');
                }),
            'nuevos_clientes_mes' => Usuario::where('rol', 'cliente')
                ->whereMonth('created_at', now()->month)
                ->count(),
            'citas_canceladas_mes' => Cita::where('estado', 'cancelada')
                ->whereMonth('created_at', now()->month)
                ->count()
        ];

        $ultimas_citas = Cita::with(['usuario', 'vehiculo', 'servicios'])
            ->orderBy('fecha_hora', 'desc')  // Cambié created_at por fecha_hora que es más lógico
            ->limit(5)
            ->get();

        $servicios_populares = Servicio::withCount('citas')
            ->orderBy('citas_count', 'desc')
            ->limit(3)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'ultimas_citas',
            'servicios_populares'
        )); // Eliminé $alertas que no estás usando
    }

    public function usuarios(): View
    {
        $usuarios = Usuario::with(['vehiculos', 'citas'])
            ->paginate(10);

        return view('admin.usuarios', compact('usuarios'));
    }
}
