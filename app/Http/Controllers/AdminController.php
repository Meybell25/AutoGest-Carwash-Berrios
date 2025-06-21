<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Cita;
use App\Models\Vehiculo;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Events\UsuarioCreado;

class AdminController extends Controller
{
    /**
     * Muestra el dashboard del administrador
     */
    public function dashboard(): View
    {
        $stats = $this->getDashboardStats();
        
        $ultimas_citas = Cita::with(['usuario', 'vehiculo', 'servicios'])
            ->latest()
            ->take(5)
            ->get();

        $servicios_populares = Servicio::withCount('citas')
            ->orderBy('citas_count', 'desc')
            ->limit(3)
            ->get();

        $rolesDistribucion = $this->getRolesDistribution();

        $alertas = $this->getAlertas();

        return view('admin.dashboard', compact(
            'stats',
            'ultimas_citas',
            'servicios_populares',
            'alertas',
            'rolesDistribucion'
        ));
    }

    /**
     * Obtiene las estadísticas para el dashboard
     */
    protected function getDashboardStats(): array
    {
        $mesActual = now()->month;
        $anoActual = now()->year;

        return [
            'total_usuarios' => Usuario::count(),
            'total_clientes' => Usuario::where('rol', 'cliente')->count(),
            'total_empleados' => Usuario::where('rol', 'empleado')->count(),
            'total_citas' => Cita::count(),
            'citas_pendientes' => Cita::where('estado', 'pendiente')->count(),
            'total_vehiculos' => Vehiculo::count(),
            'total_servicios' => Servicio::where('activo', true)->count(),
            'usuarios_totales' => Usuario::count(),
            'nuevos_clientes_mes' => Usuario::where('rol', 'cliente')
                ->whereMonth('created_at', $mesActual)
                ->whereYear('created_at', $anoActual)
                ->count(),
            'citas_hoy' => Cita::whereDate('created_at', today())->count(),
            'ingresos_hoy' => Cita::whereDate('created_at', today())
                ->with('servicios')
                ->get()
                ->sum(fn($cita) => $cita->servicios->sum('precio')),
            'citas_canceladas_mes' => Cita::where('estado', 'cancelada')
                ->whereMonth('created_at', now()->month)
                ->count()
        ];
    }

    /**
     * Obtiene la distribución de roles de usuarios
     */
    protected function getRolesDistribution(): array
    {
        return [
            'clientes' => Usuario::where('rol', 'cliente')->count(),
            'empleados' => Usuario::where('rol', 'empleado')->count(),
            'administradores' => Usuario::where('rol', 'admin')->count()
        ];
    }

    /**
     * Obtiene las alertas del sistema
     */
    protected function getAlertas(): array
    {
        return [
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
    }

    /**
     * Muestra la lista de usuarios
     */
    public function usuarios(): View
    {
        $usuarios = Usuario::with(['vehiculos', 'citas'])
            ->paginate(10);

        return view('admin.usuarios', compact('usuarios'));
    }

    /**
     * Almacena un nuevo usuario
     */
    public function storeUsuario(Request $request)
    {
        $validated = $this->validateUsuarioRequest($request);

        try {
            $usuario = $this->createUsuario($validated);

            // Limpiar caché de estadísticas
            Cache::forget('dashboard_stats');

            event(new UsuarioCreado($usuario));

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

    /**
     * Valida los datos del formulario de usuario
     */
    protected function validateUsuarioRequest(Request $request): array
    {
        return $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'telefono' => 'nullable|string|max:20',
            'rol' => 'required|in:cliente,empleado,admin',
            'password' => 'required|string|min:8|confirmed',
            'estado' => 'required|boolean'
        ]);
    }

    /**
     * Crea un nuevo usuario
     */
    protected function createUsuario(array $data): Usuario
    {
        return Usuario::create([
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'telefono' => $data['telefono'],
            'rol' => $data['rol'],
            'password' => Hash::make($data['password']),
            'estado' => $data['estado']
        ]);
    }

    /**
     * Obtiene los datos para actualizar el dashboard via AJAX
     */
    public function getDashboardData()
    {
        $data = Cache::remember('dashboard_stats', now()->addMinutes(5), function () {
            return [
                'stats' => $this->getDashboardStats(),
                'rolesDistribucion' => $this->getRolesDistribution()
            ];
        });

        return response()->json($data);
    }

    /**
     * Muestra el formulario para crear una cita
     */
    public function createCita(): View
    {
        return view('admin.citas.create');
    }

    /**
     * Almacena una nueva cita
     */
    public function storeCita(Request $request)
    {
        // TODO: Implementar lógica real de creación de cita
        return redirect()->route('admin.dashboard')
            ->with('success', 'Cita creada temporalmente. Implementa la lógica real.');
    }

    /**
     * Muestra la página de reportes
     */
    public function reportes(): View
    {
        return view('admin.reportes.index');
    }
}