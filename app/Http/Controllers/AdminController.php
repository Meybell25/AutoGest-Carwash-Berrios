<?php
// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Cita;
use App\Models\Vehiculo;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

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
            ->latest()
            ->take(5)
            ->get();

        $citas_recientes = Cita::with(['usuario', 'vehiculo'])
            ->orderBy('created_at', 'desc')
            ->orderBy('fecha_hora', 'desc')
            ->limit(5)
            ->get();

        $servicios_populares = Servicio::withCount('citas')
            ->orderBy('citas_count', 'desc')
            ->limit(3)
            ->get();

        $alertas = [
            (object)[
                'leida' => false,
                'tipo' => 'info',
                'icono' => 'exclamation-circle',
                'titulo' => 'Bienvenido al sistema',
                'mensaje' => 'Has iniciado sesión como administrador',
                'created_at' => now()
            ],
            (object)[
                'leida' => true,
                'tipo' => 'warning',
                'icono' => 'calendar-check',
                'titulo' => 'Cita próxima',
                'mensaje' => 'Tienes una cita programada para mañana',
                'created_at' => now()->subHours(2)
            ]
        ];


        return view('admin.dashboard', compact(
            'stats',
            'ultimas_citas',
            'servicios_populares',
            'alertas'
        ));
    }

    public function usuarios(): View
    {
        $usuarios = Usuario::with(['vehiculos', 'citas'])
            ->paginate(10);

        return view('admin.usuarios', compact('usuarios'));
    }

    public function storeUsuario(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'telefono' => 'nullable|string|max:20',
            'rol' => 'required|in:cliente,empleado,admin',
            'password' => 'required|string|min:8|confirmed',
            'estado' => 'required|boolean'
        ]);

        try {
            $usuario = Usuario::create([
                'nombre' => $validated['nombre'],
                'email' => $validated['email'],
                'telefono' => $validated['telefono'],
                'rol' => $validated['rol'],
                'password' => Hash::make($validated['password']),
                'estado' => $validated['estado']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Usuario creado correctamente',
                'usuario' => $usuario
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createCita(): View
    {
        return view('admin.citas.create');
    }

    public function storeCita(Request $request)
    {
        return redirect()->route('admin.dashboard')
            ->with('success', 'Cita creada temporalmente. Implementa la lógica real.');
    }

    public function reportes(): View
    {
        return view('admin.reportes.index');
    }
}
