<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmpleadoController extends Controller
{
    /**
     * Dashboard del empleado
     */
    public function dashboard(): View
    {
        $citas_hoy = Cita::hoy()
            ->byEstado(Cita::ESTADO_PENDIENTE)
            ->with(['usuario', 'vehiculo', 'servicios'])
            ->orderBy('fecha_hora')
            ->get();

        $stats = [
            'citas_hoy'        => Cita::hoy()->byEstado(Cita::ESTADO_PENDIENTE)->count(),
            'citas_proceso'    => Cita::hoy()->byEstado(Cita::ESTADO_EN_PROCESO)->count(),
            'citas_finalizadas'=> Cita::hoy()->byEstado(Cita::ESTADO_FINALIZADA)->count(),
        ];

        $historial = Cita::byEstado(Cita::ESTADO_FINALIZADA)
            ->orderByDesc('updated_at')
            ->take(5)
            ->with(['usuario', 'vehiculo', 'servicios'])
            ->get();

        return view('empleado.dashboard', compact('citas_hoy', 'stats', 'historial'));
    }

    public function citas(Request $request): View
    {
        $filtro = $request->get('filtro', 'hoy');
        $fecha  = today();

        switch ($filtro) {
            case 'manana':
                $fecha = today()->addDay();
                break;

            case 'pasado':
                $fecha = today()->addDays(2);
                break;

            case 'fecha':
                if ($request->filled('fecha')) {
                    // Solo acepta fechas >= hoy
                    $candidata = Carbon::parse($request->fecha);
                    if ($candidata->gte(today())) {
                        $fecha = $candidata;
                    }
                }
                break;

            default:
                // 'hoy' u otro valor desconocido cae aquí
                $fecha = today();
        }

        // Estadísticas corregidas
        $stats = [
            'citas_hoy' => $citas_hoy->count(),
            'citas_pendientes' => Cita::where('estado', 'pendiente')->count(),
            'citas_confirmadas' => Cita::where('estado', 'confirmada')->count(),
            'citas_proceso' => Cita::where('estado', 'en_proceso')->count(), // Corregido
            'citas_finalizadas' => Cita::where('estado', 'finalizada') // Agregado
                ->whereDate('fecha_hora', today())
                ->count(),
        ];

        // Obtener últimos 3 servicios finalizados
        $historial_reciente = Cita::with(['usuario', 'vehiculo', 'servicios', 'pago'])
            ->where('estado', 'finalizada')
            ->orderBy('fecha_hora', 'desc')
            ->take(3)
            ->get();

        return view('empleado.dashboard', compact('citas_hoy', 'stats', 'historial_reciente'));
    }

    /**
     * Vista de agenda de citas con filtros
     * Retorna JSON si es petición AJAX, sino retorna vista
     */
    public function citas(Request $request)
    {
        $query = Cita::with(['usuario', 'vehiculo', 'servicios'])
            ->whereDate('fecha_hora', '>=', today()) // Solo citas de hoy en adelante
            ->orderBy('fecha_hora', 'asc'); // Orden ascendente para mostrar las más próximas primero

        // Aplicar filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_hora', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_hora', '<=', $request->fecha_hasta);
        }

        $citas = $query->paginate(15)->withQueryString();

        // Si es petición AJAX, retornar JSON
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($citas);
        }

        // Si no, retornar vista (para mantener compatibilidad)
        return view('empleado.citas', compact('citas'));
        $citas = Cita::byEstado(Cita::ESTADO_PENDIENTE)
            ->byFecha($fecha)
            ->with(['usuario', 'vehiculo', 'servicios'])
            ->orderBy('fecha_hora')
            ->get();

        return view('empleado.citas', [
            'citas'  => $citas,
            'fecha'  => $fecha,
            'filtro' => $filtro,
        ]);
    }

    public function cambiarEstado(Request $request, Cita $cita)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,en_proceso,finalizada',
        ]);

        $cita->update([
            'estado' => $request->string('estado'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado correctamente',
        ]);
    }

    public function finalizarCita(Request $request)
    {
        $request->validate([
            'cita_id' => 'required|exists:citas,id',
        ]);

        $cita = Cita::findOrFail($request->input('cita_id'));
        $cita->estado = Cita::ESTADO_FINALIZADA;
        $cita->save();

        return response()->json([
            'success' => true,
            'message' => 'Cita finalizada correctamente',
        ]);
    }

    /**
     * Cambiar estado de una cita
     */
    public function cambiarEstado(Request $request, $id)
    {
        try {
            $request->validate([
                'estado' => 'required|in:confirmada,en_proceso,finalizada'
            ]);

            $cita = Cita::with(['usuario', 'vehiculo', 'servicios'])->findOrFail($id);
            $estadoAnterior = $cita->estado;
            $nuevoEstado = $request->estado;

            // Validar transiciones permitidas para empleados
            $transicionesPermitidas = [
                'confirmada' => ['en_proceso'], // Confirmada solo puede ir a En Proceso
                'en_proceso' => ['finalizada'],  // En Proceso solo puede ir a Finalizada
            ];

            // Verificar que la transición sea válida
            if (isset($transicionesPermitidas[$estadoAnterior])) {
                if (!in_array($nuevoEstado, $transicionesPermitidas[$estadoAnterior])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se puede cambiar de "' . ucfirst($estadoAnterior) . '" a "' . ucfirst(str_replace('_', ' ', $nuevoEstado)) . '"'
                    ], 422);
                }
            }

            DB::beginTransaction();

            // Actualizar estado
            $cita->estado = $nuevoEstado;

            // Agregar observación del cambio
            $observacionCambio = "\n[" . now()->format('d/m/Y H:i') . "] Estado cambiado a '" . ucfirst(str_replace('_', ' ', $nuevoEstado)) . "' por " . Auth::user()->nombre;
            $cita->observaciones = ($cita->observaciones ?? '') . $observacionCambio;

            $cita->save();

            // Registrar en bitácora
            \App\Models\Bitacora::create([
                'usuario_id' => Auth::id(),
                'accion' => 'cambiar_estado_cita',
                'tabla_afectada' => 'citas',
                'registro_id' => $cita->id,
                'detalles' => "Cambió estado de cita de '{$estadoAnterior}' a '{$nuevoEstado}'"
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado correctamente a "' . ucfirst(str_replace('_', ' ', $nuevoEstado)) . '"',
                'nuevo_estado' => $nuevoEstado
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
            Log::error("Error al cambiar estado de cita ID {$id}: " . $e->getMessage(), [
                'empleado_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado de la cita'
            ], 500);
        }
    }

    /**
     * Agregar observaciones a una cita
     */
    public function agregarObservaciones(Request $request, $id)
    {
        try {
            $request->validate([
                'observaciones' => 'required|string|max:1000'
            ]);

            $cita = Cita::findOrFail($id);

            // Solo permitir agregar observaciones si la cita está en proceso
            if ($cita->estado !== 'en_proceso') {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo se pueden agregar observaciones a citas en proceso'
                ], 422);
            }

            DB::beginTransaction();

            // Agregar observaciones con timestamp
            $nuevaObservacion = "\n[" . now()->format('d/m/Y H:i') . "] " . Auth::user()->nombre . ": " . $request->observaciones;
            $cita->observaciones = ($cita->observaciones ?? '') . $nuevaObservacion;
            $cita->save();

            // Registrar en bitácora
            \App\Models\Bitacora::create([
                'usuario_id' => Auth::id(),
                'accion' => 'agregar_observaciones_cita',
                'tabla_afectada' => 'citas',
                'registro_id' => $cita->id,
                'detalles' => "Agregó observaciones a cita ID {$cita->id}"
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Observaciones agregadas correctamente'
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
            Log::error("Error al agregar observaciones a cita ID {$id}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al agregar observaciones'
            ], 500);
        }
    }

    /**
     * Obtener detalles completos de una cita
     */
    public function getCitaDetalles($id)
    {
        try {
            $cita = Cita::with(['usuario', 'vehiculo', 'servicios'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'cita' => [
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
                        'color' => $cita->vehiculo->color,
                    ],
                    'fecha_hora' => $cita->fecha_hora->format('Y-m-d H:i:s'),
                    'fecha_hora_formatted' => $cita->fecha_hora->format('d/m/Y h:i A'),
                    'estado' => $cita->estado,
                    'estado_formatted' => ucfirst(str_replace('_', ' ', $cita->estado)),
                    'observaciones' => $cita->observaciones,
                    'servicios' => $cita->servicios->map(function ($servicio) {
                        return [
                            'id' => $servicio->id,
                            'nombre' => $servicio->nombre,
                            'precio' => $servicio->precio,
                            'pivot' => [
                                'precio' => $servicio->pivot->precio
                            ]
                        ];
                    }),
                    'total' => $cita->servicios->sum('pivot.precio'),
                    'created_at' => $cita->created_at->format('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Error al obtener detalles de cita ID {$id}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los detalles de la cita'
            ], 500);
        }
    }

    /**
     * Finalizar una cita y registrar el pago
     */
    public function finalizarCita(Request $request, $id)
    {
        try {
            $request->validate([
                'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia',
                'monto_recibido' => 'nullable|numeric|min:0',
                'observaciones_finalizacion' => 'nullable|string|max:1000'
            ]);

            $cita = Cita::with(['usuario', 'vehiculo', 'servicios'])->findOrFail($id);

            // Validar que la cita esté en proceso
            if ($cita->estado !== 'en_proceso') {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo se pueden finalizar citas que están en proceso'
                ], 422);
            }

            DB::beginTransaction();

            // Calcular el total de la cita
            $totalCita = $cita->servicios->sum('pivot.precio');

            // Validar monto recibido para efectivo
            if ($request->metodo_pago === 'efectivo') {
                $montoRecibido = $request->monto_recibido ?? 0;

                if ($montoRecibido < $totalCita) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El monto recibido debe ser mayor o igual al total de la cita ($' . number_format($totalCita, 2) . ')'
                    ], 422);
                }

                $cambio = $montoRecibido - $totalCita;
            } else {
                $montoRecibido = $totalCita;
                $cambio = 0;
            }

            // Crear el pago
            $pago = \App\Models\Pago::create([
                'cita_id' => $cita->id,
                'monto' => $totalCita,
                'metodo_pago' => $request->metodo_pago,
                'estado' => 'completado',
                'fecha_pago' => now(),
                'monto_recibido' => $montoRecibido,
                'cambio' => $cambio,
                'comprobante' => 'COMP-' . str_pad($cita->id, 6, '0', STR_PAD_LEFT) . '-' . date('YmdHis')
            ]);

            // Actualizar estado de la cita a finalizada
            $cita->estado = 'finalizada';

            // Agregar observaciones de finalización
            $observacionFinalizacion = "\n[" . now()->format('d/m/Y H:i') . "] Finalizada por " . Auth::user()->nombre;
            $observacionFinalizacion .= "\n- Método de pago: " . ucfirst($request->metodo_pago);
            $observacionFinalizacion .= "\n- Total: $" . number_format($totalCita, 2);

            if ($request->metodo_pago === 'efectivo') {
                $observacionFinalizacion .= "\n- Monto recibido: $" . number_format($montoRecibido, 2);
                $observacionFinalizacion .= "\n- Cambio: $" . number_format($cambio, 2);
            }

            if ($request->filled('observaciones_finalizacion')) {
                $observacionFinalizacion .= "\n- Observaciones: " . $request->observaciones_finalizacion;
            }

            $cita->observaciones = ($cita->observaciones ?? '') . $observacionFinalizacion;
            $cita->save();

            // Registrar en bitácora
            \App\Models\Bitacora::create([
                'usuario_id' => Auth::id(),
                'accion' => 'finalizar_cita',
                'tabla_afectada' => 'citas',
                'registro_id' => $cita->id,
                'detalles' => "Finalizó cita ID {$cita->id}. Método de pago: {$request->metodo_pago}. Total: $" . number_format($totalCita, 2)
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cita finalizada correctamente',
                'pago' => [
                    'comprobante' => $pago->comprobante,
                    'total' => $totalCita,
                    'metodo_pago' => $request->metodo_pago,
                    'cambio' => $cambio
                ]
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
            Log::error("Error al finalizar cita ID {$id}: " . $e->getMessage(), [
                'empleado_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al finalizar la cita: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Historial de servicios completados por el empleado
     */
    public function historial(Request $request)
    {
        $query = Cita::with(['usuario', 'vehiculo', 'servicios', 'pago'])
            ->where('estado', 'finalizada')
            ->orderBy('fecha_hora', 'desc');

        // Aplicar filtros
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_hora', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_hora', '<=', $request->fecha_hasta);
        }

        // Calcular estadísticas ANTES de paginar
        $totalQuery = clone $query;
        $estadisticas = [
            'total_servicios' => $totalQuery->count(),
            'ingresos_generados' => \App\Models\Pago::whereHas('cita', function($q) {
                $q->where('estado', 'finalizada');
            })->where('estado', 'completado')->sum('monto'),
            'servicios_hoy' => Cita::where('estado', 'finalizada')
                ->whereDate('fecha_hora', today())
                ->count(),
            'servicios_mes' => Cita::where('estado', 'finalizada')
                ->whereMonth('fecha_hora', now()->month)
                ->whereYear('fecha_hora', now()->year)
                ->count(),
        ];

        // Paginar después de calcular estadísticas
        $citas = $query->paginate(20)->withQueryString();

        // Si es petición AJAX, retornar JSON
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'citas' => $citas,
                'estadisticas' => $estadisticas
            ]);
        }

        return view('empleado.historial', compact('citas', 'estadisticas'));
    }

    /**
     * Bitácora de acciones (solo lectura)
     */
    public function bitacora(Request $request)
    {
        $query = \App\Models\Bitacora::with('usuario')
            ->where('usuario_id', Auth::id())
            ->orderBy('created_at', 'desc');

        // Aplicar filtros
        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $bitacoras = $query->paginate(30)->withQueryString();

        // Si es petición AJAX, retornar JSON
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($bitacoras);
        }

        return view('empleado.bitacora', compact('bitacoras'));
    }

    /**
     * Actualizar perfil del empleado
     */
    public function actualizarPerfil(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'telefono' => 'required|string|max:20',
            ]);

            $usuario = Auth::user();
            $usuario->nombre = $request->nombre;
            $usuario->telefono = $request->telefono;
            $usuario->save();

            // Registrar en bitácora
            \App\Models\Bitacora::create([
                'usuario_id' => Auth::id(),
                'accion' => 'actualizar_perfil',
                'tabla_afectada' => 'usuarios',
                'registro_id' => $usuario->id,
                'detalles' => "Actualizó su perfil"
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Perfil actualizado correctamente',
                'usuario' => [
                    'nombre' => $usuario->nombre,
                    'telefono' => $usuario->telefono
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error("Error al actualizar perfil: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el perfil'
            ], 500);
        }
    }

    /**
     * Cambiar contraseña del empleado
     */
    public function cambiarPassword(Request $request)
    {
        try {
            $request->validate([
                'password_actual' => 'required',
                'password_nueva' => 'required|min:8',
                'password_confirmacion' => 'required|same:password_nueva'
            ], [
                'password_actual.required' => 'La contraseña actual es requerida',
                'password_nueva.required' => 'La nueva contraseña es requerida',
                'password_nueva.min' => 'La nueva contraseña debe tener al menos 8 caracteres',
                'password_confirmacion.required' => 'Debe confirmar la nueva contraseña',
                'password_confirmacion.same' => 'Las contraseñas no coinciden'
            ]);

            $usuario = Auth::user();

            // Verificar que la contraseña actual sea correcta
            if (!Hash::check($request->password_actual, $usuario->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La contraseña actual es incorrecta'
                ], 422);
            }

            // Actualizar contraseña
            $usuario->password = Hash::make($request->password_nueva);
            $usuario->save();

            // Registrar en bitácora
            \App\Models\Bitacora::create([
                'usuario_id' => Auth::id(),
                'accion' => 'cambiar_password',
                'tabla_afectada' => 'usuarios',
                'registro_id' => $usuario->id,
                'detalles' => "Cambió su contraseña"
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Contraseña actualizada correctamente'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error("Error al cambiar contraseña: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar la contraseña'
            ], 500);
        }
    }
}
