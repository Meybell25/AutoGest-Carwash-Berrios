<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Cita;
use App\Models\Vehiculo;
use App\Models\Servicio;
use App\Models\Notificacion;
use App\Models\Pago;
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
        $hoy = now()->format('Y-m-d');

        return [
            'usuarios_totales' => Usuario::count(),
            'citas_confirmadas_hoy' => Cita::whereDate('fecha_hora', $hoy)
                ->where('estado', 'confirmada')
                ->count(),
            'ingresos_hoy' => Cita::whereDate('created_at', today())
                ->with('servicios')
                ->get()
                ->sum(fn($cita) => $cita->servicios->sum('precio')),
            'citas_pendientes' => Cita::where('estado', 'pendiente')->count(),
            'ingresos_mensuales' => Cita::whereMonth('created_at', $mesActual)
                ->whereYear('created_at', $anoActual)
                ->with('servicios')
                ->get()
                ->sum(fn($cita) => $cita->servicios->sum('precio')),
            'nuevos_clientes_mes' => Usuario::where('rol', 'cliente')
                ->whereMonth('created_at', $mesActual)
                ->whereYear('created_at', $anoActual)
                ->count(),
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

            // Registrar actualización de usuario
            \App\Models\Bitacora::registrar(\App\Models\Bitacora::ACCION_ACTUALIZAR_USUARIO, auth()->id(), request()->ip());

            // Registrar creación de usuario (panel admin)
            \App\Models\Bitacora::registrar(\App\Models\Bitacora::ACCION_CREAR_USUARIO, auth()->id(), $request->ip());

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

        // Registrar en bitácora
        \App\Models\Bitacora::registrar(\App\Models\Bitacora::ACCION_ELIMINAR_USUARIO, auth()->id(), request()->ip());

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

        // Excluir el email del usuario actual si estamos editando
        $excludeId = $request->input('exclude_id');

        $query = Usuario::where('email', $request->email);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $exists = $query->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Este correo electrónico ya está registrado' : 'Email disponible'
        ]);
    }
    /**
     * Muestra la administración de citas con filtros
     */
    public function citasAdmin(Request $request)
    {
        $query = Cita::with(['usuario', 'vehiculo', 'servicios', 'pago'])
            ->orderBy('fecha_hora', 'desc');

        // Query para estadísticas (sin paginación)
        $statsQuery = Cita::with(['usuario', 'vehiculo', 'servicios']);

        // Aplicar filtros a ambos queries
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
            $statsQuery->where('estado', $request->estado);
        }

        if ($request->filled('fecha')) {
            $query->whereDate('fecha_hora', $request->fecha);
            $statsQuery->whereDate('fecha_hora', $request->fecha);
        }

        if ($request->filled('buscar')) {
            $searchTerm = $request->buscar;

            $searchFilter = function ($q) use ($searchTerm) {
                $q->whereHas('usuario', function ($q) use ($searchTerm) {
                    $q->where('nombre', 'like', "%{$searchTerm}%")
                        ->orWhere('email', 'like', "%{$searchTerm}%");
                })->orWhereHas('vehiculo', function ($q) use ($searchTerm) {
                    $q->where('placa', 'like', "%{$searchTerm}%")
                        ->orWhere('marca', 'like', "%{$searchTerm}%")
                        ->orWhere('modelo', 'like', "%{$searchTerm}%");
                });
            };

            $query->where($searchFilter);
            $statsQuery->where($searchFilter);
        }

        // Obtener estadísticas
        $estadisticas = $this->getCitasEstadisticas($statsQuery, $request);

        $citas = $query->paginate(15)->withQueryString();

        return view('admin.citasadmin', compact('citas', 'estadisticas'));
    }

    /**
     * Calcula las estadísticas de las citas filtradas
     */
    protected function getCitasEstadisticas($query, Request $request): array
    {
        // Clonar query para cada estadística
        $totalCitas = $query->count();

        // Estadísticas básicas por estado
        $estadisticasPorEstado = [
            'pendiente' => (clone $query)->where('estado', 'pendiente')->count(),
            'confirmada' => (clone $query)->where('estado', 'confirmada')->count(),
            'en_proceso' => (clone $query)->where('estado', 'en_proceso')->count(),
            'finalizada' => (clone $query)->where('estado', 'finalizada')->count(),
            'cancelada' => (clone $query)->where('estado', 'cancelada')->count(),
        ];

        // Estadísticas de pagos
        $citasConPago = (clone $query)->whereHas('pago', function ($q) {
            $q->where('estado', Pago::ESTADO_PAGADO);
        })->count();

        $citasSinPago = (clone $query)->whereDoesntHave('pago')->count();

        $citasConPagoPendiente = (clone $query)->whereHas('pago', function ($q) {
            $q->where('estado', Pago::ESTADO_PENDIENTE);
        })->count();

        // Ingresos generados (solo citas pagadas)
        $ingresosGenerados = (clone $query)->whereHas('pago', function ($q) {
            $q->where('estado', Pago::ESTADO_PAGADO);
        })->with(['servicios', 'pago'])->get()->sum('total');

        $estadisticas = [
            'total' => $totalCitas,
            'por_estado' => $estadisticasPorEstado,
            'pagos' => [
                'con_pago_completado' => $citasConPago,
                'sin_pago' => $citasSinPago,
                'con_pago_pendiente' => $citasConPagoPendiente,
                'ingresos_generados' => $ingresosGenerados
            ],
            'filtros_activos' => []
        ];

        // Información sobre filtros activos
        if ($request->filled('buscar')) {
            $estadisticas['filtros_activos']['busqueda'] = $request->buscar;

            // Buscar usuario específico
            $usuarioEncontrado = Usuario::where('nombre', 'like', "%{$request->buscar}%")
                ->orWhere('email', 'like', "%{$request->buscar}%")
                ->first();

            if ($usuarioEncontrado) {
                $estadisticas['filtros_activos']['usuario_nombre'] = $usuarioEncontrado->nombre;
            }
        }

        if ($request->filled('estado')) {
            $estadisticas['filtros_activos']['estado'] = $request->estado;
        }

        if ($request->filled('fecha')) {
            $estadisticas['filtros_activos']['fecha'] = $request->fecha;
        }

        return $estadisticas;
    }

    /**
     * Obtiene los detalles completos de una cita
     */
    public function getCitaDetalles($id)
    {
        try {
            $cita = Cita::with(['usuario', 'vehiculo', 'servicios'])
                ->findOrFail($id);

            return response()->json([
                'id' => $cita->id,
                'usuario' => [
                    'nombre' => $cita->usuario->nombre,
                    'email' => $cita->usuario->email,
                    'telefono' => $cita->usuario->telefono
                ],
                'vehiculo' => [
                    'marca' => $cita->vehiculo->marca,
                    'modelo' => $cita->vehiculo->modelo,
                    'placa' => $cita->vehiculo->placa,
                    'tipo' => $cita->vehiculo->tipo,
                    'tipo_formatted' => $cita->vehiculo->tipo_formatted, // Añade esto
                    'color' => $cita->vehiculo->color,
                    'descripcion' => $cita->vehiculo->descripcion
                ],
                'fecha_hora' => $cita->fecha_hora,
                'estado' => $cita->estado,
                'estado_formatted' => $cita->estado_formatted,
                'observaciones' => $cita->observaciones,
                'total' => $cita->total,
                'servicios' => $cita->servicios,
                'created_at' => $cita->created_at
            ]);
        } catch (\Exception $e) {
            Log::error("Error al obtener detalles de cita: " . $e->getMessage());
            return response()->json([
                'error' => 'No se pudieron cargar los detalles de la cita'
            ], 500);
        }
    }

    /**
     * Actualiza el estado de una cita con validaciones
     */
    public function actualizarEstadoCita(Request $request, $id)
    {
        try {
            $request->validate([
                'estado' => 'required|in:pendiente,confirmada,en_proceso,finalizada,cancelada'
            ]);

            $cita = Cita::with(['usuario', 'vehiculo', 'pago', 'servicios'])->findOrFail($id);
            $estadoAnterior = $cita->estado;
            $nuevoEstado = $request->estado;

            // VALIDACIONES MEJORADAS

            // 1. No permitir finalizar sin pago completado
            if ($nuevoEstado === 'finalizada') {
                if (!$cita->pago || $cita->pago->estado !== Pago::ESTADO_PAGADO) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se puede finalizar una cita sin pago registrado y completado. Por favor, procese el pago primero.',
                        'requiere_pago' => true
                    ], 422);
                }
            }

            // 2. No cancelar citas finalizadas con pago
            if ($nuevoEstado === 'cancelada' && $estadoAnterior === 'finalizada') {
                if ($cita->pago && $cita->pago->isPagado()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se puede cancelar una cita finalizada que ya tiene pago. Use la función de reembolso si es necesario.'
                    ], 422);
                }
            }

            // 3. Validar transiciones lógicas
            $transicionesInvalidas = [
                'finalizada' => ['pendiente', 'confirmada'], // No retroceder desde finalizada
                'cancelada' => [] // Cancelada puede venir de cualquier estado (con validaciones especiales)
            ];

            if (
                isset($transicionesInvalidas[$estadoAnterior]) &&
                in_array($nuevoEstado, $transicionesInvalidas[$estadoAnterior])
            ) {
                return response()->json([
                    'success' => false,
                    'message' => "No se puede cambiar el estado de '{$cita->estado_formatted}' a '{$cita->getEstados()[$nuevoEstado]}'"
                ], 422);
            }

            DB::beginTransaction();

            // Actualizar estado
            $estadoAnteriorFormatted = $cita->estado_formatted;
            $cita->estado = $nuevoEstado;

            // Agregar observación sobre el cambio
            $observacionCambio = "Estado cambiado de '{$estadoAnteriorFormatted}' a '{$cita->estado_formatted}' por " . auth()->user()->nombre . " el " . now()->format('d/m/Y H:i');

            if ($cita->observaciones) {
                $cita->observaciones .= "\n" . $observacionCambio;
            } else {
                $cita->observaciones = $observacionCambio;
            }

            $cita->save();

            // Registrar en bitácora detallada
            Log::channel('admin_actions')->info("Estado de cita actualizado", [
                'admin_id' => auth()->id(),
                'admin_nombre' => auth()->user()->nombre,
                'cita_id' => $cita->id,
                'cliente' => $cita->usuario->nombre,
                'vehiculo' => $cita->vehiculo->marca . ' ' . $cita->vehiculo->modelo . ' (' . $cita->vehiculo->placa . ')',
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $cita->estado,
                'tiene_pago' => $cita->pago ? true : false,
                'pago_completado' => $cita->pago && $cita->pago->isPagado() ? true : false,
                'total_cita' => $cita->total,
                'fecha_cita' => $cita->fecha_hora,
                'fecha_cambio' => now(),
                'ip' => request()->ip()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Estado actualizado correctamente a '{$cita->estado_formatted}'",
                'nuevo_estado' => $cita->estado_formatted,
                'estado_codigo' => $cita->estado,
                'tiene_pago_completado' => $cita->pago && $cita->pago->isPagado(),
                'puede_finalizar' => $cita->pago && $cita->pago->isPagado()
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
            Log::error("Error al actualizar estado de cita ID {$id}: " . $e->getMessage(), [
                'admin_id' => auth()->id(),
                'request_data' => $request->all(),
                'exception' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno al actualizar el estado de la cita'
            ], 500);
        }
    }

    /**
     * Verificar si una cita tiene pago completado
     */
    public function verificarPagoCita($citaId)
    {
        try {
            $cita = Cita::with('pago')->findOrFail($citaId);

            $tienePagoCompletado = $cita->pago && $cita->pago->estado === Pago::ESTADO_PAGADO;

            return response()->json([
                'success' => true,
                'tiene_pago_completado' => $tienePagoCompletado,
                'pago' => $cita->pago ? [
                    'id' => $cita->pago->id,
                    'monto' => $cita->pago->monto,
                    'metodo' => $cita->pago->metodo,
                    'estado' => $cita->pago->estado,
                    'fecha_pago' => $cita->pago->fecha_pago
                ] : null,
                'puede_finalizar' => $tienePagoCompletado,
                'estado_cita' => $cita->estado
            ]);
        } catch (\Exception $e) {
            Log::error("Error al verificar pago de cita {$citaId}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al verificar pago',
                'tiene_pago_completado' => false
            ], 500);
        }
    }

    /**
     * Almacena un nuevo servicio
     */
    public function storeServicio(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'required|string|max:1000',
                'precio' => 'required|numeric|min:0',
                'duracion_min' => 'required|integer|min:1|max:480',
                'activo' => 'required|boolean',
                'categoria' => 'required|string|in:sedan,pickup,moto'
            ]);

            DB::beginTransaction();

            $servicio = Servicio::create($validated);

            // Limpiar caché de servicios si existe
            Cache::forget('servicios_populares');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Servicio creado correctamente',
                'servicio' => $servicio
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al crear servicio: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al crear el servicio: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene los datos de un servicio específico
     */
    public function showServicio($id)
    {
        try {
            $servicio = Servicio::findOrFail($id);

            return response()->json([
                'success' => true,
                'servicio' => $servicio
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Servicio no encontrado'
            ], 404);
        }
    }

    /**
     * Actualiza un servicio existente
     */
    public function updateServicio(Request $request, $id)
    {
        try {
            $servicio = Servicio::findOrFail($id);

            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'required|string|max:1000',
                'precio' => 'required|numeric|min:0',
                'duracion_min' => 'required|integer|min:1|max:480',
                'activo' => 'required|boolean',
                'categoria' => 'required|string|in:sedan,pickup,moto'
            ]);

            DB::beginTransaction();

            $servicio->update($validated);

            // Limpiar caché de servicios
            Cache::forget('servicios_populares');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Servicio actualizado correctamente',
                'servicio' => $servicio
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al actualizar servicio: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el servicio'
            ], 500);
        }
    }

    /**
     * Elimina un servicio
     */
    public function deleteServicio($id)
    {
        try {
            $servicio = Servicio::findOrFail($id);

            // Verificar que no tenga citas asociadas
            $citasCount = $servicio->citas()->count();
            
            if ($citasCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "No se puede eliminar el servicio porque tiene {$citasCount} citas asociadas"
                ], 422);
            }

            DB::beginTransaction();

            $servicio->delete();

            // Limpiar caché de servicios
            Cache::forget('servicios_populares');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Servicio eliminado correctamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al eliminar servicio: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el servicio'
            ], 500);
        }
    }

    /**
     * Cambia el estado activo/inactivo de un servicio
     */
    public function toggleServicioStatus($id)
    {
        try {
            $servicio = Servicio::findOrFail($id);

            DB::beginTransaction();

            $servicio->activo = !$servicio->activo;
            $servicio->save();

            // Limpiar caché de servicios
            Cache::forget('servicios_populares');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Estado del servicio actualizado correctamente',
                'nuevo_estado' => $servicio->activo
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al cambiar estado del servicio: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado del servicio'
            ], 500);
        }
    }

    /**
     * Obtiene todos los servicios para el modal de gestión
     */
    public function getAllServicios()
    {
        try {
            $servicios = Servicio::withCount('citas')
                ->orderBy('nombre')
                ->get()
                ->map(function ($servicio) {
                    return [
                        'id' => $servicio->id,
                        'nombre' => $servicio->nombre,
                        'descripcion' => $servicio->descripcion,
                        'precio' => $servicio->precio,
                        'duracion_min' => $servicio->duracion_min,
                        'categoria' => $servicio->categoria,
                        'activo' => $servicio->activo,
                        'citas_count' => $servicio->citas_count,
                        'categoria_formatted' => $this->formatCategoria($servicio->categoria),
                        'estado_formatted' => $servicio->activo ? 'Activo' : 'Inactivo'
                    ];
                });

            $estadisticas = [
                'total' => $servicios->count(),
                'activos' => $servicios->where('activo', true)->count(),
                'inactivos' => $servicios->where('activo', false)->count(),
                'por_categoria' => [
                    'sedan' => $servicios->where('categoria', 'sedan')->count(),
                    'pickup' => $servicios->where('categoria', 'pickup')->count(),
                    'moto' => $servicios->where('categoria', 'moto')->count(),
                ]
            ];

            return response()->json([
                'success' => true,
                'servicios' => $servicios,
                'estadisticas' => $estadisticas
            ]);

        } catch (\Exception $e) {
            Log::error("Error al obtener servicios: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los servicios'
            ], 500);
        }
    }

    /**
     * Formatea el nombre de la categoría para mostrar
     */
    private function formatCategoria($categoria)
    {
        switch ($categoria) {
            case 'sedan':
                return '🚗 Sedán';
            case 'pickup':
                return '🚙 Pickup/SUV';
            case 'moto':
                return '🏍️ Motocicleta';
            default:
                return $categoria ? ucfirst($categoria) : 'Sin categoría';
        }
    }
}
