<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Cita;
use App\Models\Vehiculo;
use App\Models\Servicio;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $usuarios = Usuario::with(['vehiculos', 'citas'])->get();
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

    /**
     * Actualiza el perfil del administrador
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed'
        ]);

        try {
            $user->nombre = $validated['nombre'];
            $user->telefono = $validated['telefono'];

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Perfil actualizado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el perfil: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Almacena un nuevo usuario desde el panel de administración
     */
    public function storeUser(Request $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:usuarios',
                'telefono' => 'nullable|string|max:20',
                'rol' => 'required|in:cliente,empleado,admin',
                'password' => 'required|string|min:8|confirmed',
                'estado' => 'required|boolean'
            ]);

            $user = Usuario::create([
                'nombre' => $validated['nombre'],
                'email' => $validated['email'],
                'telefono' => $validated['telefono'],
                'rol' => $validated['rol'],
                'password' => Hash::make($validated['password']),
                'estado' => $validated['estado']
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Usuario creado correctamente',
                'user' => $user
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    // Obtener todos los usuarios para filtrado
    public function getAllUsers(Request $request)
    {
        $withRelations = $request->has('export'); // Si viene el parámetro 'export', cargar relaciones

        $query = Usuario::query();

        if ($withRelations) {
            $query->with(['vehiculos', 'citas']);
        }

        // Solo cargar relaciones si es para exportación
        if ($request->has('export')) {
            return $query->get()
                ->makeHidden(['password', 'remember_token']);
        }

        // Para la tabla, no cargar relaciones para mejor performance
        return $query->get()
            ->makeHidden(['password', 'remember_token']);
    }

    // Acciones masivas
    public function bulkActivate(Request $request)
    {
        $ids = $request->input('ids');
        Usuario::whereIn('id', $ids)->update(['estado' => true]);
        return response()->json(['success' => true]);
    }

    public function bulkDeactivate(Request $request)
    {
        $ids = $request->input('ids');

        // Verificar que ningún usuario tenga citas pendientes
        $usuariosConCitas = Usuario::whereIn('id', $ids)
            ->whereHas('citas', function ($q) {
                $q->whereIn('estado', ['pendiente', 'en_proceso']);
            })->count();

        if ($usuariosConCitas > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede desactivar usuarios con citas pendientes o en proceso'
            ], 403);
        }

        Usuario::whereIn('id', $ids)->update(['estado' => false]);
        return response()->json(['success' => true]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        Usuario::whereIn('id', $ids)->where('rol', '!=', 'admin')->delete();
        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        // Validación condicional
        $rules = [
            'telefono' => 'nullable|string|max:20',
        ];

        if ($request->has('nombre')) {
            $rules['nombre'] = 'required|string|max:255';
        }

        if ($request->has('estado')) {
            $rules['estado'] = 'required|boolean';
        }

        $validated = $request->validate($rules, [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres',
            'telefono.max' => 'El teléfono no puede exceder 20 caracteres',
            'estado.required' => 'El estado es obligatorio',
            'estado.boolean' => 'El estado debe ser verdadero o falso',
        ]);

        // Guardar estado anterior para comparación
        $estadoAnterior = $usuario->estado;

        DB::beginTransaction();

        try {
            // Actualizar solo los campos validados
            $usuario->fill($validated)->save();

            // Auditoría
            Log::channel('admin_actions')->info("Usuario actualizado", [
                'admin_id' => auth()->id(),
                'user_id' => $usuario->id,
                'changes' => $validated,
                'ip' => request()->ip(),
                'fecha' => now()
            ]);

            // Notificación solo si cambió el estado
            if (array_key_exists('estado', $validated) && $estadoAnterior != $usuario->estado) {
                Notificacion::create([
                    'usuario_id' => $usuario->id,
                    'mensaje' => 'Tu estado de cuenta ha sido actualizado a: ' . ($usuario->estado ? 'ACTIVO' : 'INACTIVO'),
                    'canal' => Notificacion::CANAL_SISTEMA,
                    'leido' => false,
                    'fecha_envio' => now()
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado correctamente',
                'data' => $usuario->fresh()
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Error al actualizar usuario ID {$id}: " . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el usuario',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);

        // Validación 1: No permitir eliminar admins
        if ($usuario->rol === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'No se pueden eliminar cuentas de administrador'
            ], 403);
        }

        // Validación 2: Usuario debe estar inactivo
        if ($usuario->estado) {
            return response()->json([
                'success' => false,
                'message' => 'Solo se pueden eliminar usuarios inactivos'
            ], 403);
        }

        // Validación 3: No tener citas pendientes
        if ($usuario->citas()->whereIn('estado', ['pendiente', 'en_proceso'])->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el usuario porque tiene citas pendientes o en proceso'
            ], 403);
        }

        // Validación 4: Inactivo por al menos 3 meses
        $fechaLimite = now()->subMonths(3);
        if ($usuario->updated_at > $fechaLimite) {
            return response()->json([
                'success' => false,
                'message' => 'El usuario debe estar inactivo por al menos 3 meses para ser eliminado'
            ], 403);
        }

        // Eliminar
        $usuario->delete();

        // Registrar en logs
        Log::channel('admin_actions')->info("Usuario eliminado", [
            'admin_id' => auth()->id(),
            'user_id' => $usuario->id,
            'ip' => request()->ip()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado correctamente'
        ]);
    }

    /**
     * Obtiene los registros (vehículos y citas) de un usuario
     */
    public function getUserRecords($usuarioId)
    {
        $usuario = Usuario::with([
            'vehiculos',
            'citas' => function ($query) {
                $query->orderBy('fecha_hora', 'desc')
                    ->with(['servicios']);
            }
        ])->findOrFail($usuarioId);

        return response()->json([
            'vehiculos' => $usuario->vehiculos,
            'citas' => $usuario->citas
        ]);
    }
    public function checkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $exists = Usuario::where('email', $request->email)->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Email ya registrado' : 'Email disponible'
        ]);
    }
}
