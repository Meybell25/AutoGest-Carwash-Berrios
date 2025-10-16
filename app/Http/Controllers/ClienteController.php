<?php
// app/Http/Controllers/ClienteController.php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Vehiculo;
use App\Models\Servicio;
use App\Models\Usuario;
use App\Models\DiaNoLaborable;
use App\Models\Horario;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class ClienteController extends Controller
{
    public function dashboard(): View
    {
        $user = Auth::user();

        if (!$user || !$user->isCliente()) {
            abort(403, 'Acceso no autorizado');
        }

        try {
            // Procesar citas expiradas primero
            $this->procesarCitasExpiradas();

            // Obtener vehículos del usuario
            $vehiculos = $user->vehiculos()
                ->withCount('citas')
                ->orderByDesc('citas_count')
                ->get();

            $vehiculosDashboard = $vehiculos->take(3);

            // Obtener todas las citas del usuario
            $citas = $user->citas()
                ->with(['vehiculo', 'servicios', 'pago'])
                ->orderBy('fecha_hora', 'desc')
                ->get();

            // Obtener servicios disponibles
            $servicios = Servicio::activos()->get();

            // PRÓXIMAS CITAS - Pendientes, confirmadas y en proceso (futuras)
            $proximas_citas = $citas->filter(function ($cita) {
                return $cita->fecha_hora >= now() &&
                    $cita->estado === 'confirmada';
            })->sortBy('fecha_hora');

            // HISTORIAL - SOLO CANCELADAS O FINALIZADAS
            $historial_citas = $citas->filter(function ($cita) {
                return in_array($cita->estado, ['finalizada', 'cancelada']);
            });

            // FACTURAS DISPONIBLES - Últimas 3 facturas para el dashboard
            $facturas_dashboard = $citas->filter(function ($cita) {
                return $cita->estado === 'finalizada' && $cita->pago;
            })->sortByDesc('fecha_hora')
                ->take(3);

            // ESTADÍSTICAS DE FACTURAS MEJORADAS
            $citasFinalizadasConPago = $citas->where('estado', 'finalizada')
                ->filter(fn($cita) => $cita->pago);

            $estadisticasFacturas = [
                'total_facturas' => $citasFinalizadasConPago->count(),
                'total_gastado' => $citasFinalizadasConPago->sum(fn($cita) => $cita->pago->monto),
                'facturas_mes_actual' => $citasFinalizadasConPago
                    ->filter(fn($cita) => $cita->fecha_hora->month == now()->month &&
                        $cita->fecha_hora->year == now()->year)
                    ->count(),
                'promedio_por_factura' => $citasFinalizadasConPago->count() > 0 ?
                    $citasFinalizadasConPago->sum(fn($cita) => $cita->pago->monto) / $citasFinalizadasConPago->count() : 0,
                'vehiculo_mas_utilizado' => $this->getVehiculoMasUtilizado($citasFinalizadasConPago),
                'servicio_mas_solicitado' => $this->getServicioMasSolicitado($citasFinalizadasConPago),
            ];

            // Obtener próximos días no laborables
            $proximosDiasNoLaborables = DiaNoLaborable::getProximosNoLaborables(3);

            return view('cliente.dashboard', [
                'user' => $user,
                'stats' => [
                    'total_vehiculos' => $vehiculos->count(),
                    'total_citas' => $citas->count(),
                    'citas_pendientes' => $citas->where('estado', 'pendiente')->count(),
                    'citas_confirmadas' => $citas->where('estado', 'confirmada')->count(),
                ],
                'estadisticas_facturas' => $estadisticasFacturas,
                'facturas_dashboard' => $facturas_dashboard,
                'mis_vehiculos' => $vehiculos,
                'vehiculos_dashboard' => $vehiculosDashboard,
                'proximas_citas' => $proximas_citas,
                'historial_citas' => $historial_citas->take(5),
                'proximos_dias_no_laborables' => $proximosDiasNoLaborables,
                'notificaciones' => $user->notificaciones()->orderBy('fecha_envio', 'desc')->get(),
                'notificacionesNoLeidas' => $user->notificaciones()->where('leido', false)->count(),
                'servicios' => $servicios
            ]);
        } catch (\Exception $e) {
            Log::error('Dashboard error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id ?? 'unknown'
            ]);

            // Datos por defecto en caso de error
            $defaultStats = [
                'total_facturas' => 0,
                'total_gastado' => 0,
                'facturas_mes_actual' => 0,
                'promedio_por_factura' => 0,
                'vehiculo_mas_utilizado' => null,
                'servicio_mas_solicitado' => null,
            ];

            return view('cliente.dashboard', [
                'user' => $user,
                'stats' => [
                    'total_vehiculos' => 0,
                    'total_citas' => 0,
                    'citas_pendientes' => 0,
                    'citas_confirmadas' => 0,
                ],
                'estadisticas_facturas' => $defaultStats,
                'facturas_dashboard' => collect(),
                'mis_vehiculos' => collect(),
                'vehiculos_dashboard' => collect(),
                'proximas_citas' => collect(),
                'historial_citas' => collect(),
                'proximos_dias_no_laborables' => collect(),
                'notificaciones' => collect(),
                'notificacionesNoLeidas' => 0,
                'servicios' => collect()
            ]);
        }
    }

    /**
     * Obtener el vehículo más utilizado en las facturas
     */
    private function getVehiculoMasUtilizado($citasFinalizadas)
    {
        if ($citasFinalizadas->isEmpty()) {
            return null;
        }

        $vehiculosCount = $citasFinalizadas->groupBy('vehiculo_id')
            ->map(function ($citas) {
                return [
                    'count' => $citas->count(),
                    'vehiculo' => $citas->first()->vehiculo
                ];
            })
            ->sortByDesc('count')
            ->first();

        return $vehiculosCount ? [
            'vehiculo' => $vehiculosCount['vehiculo'],
            'cantidad' => $vehiculosCount['count']
        ] : null;
    }

    /**
     * Obtener el servicio más solicitado en las facturas
     */
    private function getServicioMasSolicitado($citasFinalizadas)
    {
        if ($citasFinalizadas->isEmpty()) {
            return null;
        }

        $serviciosCount = [];

        foreach ($citasFinalizadas as $cita) {
            foreach ($cita->servicios as $servicio) {
                if (!isset($serviciosCount[$servicio->id])) {
                    $serviciosCount[$servicio->id] = [
                        'servicio' => $servicio,
                        'count' => 0
                    ];
                }
                $serviciosCount[$servicio->id]['count']++;
            }
        }

        if (empty($serviciosCount)) {
            return null;
        }

        $servicioMasSolicitado = collect($serviciosCount)
            ->sortByDesc('count')
            ->first();

        return [
            'servicio' => $servicioMasSolicitado['servicio'],
            'cantidad' => $servicioMasSolicitado['count']
        ];
    }

    public function vehiculos(): View
    {
        $vehiculos = Vehiculo::where('usuario_id', Auth::id())
            ->with('citas')
            ->get();

        return view('VehiculosViews.index', compact('vehiculos'));
    }

    public function citas(Request $request): View
    {
        $this->procesarCitasExpiradas();

        $query = Cita::where('usuario_id', Auth::id())
            ->with(['vehiculo', 'servicios'])
            ->whereIn('estado', ['pendiente', 'confirmada', 'en_proceso']);

        // Aplicar filtros
        if (!$request->filled('estado')) {
            $query->whereIn('estado', ['pendiente', 'confirmada', 'en_proceso']);
        } else {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_hora', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_hora', '<=', $request->fecha_hasta);
        }

        if ($request->filled('vehiculo_id')) {
            $query->where('vehiculo_id', $request->vehiculo_id);
        }

        // Ordenar por fecha más reciente primero
        $citas = $query->orderBy('fecha_hora', 'asc')->paginate(10);

        // Mantener los parámetros de filtro en la paginación
        $citas->appends($request->query());

        return view('cliente.citas', compact('citas'));
    }

    public function misVehiculosAjax()
    {
        $vehiculos = Auth::user()->vehiculos()
            ->withCount('citas')
            ->orderByDesc('citas_count')
            ->take(3)
            ->get();

        return response()->json(['vehiculos' => $vehiculos]);
    }

    public function checkStatus()
    {
        $user = Auth::user();
        return response()->json(['is_active' => $user->estado]);
    }

    public function storeCita(Request $request)
    {
        try {
            // Validar estado del usuario
            if (!Auth::user()->estado) {
                return response()->json(['message' => 'Tu cuenta está inactiva.'], 403);
            }

            // Verificar si es force create
            $forceCreate = $request->header('X-Force-Create') === 'true';

            $validated = $request->validate([
                'vehiculo_id' => 'required|exists:vehiculos,id,usuario_id,' . Auth::id(),
                'fecha_hora' => [
                    'required',
                    'date',
                    'after:now',
                    function ($attribute, $value, $fail) {
                        // Validar que no sea domingo
                        $fecha = Carbon::parse($value);
                        if ($fecha->dayOfWeek === 0) {
                            $fail('No se pueden agendar citas los domingos.');
                        }

                        // Validar que no sea un día no laborable
                        if (DiaNoLaborable::esNoLaborable($value)) {
                            $diaNoLaborable = DiaNoLaborable::whereDate('fecha', $fecha->format('Y-m-d'))->first();
                            $motivosDisponibles = DiaNoLaborable::getMotivosDisponibles();
                            $motivoTexto = $motivosDisponibles[$diaNoLaborable->motivo] ?? $diaNoLaborable->motivo;
                            $fail("No se pueden agendar citas este día. Motivo: {$motivoTexto}");
                        }
                    },
                ],
                'servicios' => 'required|array|min:1',
                'servicios.*' => 'exists:servicios,id',
                'observaciones' => 'nullable|string|max:500'
            ]);

            // Parsear fecha y hora
            $fechaCita = Carbon::parse($validated['fecha_hora'], config('app.timezone'));

            // Validaciones adicionales
            if ($fechaCita->lt(now())) {
                throw new \Exception('No puedes agendar citas en fechas u horas pasadas.', 400);
            }

            if ($fechaCita->gt(Carbon::now()->addMonth())) {
                return response()->json([
                    'message' => 'Solo se pueden agendar citas con máximo 1 mes de anticipación.',
                    'fecha_maxima' => Carbon::now()->addMonth()->format('Y-m-d')
                ], 400);
            }

            // Validar tipo de vehículo vs servicios
            $vehiculo = Vehiculo::find($validated['vehiculo_id']);
            $servicios = Servicio::whereIn('id', $validated['servicios'])->get();

            $serviciosInvalidos = $servicios->where('categoria', '!=', $vehiculo->tipo)->count();
            if ($serviciosInvalidos > 0) {
                return response()->json([
                    'message' => 'Algunos servicios seleccionados no son válidos para este tipo de vehículo.',
                    'tipo_vehiculo' => $vehiculo->tipo,
                    'servicios_validos' => Servicio::where('categoria', $vehiculo->tipo)->pluck('nombre')
                ], 400);
            }

            // Calcular duración total de servicios
            $duracionTotal = $servicios->sum('duracion_min');
            $horaFin = $fechaCita->copy()->addMinutes($duracionTotal);

            // ===============================================
            // VERIFICACIÓN DE HORARIO LABORAL MÁS FLEXIBLE
            // ===============================================
            $diaSemana = $fechaCita->dayOfWeek === 0 ? 7 : $fechaCita->dayOfWeek;
            $horario = Horario::where('dia_semana', $diaSemana)->where('activo', true)->first();

            if ($horario) {
                $horaCierre = Carbon::parse($fechaCita->format('Y-m-d') . ' ' . $horario->hora_fin->format('H:i:s'));

                // CORRECCIÓN: Calcular diferencia CORRECTAMENTE (puede ser negativa)
                $minutosExcedidos = $horaFin->diffInMinutes($horaCierre, false); // false para diferencia con signo

                // Si es negativo, la cita termina ANTES del cierre (no hay exceso)
                if ($minutosExcedidos < 0) {
                    $minutosExcedidos = 0; // No hay tiempo extra
                }

                // SOLO mostrar advertencia si realmente hay exceso
                if ($minutosExcedidos > 0) {
                    // Obtener la hora de cierre normal correctamente
                    $horaCierreNormal = $horario->hora_fin->format('H:i');

                    // CALCULAR VALORES AMIGABLES PARA EL USUARIO
                    $horasExtrasRedondeadas = round($minutosExcedidos / 60, 1);
                    $tiempoFinalizacion = $horaFin->format('H:i');
                    $horaCierreNormal = Carbon::parse($horario->hora_fin)->format('H:i');

                    // MENSAJES MÁS AMIGABLES SEGÚN EL TIEMPO EXTRA
                    $mensajeUsuario = '';
                    $nivelUrgencia = 'info'; // info, warning, error

                    if ($minutosExcedidos <= 30) {
                        $mensajeUsuario = "Tu cita terminará aproximadamente a las {$tiempoFinalizacion}, unos minutos después del horario habitual.";
                        $nivelUrgencia = 'info';
                    } elseif ($minutosExcedidos <= 60) {
                        $mensajeUsuario = "Tu cita terminará alrededor de las {$tiempoFinalizacion}, requiriendo aproximadamente {$horasExtrasRedondeadas} hora extra de atención.";
                        $nivelUrgencia = 'warning';
                    } elseif ($minutosExcedidos <= 90) {
                        $mensajeUsuario = "Los servicios que seleccionaste requieren tiempo adicional considerable. Tu cita finalizaría aproximadamente a las {$tiempoFinalizacion}.";
                        $nivelUrgencia = 'warning';
                    } else {
                        $mensajeUsuario = "Los servicios seleccionados requieren demasiado tiempo adicional. Por favor considera seleccionar un horario más temprano o reducir algunos servicios.";
                        $nivelUrgencia = 'error';
                    }

                    //  LÓGICA MÁS PERMISIVA:
                    if ($forceCreate) {
                        // Si ya fue forzada, permitir hasta 90 minutos extra
                        if ($minutosExcedidos > 90) {
                            return response()->json([
                                'success' => false,
                                'message' => 'No podemos procesar esta cita',
                                'mensaje_usuario' => 'Los servicios que seleccionaste tomarían demasiado tiempo. Te sugerimos elegir un horario más temprano o considerar dividir los servicios en dos citas.',
                                'sugerencias' => [
                                    'Selecciona un horario antes de las ' . $fechaCita->copy()->subMinutes($duracionTotal)->addMinutes(90)->format('H:i'),
                                    'Considera dividir los servicios en dos citas separadas',
                                    'Contacta directamente al establecimiento para opciones especiales'
                                ],
                                'duracion_total_minutos' => $duracionTotal,
                                'tiempo_disponible' => 90
                            ], 400);
                        }

                        Log::info('Cita con extensión de horario forzada', [
                            'usuario_id' => Auth::id(),
                            'minutos_excedidos' => $minutosExcedidos,
                            'fecha_hora' => $fechaCita,
                            'servicios' => $servicios->pluck('nombre'),
                            'duracion_total' => $duracionTotal
                        ]);
                    } else {
                        // Primera validación - mostrar advertencia hasta 90 minutos
                        if ($minutosExcedidos <= 90) {
                            return response()->json([
                                'success' => true,
                                'es_advertencia' => true,
                                'message' => 'Tiempo adicional requerido',
                                'mensaje_usuario' => $mensajeUsuario,
                                'nivel_urgencia' => $nivelUrgencia,
                                'detalles_cita' => [
                                    'hora_inicio' => $fechaCita->format('H:i'),
                                    'hora_finalizacion_estimada' => $tiempoFinalizacion,
                                    'duracion_servicios' => $duracionTotal,
                                    'tiempo_extra_minutos' => $minutosExcedidos,
                                    'tiempo_extra_horas' => $horasExtrasRedondeadas,
                                    'horario_cierre_normal' => $horaCierreNormal,
                                ],
                                'beneficios' => [
                                    'Recibirás atención personalizada sin prisa',
                                    'Todos tus servicios se completarán en una sola visita',
                                    'No necesitarás programar citas adicionales'
                                ],
                                'nota_importante' => $minutosExcedidos <= 30
                                    ? 'El tiempo extra es mínimo y parte del servicio normal.'
                                    : 'Nuestro equipo estará disponible para completar todos tus servicios.'
                            ], 200);
                        } else {
                            // Más de 90 minutos - error firme pero amigable
                            return response()->json([
                                'success' => false,
                                'message' => 'Horario no disponible',
                                'mensaje_usuario' => $mensajeUsuario,
                                'sugerencias' => [
                                    'Programa tu cita antes de las ' . $fechaCita->copy()->subMinutes($duracionTotal)->addMinutes(90)->format('H:i'),
                                    'Considera dividir los servicios en dos visitas',
                                    'Selecciona menos servicios para esta cita',
                                    'Elige otro día con más disponibilidad'
                                ],
                                'horarios_sugeridos' => $this->getAvailableTimes($fechaCita->format('Y-m-d')),
                                'duracion_total_minutos' => $duracionTotal,
                                'tiempo_maximo_permitido' => 90
                            ], 400);
                        }
                    }
                }
            }

            // VERIFICACIÓN DE CONFLICTOS TEMPORALMENTE DESHABILITADA PARA DEBUG 
            Log::info('Verificando conflictos de horario', [
                'fecha_hora_inicio' => $fechaCita->format('Y-m-d H:i:s'),
                'duracion_total' => $duracionTotal,
                'hora_fin' => $horaFin->format('Y-m-d H:i:s')
            ]);

            $conflictos = Cita::where('estado', '!=', 'cancelada')
                ->whereDate('fecha_hora', $fechaCita->format('Y-m-d'))
                ->with('servicios')
                ->get()
                ->filter(function ($citaExistente) use ($fechaCita, $horaFin, $request) {
                    // Excluir cita actual si se está editando
                    if ($request->has('cita_id') && $citaExistente->id == $request->cita_id) {
                        return false;
                    }

                    $inicioCitaExistente = Carbon::parse($citaExistente->fecha_hora);
                    $duracionCitaExistente = $citaExistente->servicios->sum('duracion_min');
                    $finCitaExistente = $inicioCitaExistente->copy()->addMinutes($duracionCitaExistente);

                    // BUFFER DE TIEMPO: 5 minutos entre citas para preparación
                    $bufferInicio = $inicioCitaExistente->copy()->subMinutes(5);
                    $bufferFin = $finCitaExistente->copy()->addMinutes(5);

                    // Verificar superposición con buffer
                    $hayConflicto = (
                        // Nueva cita empieza durante cita existente (con buffer)
                        ($fechaCita->gte($bufferInicio) && $fechaCita->lt($bufferFin)) ||
                        // Nueva cita termina durante cita existente (con buffer)
                        ($horaFin->gt($bufferInicio) && $horaFin->lte($bufferFin)) ||
                        // Nueva cita envuelve completamente a la existente
                        ($fechaCita->lte($bufferInicio) && $horaFin->gte($bufferFin))
                    );

                    if ($hayConflicto) {
                        Log::info('Conflicto detectado con buffer', [
                            'cita_existente_id' => $citaExistente->id,
                            'cita_existente_inicio' => $inicioCitaExistente->format('Y-m-d H:i:s'),
                            'cita_existente_fin' => $finCitaExistente->format('Y-m-d H:i:s'),
                            'buffer_inicio' => $bufferInicio->format('Y-m-d H:i:s'),
                            'buffer_fin' => $bufferFin->format('Y-m-d H:i:s'),
                            'nueva_cita_inicio' => $fechaCita->format('Y-m-d H:i:s'),
                            'nueva_cita_fin' => $horaFin->format('Y-m-d H:i:s')
                        ]);
                    }

                    return $hayConflicto;
                });

            if ($conflictos->isNotEmpty()) {
                $horariosDisponibles = $this->getAvailableTimes($fechaCita->format('Y-m-d'), $request->cita_id ?? null);

                Log::info('Conflictos encontrados', [
                    'total_conflictos' => $conflictos->count(),
                    'conflictos_ids' => $conflictos->pluck('id')->toArray(),
                    'horarios_disponibles' => $horariosDisponibles
                ]);

                return response()->json([
                    'message' => 'Existe un conflicto de horario con otra cita.',
                    'horarios_disponibles' => $horariosDisponibles,
                    'duracion_total' => $duracionTotal,
                    'conflictos_detalles' => $conflictos->map(function ($cita) {
                        return [
                            'id' => $cita->id,
                            'fecha_hora' => $cita->fecha_hora->format('Y-m-d H:i'),
                            'cliente' => $cita->usuario->nombre ?? 'N/A',
                            'servicios' => $cita->servicios->pluck('nombre')->join(', ')
                        ];
                    })
                ], 409);
            }

            // Si no hay conflictos, crear la cita
            DB::beginTransaction();

            $cita = Cita::create([
                'usuario_id' => Auth::id(),
                'vehiculo_id' => $validated['vehiculo_id'],
                'fecha_hora' => $fechaCita,
                'estado' => Cita::ESTADO_PENDIENTE,
                'observaciones' => $validated['observaciones'] ?? null
            ]);

            $serviciosConPrecio = $servicios->mapWithKeys(function ($servicio) {
                return [$servicio->id => ['precio' => $servicio->precio]];
            });

            $cita->servicios()->attach($serviciosConPrecio);

            // Registrar en bitácora
            \App\Models\Bitacora::create([
                'usuario_id' => Auth::id(),
                'accion' => 'crear_cita',
                'tabla_afectada' => 'citas',
                'registro_id' => $cita->id,
                'detalles' => "Creó cita para {$fechaCita->format('d/m/Y H:i')} - Vehículo: {$vehiculo->marca} {$vehiculo->modelo}"
            ]);

            DB::commit();

            // Registrar en bitácora
            \App\Models\Bitacora::registrar(\App\Models\Bitacora::ACCION_ACTUALIZAR_CITA, Auth::id(), request()->ip());

            return response()->json([
                'success' => true,
                'message' => 'Cita creada exitosamente',
                'data' => [
                    'cita_id' => $cita->id,
                    'fecha_hora' => $fechaCita->format('Y-m-d H:i:s'),
                    'servicios_count' => count($validated['servicios']),
                    'servicios_nombres' => $servicios->pluck('nombre')->join(', '),
                    'duracion_total' => $duracionTotal,
                    'hora_fin' => $horaFin->format('H:i'),
                    'vehiculo_marca' => $vehiculo->marca,
                    'vehiculo_modelo' => $vehiculo->modelo,
                    'vehiculo_placa' => $vehiculo->placa,
                    'precio_total' => $servicios->sum('precio')
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear cita', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'usuario_id' => Auth::id(),
                'datos' => $validated ?? $request->all()
            ]);

            $statusCode = is_int($e->getCode()) && $e->getCode() >= 100 && $e->getCode() < 600
                ? $e->getCode()
                : 500;

            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Error interno del servidor.',
                'error_type' => get_class($e)
            ], $statusCode);
        }
    }

    /**
     * Método temporal simplificado para crear citas SIN validación de conflictos
     */
    public function storeCitaSimple(Request $request)
    {
        try {
            Log::info('USANDO MÉTODO SIMPLIFICADO SIN VALIDACIÓN DE CONFLICTOS');

            // Validar estado del usuario
            if (!Auth::user()->estado) {
                return response()->json(['message' => 'Tu cuenta está inactiva.'], 403);
            }

            $validated = $request->validate([
                'vehiculo_id' => 'required|exists:vehiculos,id,usuario_id,' . Auth::id(),
                'fecha_hora' => [
                    'required',
                    'date',
                    'after:now',
                ],
                'servicios' => 'required|array|min:1',
                'servicios.*' => 'exists:servicios,id',
                'observaciones' => 'nullable|string|max:500'
            ]);

            DB::beginTransaction();

            $vehiculo = Vehiculo::find($validated['vehiculo_id']);
            $servicios = Servicio::activos()->whereIn('id', $validated['servicios'])->get();
            $fechaCita = Carbon::parse($validated['fecha_hora']);
            $duracionTotal = $servicios->sum('duracion_min');
            $horaFin = $fechaCita->copy()->addMinutes($duracionTotal);

            Log::info('Creando cita simplificada', [
                'vehiculo' => $vehiculo->marca . ' ' . $vehiculo->modelo,
                'servicios' => $servicios->pluck('nombre')->toArray(),
                'fecha_hora' => $fechaCita->format('Y-m-d H:i:s'),
                'duracion' => $duracionTotal
            ]);

            // Crear la cita directamente SIN validaciones de conflicto
            $cita = Cita::create([
                'usuario_id' => Auth::id(),
                'vehiculo_id' => $validated['vehiculo_id'],
                'fecha_hora' => $fechaCita,
                'estado' => Cita::ESTADO_PENDIENTE,
                'observaciones' => $validated['observaciones'] ?? null
            ]);

            $serviciosConPrecio = $servicios->mapWithKeys(function ($servicio) {
                return [$servicio->id => ['precio' => $servicio->precio]];
            });

            $cita->servicios()->attach($serviciosConPrecio);

            // Registrar en bitácora
            \App\Models\Bitacora::create([
                'usuario_id' => Auth::id(),
                'accion' => 'crear_cita_simple',
                'tabla_afectada' => 'citas',
                'registro_id' => $cita->id,
                'detalles' => "Creó cita simplificada para {$fechaCita->format('d/m/Y H:i')} - Vehículo: {$vehiculo->marca} {$vehiculo->modelo}"
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cita creada exitosamente (método simplificado)',
                'data' => [
                    'cita_id' => $cita->id,
                    'fecha_hora' => $fechaCita->format('Y-m-d H:i:s'),
                    'hora' => $fechaCita->format('H:i'),
                    'servicios_count' => count($validated['servicios']),
                    'servicios_nombres' => $servicios->pluck('nombre')->join(', '),
                    'duracion_total' => $duracionTotal,
                    'hora_fin' => $horaFin->format('H:i'),
                    'vehiculo_marca' => $vehiculo->marca,
                    'vehiculo_modelo' => $vehiculo->modelo,
                    'vehiculo_placa' => $vehiculo->placa,
                    'precio_total' => $servicios->sum('precio')
                ]
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación: ' . implode(', ', $e->validator->errors()->all())
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating simple appointment:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }

    private function getAvailableTimes($date, $excludeCitaId = null)
    {
        try {
            // Crear fecha usando Carbon sin problemas de timezone
            $date = Carbon::createFromFormat('Y-m-d', $date, config('app.timezone'))->startOfDay();

            // Obtener día de la semana ISO (1=Lunes, 7=Domingo)
            $dayOfWeekISO = $date->dayOfWeekIso;

            Log::info("getAvailableTimes:", [
                'fecha' => $date->toDateString(),
                'dia_semana_iso' => $dayOfWeekISO,
                'nombre_dia' => $date->locale('es')->dayName,
                'exclude_cita_id' => $excludeCitaId
            ]);

            // Verificar si es domingo (ISO = 7)
            if ($dayOfWeekISO === 7) {
                Log::info("Domingo detectado, no hay horarios disponibles");
                return [];
            }

            // Verificar días no laborables
            if (DiaNoLaborable::whereDate('fecha', $date)->exists()) {
                Log::info("Día no laborable detectado");
                return [];
            }

            // Obtener horarios programados para este día ISO
            $horariosDisponibles = \App\Models\Horario::where('dia_semana', $dayOfWeekISO)
                ->where('activo', true)
                ->get();

            if ($horariosDisponibles->isEmpty()) {
                Log::info("No hay horarios programados para este día");
                return [];
            }

            // Obtener citas existentes para esta fecha (excluyendo cita específica si existe)
            $query = Cita::whereDate('fecha_hora', $date)
                ->where('estado', '!=', 'cancelada')
                ->with('servicios');

            if ($excludeCitaId) {
                $query->where('id', '!=', $excludeCitaId);
            }

            $citas = $query->get();

            // Crear array de bloques de tiempo ocupados con buffer
            $bloquesOcupados = [];
            foreach ($citas as $cita) {
                $inicio = Carbon::parse($cita->fecha_hora);
                $duracion = $cita->servicios->sum('duracion_min');
                $fin = $inicio->copy()->addMinutes($duracion);

                // Agregar buffer de 5 minutos antes y después
                $inicioConBuffer = $inicio->copy()->subMinutes(5);
                $finConBuffer = $fin->copy()->addMinutes(5);

                $bloquesOcupados[] = [
                    'inicio' => $inicioConBuffer,
                    'fin' => $finConBuffer,
                    'cita_id' => $cita->id
                ];
            }

            // Generar horarios disponibles (intervalos de 60 minutos en lugar de 30)
            $horariosLibres = [];
            $intervalo = 60; // CAMBIO: 60 minutos en lugar de 30

            foreach ($horariosDisponibles as $horario) {
                $horaInicio = $date->copy()->setTimeFromTimeString($horario->hora_inicio->format('H:i'));
                $horaCierre = $date->copy()->setTimeFromTimeString($horario->hora_fin->format('H:i'));

                $horaActual = $horaInicio->copy();

                while ($horaActual->lt($horaCierre)) {
                    // Verificar si este bloque está disponible
                    $disponible = true;
                    foreach ($bloquesOcupados as $bloque) {
                        if ($horaActual->lt($bloque['fin']) && $horaActual->copy()->addMinutes($intervalo)->gt($bloque['inicio'])) {
                            $disponible = false;
                            break;
                        }
                    }

                    if ($disponible) {
                        $horariosLibres[] = $horaActual->format('H:i');
                    }

                    $horaActual->addMinutes($intervalo);
                }
            }

            // Agregar horarios de 30 minutos también para mayor flexibilidad
            foreach ($horariosDisponibles as $horario) {
                $horaInicio = $date->copy()->setTimeFromTimeString($horario->hora_inicio->format('H:i'));
                $horaCierre = $date->copy()->setTimeFromTimeString($horario->hora_fin->format('H:i'));

                $horaActual = $horaInicio->copy()->addMinutes(30); // Comenzar en :30

                while ($horaActual->lt($horaCierre)) {
                    // Verificar si este bloque está disponible
                    $disponible = true;
                    foreach ($bloquesOcupados as $bloque) {
                        if ($horaActual->lt($bloque['fin']) && $horaActual->copy()->addMinutes(30)->gt($bloque['inicio'])) {
                            $disponible = false;
                            break;
                        }
                    }

                    if ($disponible) {
                        $horariosLibres[] = $horaActual->format('H:i');
                    }

                    $horaActual->addMinutes(60); // Saltar a la próxima media hora disponible
                }
            }

            // Ordenar y eliminar duplicados
            $horariosLibres = array_unique($horariosLibres);
            sort($horariosLibres);

            // Si la fecha es hoy, filtrar horarios que ya pasaron
            if ($date->isToday()) {
                $horaActualNow = Carbon::now();
                $horariosLibres = array_filter($horariosLibres, function ($hora) use ($horaActualNow) {
                    $horaCita = Carbon::createFromFormat('H:i', $hora);
                    return $horaCita->gt($horaActualNow);
                });

                $horariosLibres = array_values($horariosLibres);
            }

            Log::info("Horarios libres generados con intervalos mixtos:", [
                'count' => count($horariosLibres),
                'horarios' => $horariosLibres
            ]);

            return $horariosLibres;
        } catch (\Exception $e) {
            Log::error('Error en getAvailableTimes: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'date' => $date,
                'excludeCitaId' => $excludeCitaId
            ]);
            return [];
        }
    }

    public function cancelarCita(Cita $cita)
    {
        // Verificar que la cita pertenece al usuario
        if ($cita->usuario_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para cancelar esta cita'
            ], 403);
        }

        // Estados permitidos para cancelación
        $estadosCancelables = [Cita::ESTADO_PENDIENTE, Cita::ESTADO_CONFIRMADA];

        if (!in_array($cita->estado, $estadosCancelables)) {
            return response()->json([
                'success' => false,
                'message' => 'Solo se pueden cancelar citas en estado: ' .
                    implode(' o ', array_map(function ($estado) {
                        return Cita::getEstados()[$estado];
                    }, $estadosCancelables))
            ], 400);
        }

        // Verificar que la cita no sea muy próxima (mínimo 2 horas de anticipación)
        if ($cita->fecha_hora->diffInHours(now()) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'No se pueden cancelar citas con menos de 2 horas de anticipación. Por favor, contacta directamente al establecimiento.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $fechaOriginal = $cita->fecha_hora->format('d/m/Y H:i');
            $serviciosNombres = $cita->servicios->pluck('nombre')->join(', ');

            $cita->estado = Cita::ESTADO_CANCELADA;
            $cita->save();

            // Crear notificación
            $cita->usuario->notificaciones()->create([
                'titulo' => 'Cita cancelada',
                'mensaje' => "Tu cita para el {$fechaOriginal} ha sido cancelada exitosamente. Servicios: {$serviciosNombres}",
                'tipo' => 'cancelacion',
                'fecha_envio' => now(),
                'leido' => false
            ]);

            // Registrar en bitácora
            \App\Models\Bitacora::create([
                'usuario_id' => Auth::id(),
                'accion' => 'cancelar_cita',
                'tabla_afectada' => 'citas',
                'registro_id' => $cita->id,
                'detalles' => "Canceló cita programada para {$fechaOriginal}"
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cita cancelada exitosamente',
                'cita_id' => $cita->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al cancelar cita', [
                'cita_id' => $cita->id,
                'usuario_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor. Por favor, inténtalo de nuevo.'
            ], 500);
        }
    }

    public function getDashboardData()
    {
        try {
            $user = Auth::user();

            if (!$user || !$user->isCliente()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acceso no autorizado'
                ], 403);
            }

            // Procesar citas expiradas antes de cargar datos
            $this->procesarCitasExpiradas();

            // Obtener datos
            $vehiculos = $user->vehiculos()
                ->withCount('citas')
                ->orderByDesc('citas_count')
                ->get();

            $vehiculosDashboard = $vehiculos->take(3);

            // Obtener todas las citas del usuario
            $todasLasCitas = $user->citas()
                ->with(['vehiculo', 'servicios'])
                ->orderBy('fecha_hora', 'desc')
                ->get();

            // PRÓXIMAS CITAS - SOLO CONFIRMADAS
            $proximas_citas = $todasLasCitas->where('estado', 'confirmada')
                ->where('fecha_hora', '>=', now())
                ->sortBy('fecha_hora')
                ->values();

            // HISTORIAL - SOLO CANCELADAS O FINALIZADAS
            $historial_citas = $todasLasCitas->filter(function ($cita) {
                return in_array($cita->estado, ['cancelada', 'finalizada']);
            })->values();

            // Obtener próximos días no laborables
            $proximosDiasNoLaborables = DiaNoLaborable::getProximosNoLaborables(3);

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user,
                    'stats' => [
                        'total_vehiculos' => $vehiculos->count(),
                        'total_citas' => $todasLasCitas->count(),
                        'citas_pendientes' => $todasLasCitas->where('estado', 'pendiente')->count(),
                        'citas_confirmadas' => $todasLasCitas->where('estado', 'confirmada')->count(),
                    ],
                    'vehiculos_dashboard' => $vehiculosDashboard,
                    'proximas_citas' => $proximas_citas->values(),
                    'historial_citas' => $historial_citas->values(),
                    'proximos_dias_no_laborables' => $proximosDiasNoLaborables,
                    'notificaciones' => $user->notificaciones()->orderBy('fecha_envio', 'desc')->limit(10)->get(),
                    'notificacionesNoLeidas' => $user->notificaciones()->where('leido', false)->count()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error en getDashboardData', [
                'usuario_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al cargar datos del dashboard',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function edit(Cita $cita, Request $request)
    {
        if ($cita->usuario_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para editar esta cita'
            ], 403);
        }

        if (!in_array($cita->estado, ['pendiente', 'confirmada'])) {
            return response()->json([
                'success' => false,
                'message' => 'Solo se pueden editar citas en estado pendiente o confirmada'
            ], 400);
        }

        // Calcular restricción de 24 horas
        $fechaActual = now();
        $fechaCita = Carbon::parse($cita->fecha_hora);
        $horasRestantes = $fechaActual->diffInHours($fechaCita, false);
        $restriccion_24h = $cita->estado === 'confirmada' && $horasRestantes < 24;

        return response()->json([
            'success' => true,
            'data' => [
                'cita' => $cita->load(['vehiculo', 'servicios']),
                'servicios' => Servicio::activos()->where('categoria', $cita->vehiculo->tipo)->get(),
                'vehiculos' => Auth::user()->vehiculos,
                'horarios_disponibles' => $this->getHorariosDisponibles($cita->fecha_hora->format('Y-m-d'), $request),
                'dias_no_laborables' => DiaNoLaborable::futuros()->pluck('fecha')->map(function ($fecha) {
                    return $fecha->format('Y-m-d');
                }),
                'restriccion_24h' => $restriccion_24h,
                'vehiculo_id' => $cita->vehiculo_id,
                'fecha' => $cita->fecha_hora->format('Y-m-d'),
                'hora' => $cita->fecha_hora->format('H:i'),
                'observaciones' => $cita->observaciones
            ]
        ]);
    }

    public function updateCita(Request $request, Cita $cita)
    {
        // Forzar servicios como array (incluso si viene como string)
        $request->merge(['servicios' => (array)$request->input('servicios', [])]);

        Log::debug('Datos recibidos para actualizar cita:', $request->all());

        // Verificar permisos
        if ($cita->usuario_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para editar esta cita'
            ], 403);
        }

        if (!in_array($cita->estado, ['pendiente', 'confirmada'])) {
            return response()->json([
                'success' => false,
                'message' => 'Solo se pueden editar citas en estado pendiente o confirmada'
            ], 400);
        }

        // Agregar el ID de la cita actual al request para validación
        $request->merge(['cita_id' => $cita->id]);

        try {
            $validated = $request->validate([
                'vehiculo_id' => 'required|exists:vehiculos,id,usuario_id,' . Auth::id(),
                'fecha_hora' => 'required|date|after_or_equal:today', // ✅ Cambiado a fecha_hora
                'servicios' => 'required|array|min:1',
                'servicios.*' => 'exists:servicios,id',
                'observaciones' => 'nullable|string|max:500'
            ]);

            // Parsear fecha y hora (ya vienen combinadas)
            $fechaCita = Carbon::parse($validated['fecha_hora'], config('app.timezone'));

            // Bloquear cambios de fecha/hora/vehículo si faltan menos de 24 horas para cita confirmada
            $fechaActual = now();
            $fechaCitaOriginal = Carbon::parse($cita->fecha_hora);
            $horasRestantes = $fechaActual->diffInHours($fechaCitaOriginal, false);

            if ($cita->estado === 'confirmada' && $horasRestantes < 24) {
                // Verificar qué campos intentan modificar
                $haCambiadoFechaHora = !$fechaCitaOriginal->equalTo($fechaCita);
                $haCambiadoVehiculo = $cita->vehiculo_id != $validated['vehiculo_id'];

                if ($haCambiadoFechaHora || $haCambiadoVehiculo) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No puedes cambiar la fecha, hora o vehículo cuando faltan menos de 24 horas para la cita confirmada. Solo puedes modificar servicios u observaciones.'
                    ], 400);
                }
            }

            DB::beginTransaction();

            // Verificar si la fecha/hora ha cambiado
            $haCambiadoFecha = !$fechaCitaOriginal->equalTo($fechaCita);

            // Si la cita estaba confirmada y cambió la fecha, cambiar a pendiente
            $nuevoEstado = $cita->estado;
            if ($cita->estado === 'confirmada' && $haCambiadoFecha) {
                $nuevoEstado = 'pendiente';
            }

            // Validar que la nueva fecha/hora no sea en el pasado
            if ($fechaCita->lt(now())) {
                throw new \Exception('No puedes cambiar la cita a una fecha u hora pasada.', 400);
            }

            // Validaciones básicas
            if ($fechaCita->isSunday()) {
                throw new \Exception('No atendemos domingos.', 400);
            }

            if ($fechaCita->gt(Carbon::now()->addMonth())) {
                throw new \Exception('Máximo 1 mes de anticipación.', 400);
            }

            if (DiaNoLaborable::whereDate('fecha', $fechaCita)->exists()) {
                throw new \Exception('Día no laborable.', 400);
            }

            $hora = $fechaCita->format('H:i');
            if ($hora < '08:00' || $hora > '18:00') {
                throw new \Exception('Horario no laboral (8:00 AM - 6:00 PM).', 400);
            }

            // Validar tipo de vehículo vs servicios
            $vehiculo = Vehiculo::find($validated['vehiculo_id']);
            $servicios = Servicio::whereIn('id', $validated['servicios'])->get();

            foreach ($servicios as $servicio) {
                if ($servicio->categoria !== $vehiculo->tipo) {
                    throw new \Exception('El servicio "' . $servicio->nombre . '" no está disponible para ' . $vehiculo->tipo . 's.', 400);
                }
            }

            // Calcular duración total
            $duracionTotal = $servicios->sum('duracion_min');
            $horaFin = $fechaCita->copy()->addMinutes($duracionTotal);

            if ($horaFin->format('H:i') > '18:00') {
                throw new \Exception('Los servicios seleccionados exceden el horario de cierre.', 400);
            }


            // Verificar colisión con otras citas (excluir cita actual si existe)
            $citasQuery = Cita::where('estado', '!=', 'cancelada')
                ->whereDate('fecha_hora', $fechaCita->format('Y-m-d'))
                ->where(function ($query) use ($fechaCita, $horaFin) {
                    $query->where(function ($q) use ($fechaCita, $horaFin) {
                        // Citas que empiezan durante la nueva cita
                        $q->where('fecha_hora', '>=', $fechaCita)
                            ->where('fecha_hora', '<', $horaFin);
                    })->orWhere(function ($q) use ($fechaCita, $horaFin) {
                        // Citas que terminan durante la nueva cita
                        $q->whereRaw('DATE_ADD(fecha_hora, INTERVAL (
                SELECT SUM(servicios.duracion_min)
                FROM cita_servicio
                JOIN servicios ON cita_servicio.servicio_id = servicios.id
                WHERE cita_servicio.cita_id = citas.id
            ) MINUTE) > ?', [$fechaCita])
                            ->where('fecha_hora', '<=', $fechaCita);
                    });
                });

            // Excluir la cita actual si se está editando
            if ($request->has('cita_id') && $request->cita_id) {
                $citasQuery->where('id', '!=', $request->cita_id);
            }

            $citasSuperpuestas = $citasQuery->exists();

            if ($citasSuperpuestas) {
                $horariosDisponibles = $this->getAvailableTimes($fechaCita->format('Y-m-d'), $cita->id);
                return response()->json([
                    'success' => false,
                    'message' => 'El horario seleccionado está ocupado.',
                    'data' => [
                        'available_times' => $horariosDisponibles,
                        'duracion_total' => $duracionTotal
                    ]
                ], 409);
            }

            // Actualizar cita
            $cita->update([
                'vehiculo_id' => $validated['vehiculo_id'],
                'fecha_hora' => $fechaCita,
                'estado' => $nuevoEstado,
                'observaciones' => $validated['observaciones'] ?? null
            ]);

            // Sincronizar servicios
            $serviciosConPrecio = [];
            foreach ($validated['servicios'] as $servicioId) {
                $servicio = Servicio::find($servicioId);
                $serviciosConPrecio[$servicioId] = ['precio' => $servicio->precio];
            }
            $cita->servicios()->sync($serviciosConPrecio);

            DB::commit();

            // Recargar la cita con relaciones
            $cita->load(['vehiculo', 'servicios']);

            return response()->json([
                'success' => true,
                'message' => 'Cita actualizada exitosamente',
                'data' => [
                    'cita_id' => $cita->id,
                    'fecha_hora' => $fechaCita->format('Y-m-d H:i:s'),
                    'servicios_nombres' => $cita->servicios->pluck('nombre')->join(', '),
                    'vehiculo_marca' => $cita->vehiculo->marca,
                    'vehiculo_modelo' => $cita->vehiculo->modelo,
                    'vehiculo_placa' => $cita->vehiculo->placa ?? '',
                    'nuevo_estado' => $nuevoEstado
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
            Log::error('Error al actualizar cita: ' . $e->getMessage(), [
                'cita_id' => $cita->id,
                'request' => $request->all(),
                'error' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getHorariosOcupados(Request $request)
    {
        try {
            $fecha = $request->query('fecha');
            $excludeCitaId = $request->query('exclude');

            if (!$fecha) {
                return response()->json(['horariosOcupados' => []]);
            }

            // Validar formato de fecha
            try {
                $fechaCarbon = \Carbon\Carbon::createFromFormat('Y-m-d', $fecha)->startOfDay();
            } catch (\Exception $e) {
                Log::error("Error al parsear fecha:", ['fecha' => $fecha, 'error' => $e->getMessage()]);
                return response()->json(['horariosOcupados' => []], 400);
            }

            $query = Cita::with('servicios')
                ->whereDate('fecha_hora', $fechaCarbon)
                ->where('estado', '!=', 'cancelada');

            // Excluir cita específica si se proporciona
            if ($excludeCitaId) {
                $query->where('id', '!=', $excludeCitaId);
            }

            $citas = $query->get();

            $horariosOcupados = $citas->map(function ($cita) {
                $horaInicio = \Carbon\Carbon::parse($cita->fecha_hora);
                $duracionTotal = $cita->servicios->sum('duracion_min') ?: 30;

                return [
                    'cita_id' => $cita->id,
                    'hora_inicio' => $horaInicio->format('H:i'),
                    'duracion' => $duracionTotal,
                    'hora_fin' => $horaInicio->copy()->addMinutes($duracionTotal)->format('H:i'),
                    'estado' => $cita->estado,
                    'fecha_hora_completa' => $cita->fecha_hora->format('Y-m-d H:i:s'), // AÑADIR ESTO
                    'servicios_nombres' => $cita->servicios->pluck('nombre')->join(', ')
                ];
            });

            Log::info("Horarios ocupados para {$fecha}:", [
                'exclude_cita_id' => $excludeCitaId,
                'total_citas' => $citas->count(),
                'horarios_ocupados' => $horariosOcupados->toArray()
            ]);

            return response()->json(['horariosOcupados' => $horariosOcupados]);
        } catch (\Exception $e) {
            Log::error('Error en getHorariosOcupados: ' . $e->getMessage(), [
                'fecha' => $request->query('fecha'),
                'exclude' => $request->query('exclude'),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['horariosOcupados' => []], 500);
        }
    }

    public function historial(Request $request)
    {
        // Procesar citas expiradas antes de mostrar historial
        $this->procesarCitasExpiradas();

        $user = auth()->user();

        // Obtener citas para historial - SOLO finalizadas y canceladas
        $query = $user->citas()
            ->with(['servicios', 'vehiculo', 'pago'])
            ->whereIn('estado', [Cita::ESTADO_FINALIZADA, Cita::ESTADO_CANCELADA]);

        // CREAR UNA COPIA DE LA QUERY PARA LOS CONTADORES (sin paginación)
        $queryParaContadores = clone $query;

        // Aplicar filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
            $queryParaContadores->where('estado', $request->estado);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_hora', '>=', $request->fecha_desde);
            $queryParaContadores->whereDate('fecha_hora', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_hora', '<=', $request->fecha_hasta);
            $queryParaContadores->whereDate('fecha_hora', '<=', $request->fecha_hasta);
        }

        if ($request->filled('vehiculo_id')) {
            $query->where('vehiculo_id', $request->vehiculo_id);
            $queryParaContadores->where('vehiculo_id', $request->vehiculo_id);
        }

        // CALCULAR LOS CONTADORES CON LOS FILTROS APLICADOS
        $totalFiltradas = $queryParaContadores->count();
        $finalizadasFiltradas = (clone $queryParaContadores)->where('estado', Cita::ESTADO_FINALIZADA)->count();
        $canceladasFiltradas = (clone $queryParaContadores)->where('estado', Cita::ESTADO_CANCELADA)->count();

        // ORDENAR POR FECHA MÁS RECIENTE PRIMERO (orden cronológico descendente)
        $citas = $query->orderBy('fecha_hora', 'desc')->paginate(15);

        // Mantener los parámetros de filtro en la paginación
        $citas->appends($request->query());

        return view('cliente.historial', [
            'citas' => $citas,
            'user' => $user,
            // PASAR LOS CONTADORES CORRECTOS
            'contadores' => [
                'total' => $totalFiltradas,
                'finalizadas' => $finalizadasFiltradas,
                'canceladas' => $canceladasFiltradas
            ]
        ]);
    }


    /**
     * Procesar citas expiradas automáticamente
     */
    private function procesarCitasExpiradas()
    {
        try {
            $user = Auth::user();

            // Citas pendientes que expiraron (más de 24 horas sin confirmar)
            $citasPendientesExpiradas = $user->citas()
                ->where('estado', Cita::ESTADO_PENDIENTE)
                ->where('fecha_hora', '<', now())
                ->whereNotLike('observaciones', '%Cita expirada por inacción%')
                ->get();

            foreach ($citasPendientesExpiradas as $cita) {
                $motivoExpiracion = 'Cita expirada por inacción';
                $observaciones = $cita->observaciones
                    ? $cita->observaciones . "\n" . $motivoExpiracion
                    : $motivoExpiracion;

                $cita->update([
                    'estado' => Cita::ESTADO_CANCELADA,
                    'observaciones' => $observaciones
                ]);

                // Crear notificación
                $cita->usuario->notificaciones()->create([
                    'titulo' => 'Cita cancelada por expiración',
                    'mensaje' => "Tu cita para el {$cita->fecha_hora->format('d/m/Y H:i')} fue cancelada automáticamente por no ser confirmada a tiempo.",
                    'tipo' => 'cancelacion',
                    'fecha_envio' => now(),
                    'leido' => false
                ]);

                Log::info("Cita pendiente expirada automáticamente", [
                    'cita_id' => $cita->id,
                    'usuario_id' => $cita->usuario_id,
                    'fecha_hora' => $cita->fecha_hora
                ]);
            }

            // Citas confirmadas que no fueron atendidas (más de 24 horas después de la fecha programada)
            $citasConfirmadasExpiradas = $user->citas()
                ->where('estado', Cita::ESTADO_CONFIRMADA)
                ->where('fecha_hora', '<', now()->subHours(24))
                ->whereNotLike('observaciones', '%Cita no atendida%')
                ->get();

            foreach ($citasConfirmadasExpiradas as $cita) {
                $motivoExpiracion = 'Cita no atendida - Cancelada automáticamente';
                $observaciones = $cita->observaciones
                    ? $cita->observaciones . "\n" . $motivoExpiracion
                    : $motivoExpiracion;

                $cita->update([
                    'estado' => Cita::ESTADO_CANCELADA,
                    'observaciones' => $observaciones
                ]);

                // Crear notificación
                /*    $cita->usuario->notificaciones()->create([
                    'titulo' => 'Cita cancelada - No atendida',
                    'mensaje' => "Tu cita para el {$cita->fecha_hora->format('d/m/Y H:i')} fue marcada como no atendida y cancelada automáticamente.",
                    'tipo' => 'cancelacion',
                    'fecha_envio' => now(),
                    'leido' => false
                ]);*/

                Log::info("Cita confirmada no atendida cancelada automáticamente", [
                    'cita_id' => $cita->id,
                    'usuario_id' => $cita->usuario_id,
                    'fecha_hora' => $cita->fecha_hora
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error al procesar citas expiradas', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'usuario_id' => Auth::id()
            ]);
        }
    }
    /**
     * Obtener horarios disponibles para una fecha específica
     */
    public function getHorariosDisponibles($fecha, Request $request)
    {
        $excludeCitaId = $request->query('exclude');

        try {
            $fechaCarbon = Carbon::parse($fecha);

            // Verificar si es domingo
            if ($fechaCarbon->isSunday()) {
                return [];
            }

            // Verificar día no laborable
            if (DiaNoLaborable::whereDate('fecha', $fechaCarbon)->exists()) {
                return [];
            }

            // Obtener día de la semana en formato ISO (1=Lunes, 7=Domingo)
            $diaSemana = $fechaCarbon->dayOfWeekIso;

            // Obtener horarios programados para este día
            $horarios = Horario::where('dia_semana', $diaSemana)
                ->where('activo', true)
                ->orderBy('hora_inicio')
                ->get();

            if ($horarios->isEmpty()) {
                return [];
            }

            //  MODIFICAR CONSULTA PARA EXCLUIR CITA SI EXISTE
            $query = Cita::whereDate('fecha_hora', $fechaCarbon)
                ->where('estado', '!=', 'cancelada');

            if ($excludeCitaId) {
                $query->where('id', '!=', $excludeCitaId);
            }

            $horariosOcupados = $query->with('servicios')
                ->get()
                ->map(function ($cita) {
                    $horaInicio = Carbon::parse($cita->fecha_hora);
                    $duracionTotal = $cita->servicios->sum('duracion_min');
                    return [
                        'inicio' => $horaInicio->format('H:i'),
                        'fin' => $horaInicio->copy()->addMinutes($duracionTotal)->format('H:i')
                    ];
                });

            // Generar horarios disponibles cada 30 minutos
            $horariosDisponibles = [];

            foreach ($horarios as $horario) {
                $horaInicio = Carbon::parse($horario->hora_inicio);
                $horaFin = Carbon::parse($horario->hora_fin);

                $horaActual = $horaInicio->copy();

                while ($horaActual < $horaFin) {
                    $horaStr = $horaActual->format('H:i');

                    // Verificar si el horario está ocupado
                    $estaOcupado = false;
                    foreach ($horariosOcupados as $ocupado) {
                        $horaCita = Carbon::createFromFormat('H:i', $ocupado['inicio']);
                        $horaFinCita = Carbon::createFromFormat('H:i', $ocupado['fin']);
                        $horaActualCarbon = Carbon::createFromFormat('H:i', $horaStr);

                        if ($horaActualCarbon->between($horaCita, $horaFinCita, false)) {
                            $estaOcupado = true;
                            break;
                        }
                    }

                    $horariosDisponibles[] = [
                        'hora' => $horaStr,
                        'disponible' => !$estaOcupado
                    ];

                    $horaActual->addMinutes(30);
                }
            }

            // Ordenar por hora
            usort($horariosDisponibles, function ($a, $b) {
                return strcmp($a['hora'], $b['hora']);
            });

            return $horariosDisponibles;
        } catch (\Exception $e) {
            Log::error('Error en getHorariosDisponibles: ' . $e->getMessage());
            return [];
        }
    }

    public function verificarDiaNoLaborable(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date'
        ]);

        $fecha = Carbon::parse($request->fecha);

        $diaNoLaborable = DiaNoLaborable::whereDate('fecha', $fecha)->first();

        if ($diaNoLaborable) {
            $motivosDisponibles = DiaNoLaborable::getMotivosDisponibles();
            $motivoTexto = $motivosDisponibles[$diaNoLaborable->motivo] ?? $diaNoLaborable->motivo;

            return response()->json([
                'es_no_laborable' => true,
                'motivo' => $motivoTexto,
                'fecha' => $fecha->format('Y-m-d')
            ]);
        }

        return response()->json([
            'es_no_laborable' => false,
            'fecha' => $fecha->format('Y-m-d')
        ]);
    }
    /**
     * Obtener horarios disponibles para una fecha específica 
     */
    public function getHorariosDisponiblesPorFecha($fecha, Request $request)
    {
        try {
            $excludeCitaId = $request->query('exclude');

            // Llamar al método privado existente
            $horariosDisponibles = $this->getAvailableTimes($fecha, $excludeCitaId);

            // Convertir a formato esperado por el frontend
            $horariosFormatted = collect($horariosDisponibles)->map(function ($hora) {
                return [
                    'hora' => $hora,
                    'disponible' => true
                ];
            });

            Log::info("Horarios disponibles para fecha {$fecha}:", [
                'exclude' => $excludeCitaId,
                'horarios_count' => $horariosFormatted->count(),
                'horarios' => $horariosFormatted->toArray()
            ]);

            return response()->json($horariosFormatted);
        } catch (\Exception $e) {
            Log::error('Error en getHorariosDisponiblesPorFecha:', [
                'fecha' => $fecha,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([], 500);
        }
    }

    /**
     * Mostrar listado de facturas del cliente (CITAS FINALIZADAS CON PAGO)
     */
    public function facturas(Request $request)
    {
        $user = auth()->user();

        // Obtener citas finalizadas que tengan pago
        $query = $user->citas()
            ->with(['servicios', 'vehiculo', 'pago'])
            ->where('estado', Cita::ESTADO_FINALIZADA)
            ->whereHas('pago');

        // Aplicar filtros si existen
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_hora', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_hora', '<=', $request->fecha_hasta);
        }

        if ($request->filled('vehiculo_id')) {
            $query->where('vehiculo_id', $request->vehiculo_id);
        }

        // Estadísticas para mostrar
        $citasFiltradas = (clone $query)->get();

        $estadisticas = [
            'total_facturas' => $citasFiltradas->count(),
            'total_pagado' => $citasFiltradas->sum(function ($cita) {
                return $cita->pago ? $cita->pago->monto : 0;
            }),
            'facturas_este_mes' => $citasFiltradas->where('fecha_hora', '>=', now()->startOfMonth())->count(),
            'promedio_por_factura' => $citasFiltradas->count() > 0 ?
                $citasFiltradas->sum(function ($cita) {
                    return $cita->pago ? $cita->pago->monto : 0;
                }) / $citasFiltradas->count() : 0,
        ];

        $facturas = $query->orderBy('fecha_hora', 'desc')->paginate(10);

        // Obtener vehículos del usuario para filtros
        $vehiculos = $user->vehiculos()->get();

        return view('cliente.facturas', compact('facturas', 'estadisticas', 'vehiculos'));
    }

    /**
     * Ver detalle de una factura específica
     */
    public function verFactura(Cita $cita)
    {
        // Verificar que la cita pertenece al usuario
        if ($cita->usuario_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para ver esta factura'
            ], 403);
        }

        // Verificar que la cita es facturable (finalizada y con pago)
        if ($cita->estado !== Cita::ESTADO_FINALIZADA || !$cita->pago) {
            return response()->json([
                'success' => false,
                'message' => 'Esta cita no tiene una factura disponible'
            ], 404);
        }

        // Calcular total de servicios
        $totalServicios = $cita->servicios->sum('precio');

        return response()->json([
            'success' => true,
            'factura' => [
                'id' => $cita->id,
                'numero' => 'FACT-' . str_pad($cita->id, 6, '0', STR_PAD_LEFT),
                'fecha_emision' => now()->format('d/m/Y'),
                'fecha_servicio' => $cita->fecha_hora->format('d/m/Y'),
                'hora_servicio' => $cita->fecha_hora->format('h:i A'),
                'estado' => 'Completada',
                'cliente_nombre' => $cita->usuario->nombre,
                'cliente_email' => $cita->usuario->email,
                'cliente_telefono' => $cita->usuario->telefono,
                'vehiculo_marca' => $cita->vehiculo->marca,
                'vehiculo_modelo' => $cita->vehiculo->modelo,
                'vehiculo_placa' => $cita->vehiculo->placa,
                'vehiculo_color' => $cita->vehiculo->color,
                'vehiculo_tipo' => $cita->vehiculo->tipo_formatted ?? ucfirst($cita->vehiculo->tipo),
                'servicios' => $cita->servicios->map(function ($servicio) {
                    return [
                        'nombre' => $servicio->nombre,
                        'descripcion' => $servicio->descripcion,
                        'precio' => $servicio->precio,
                        'duracion' => $servicio->duracion_min
                    ];
                }),
                'subtotal' => $totalServicios,
                'total' => $cita->pago->monto,
                'metodo_pago' => $this->getMetodoPagoFormatted($cita->pago->metodo),
                'referencia_pago' => $cita->pago->referencia,
                'estado_pago' => $this->getEstadoPagoFormatted($cita->pago->estado),
                'fecha_pago' => $cita->pago->fecha_pago ? $cita->pago->fecha_pago->format('d/m/Y H:i') : 'N/A',
                'observaciones' => $cita->observaciones
            ]
        ]);
    }

    /**
     * Descargar factura en PDF
     */
    public function descargarFactura(Cita $cita)
    {
        // Verificar que la cita pertenece al usuario
        if ($cita->usuario_id !== auth()->id()) {
            abort(403, 'No tienes permiso para descargar esta factura');
        }

        // Verificar que la cita es facturable
        if ($cita->estado !== Cita::ESTADO_FINALIZADA || !$cita->pago) {
            abort(404, 'Esta cita no tiene una factura disponible');
        }

        // Cargar todas las relaciones necesarias
        $cita->load(['usuario', 'vehiculo', 'servicios', 'pago']);

        // Generar PDF
        $pdf = PDF::loadView('cliente.factura-pdf', compact('cita'));

        // Descargar con nombre personalizado
        $fileName = 'factura-' . $cita->id . '-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Obtener método de pago formateado
     */
    private function getMetodoPagoFormatted($metodo)
    {
        $metodos = [
            'efectivo' => 'Efectivo',
            'transferencia' => 'Transferencia Bancaria',
            'pasarela' => 'Pasarela de Pago'
        ];

        return $metodos[$metodo] ?? $metodo;
    }

    /**
     * Obtener estado de pago formateado
     */
    private function getEstadoPagoFormatted($estado)
    {
        $estados = [
            'pendiente' => 'Pendiente',
            'pagado' => 'Pagado',
            'rechazado' => 'Rechazado'
        ];

        return $estados[$estado] ?? $estado;
    }

    public function debugHorarios(Request $request)
    {
        try {
            $request->validate([
                'fecha' => 'required|date',
                'hora' => 'required|date_format:H:i',
                'duracion' => 'required|integer|min:1'
            ]);

            $fecha = $request->fecha;
            $hora = $request->hora;
            $duracion = $request->duracion;

            $fechaCita = Carbon::parse("$fecha $hora");
            $horaFin = $fechaCita->copy()->addMinutes($duracion);

            // Verificar colisión con otras citas
            $citasQuery = Cita::where('estado', '!=', 'cancelada')
                ->whereDate('fecha_hora', $fechaCita->format('Y-m-d'))
                ->where(function ($query) use ($fechaCita, $horaFin) {
                    $query->where(function ($q) use ($fechaCita, $horaFin) {
                        // Citas que empiezan durante la nueva cita
                        $q->where('fecha_hora', '>=', $fechaCita)
                            ->where('fecha_hora', '<', $horaFin);
                    })->orWhere(function ($q) use ($fechaCita, $horaFin) {
                        // Citas que terminan durante la nueva cita
                        $q->whereRaw('DATE_ADD(fecha_hora, INTERVAL (
                        SELECT SUM(servicios.duracion_min)
                        FROM cita_servicio
                        JOIN servicios ON cita_servicio.servicio_id = servicios.id
                        WHERE cita_servicio.cita_id = citas.id
                    ) MINUTE) > ?', [$fechaCita])
                            ->where('fecha_hora', '<=', $fechaCita);
                    });
                });

            $citasSuperpuestas = $citasQuery->with(['servicios', 'vehiculo'])->get();

            // También obtener horarios disponibles para comparar
            $horariosDisponibles = $this->getAvailableTimes($fecha);

            return response()->json([
                'success' => true,
                'data' => [
                    'fecha_cita' => $fechaCita->format('Y-m-d H:i:s'),
                    'hora_fin' => $horaFin->format('Y-m-d H:i:s'),
                    'duracion_minutos' => $duracion,
                    'citas_superpuestas' => $citasSuperpuestas->map(function ($cita) {
                        return [
                            'id' => $cita->id,
                            'fecha_hora' => $cita->fecha_hora->format('Y-m-d H:i:s'),
                            'estado' => $cita->estado,
                            'duracion_total' => $cita->servicios->sum('duracion_min'),
                            'servicios' => $cita->servicios->pluck('nombre'),
                            'vehiculo' => $cita->vehiculo->marca . ' ' . $cita->vehiculo->modelo,
                            'hora_fin_cita' => $cita->fecha_hora->copy()
                                ->addMinutes($cita->servicios->sum('duracion_min'))
                                ->format('H:i:s')
                        ];
                    }),
                    'total_citas_superpuestas' => $citasSuperpuestas->count(),
                    'horarios_disponibles' => $horariosDisponibles,
                    'query_explicacion' => 'Buscando citas que se superponen con el rango: ' .
                        $fechaCita->format('H:i') . ' - ' . $horaFin->format('H:i')
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error en debugHorarios: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error en debug: ' . $e->getMessage()
            ], 500);
        }
    }

    public function debugFechas(Request $request)
    {
        $fecha = $request->query('fecha', now()->format('Y-m-d'));

        try {
            // Crear fecha con Carbon en timezone local
            $fechaCarbon = \Carbon\Carbon::createFromFormat('Y-m-d', $fecha, config('app.timezone', 'America/El_Salvador'))->startOfDay();

            // Información de debug
            $debug = [
                'fecha_original' => $fecha,
                'fecha_carbon' => $fechaCarbon->toDateString(),
                'fecha_carbon_formatted' => $fechaCarbon->format('Y-m-d H:i:s'), // SIN timezone para frontend
                'dia_semana_js' => $fechaCarbon->dayOfWeek, // 0=Domingo, 1=Lunes... 6=Sábado
                'dia_semana_iso' => $fechaCarbon->dayOfWeekIso, // 1=Lunes, 2=Martes... 7=Domingo
                'nombre_dia' => $fechaCarbon->locale('es')->dayName,
                'es_domingo_js' => $fechaCarbon->dayOfWeek === 0,
                'es_domingo_iso' => $fechaCarbon->dayOfWeekIso === 7,
                'timezone' => $fechaCarbon->timezone->getName()
            ];

            // Obtener horarios programados
            $horariosDisponibles = \App\Models\Horario::where('activo', true)->get();

            $debug['horarios_bd'] = $horariosDisponibles->map(function ($horario) {
                return [
                    'id' => $horario->id,
                    'dia_semana' => $horario->dia_semana,
                    'nombre_dia' => $this->getNombreDiaISO($horario->dia_semana),
                    'hora_inicio' => $horario->hora_inicio->format('H:i'),
                    'hora_fin' => $horario->hora_fin->format('H:i'),
                    'activo' => $horario->activo
                ];
            });

            // Verificar qué horarios coinciden con la fecha seleccionada
            $horariosCoincidentes = $horariosDisponibles->where('dia_semana', $fechaCarbon->dayOfWeekIso);

            $debug['horarios_coincidentes'] = $horariosCoincidentes->map(function ($horario) {
                return [
                    'id' => $horario->id,
                    'dia_semana' => $horario->dia_semana,
                    'hora_inicio' => $horario->hora_inicio->format('H:i'),
                    'hora_fin' => $horario->hora_fin->format('H:i')
                ];
            });

            return response()->json($debug);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    private function getNombreDiaISO($diaISO)
    {
        $dias = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo'
        ];

        return $dias[$diaISO] ?? 'Desconocido';
    }
    /**
     * Método para debugging - Mostrar citas de cualquier usuario en JSON
     */
    public function debugCitasUsuarioJson($usuarioId)
    {
        // Validar que el usuario que hace la solicitud sea admin
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            return response()->json([
                'error' => true,
                'message' => 'Acceso no autorizado'
            ], 403);
        }

        try {
            // Obtener el usuario
            $usuario = Usuario::findOrFail($usuarioId);

            // Obtener todas las citas del usuario con relaciones
            $citas = Cita::where('usuario_id', $usuarioId)
                ->with(['vehiculo', 'servicios', 'usuario'])
                ->orderBy('fecha_hora', 'desc')
                ->get();

            // Formatear los datos para la respuesta JSON
            $response = [
                'usuario' => [
                    'id' => $usuario->id,
                    'nombre' => $usuario->nombre,
                    'email' => $usuario->email,
                    'estado' => $usuario->estado,
                ],
                'estadisticas' => [
                    'total_citas' => $citas->count(),
                    'pendientes' => $citas->where('estado', 'pendiente')->count(),
                    'confirmadas' => $citas->where('estado', 'confirmada')->count(),
                    'en_proceso' => $citas->where('estado', 'en_proceso')->count(),
                    'finalizadas' => $citas->where('estado', 'finalizada')->count(),
                    'canceladas' => $citas->where('estado', 'cancelada')->count(),
                ],
                'citas' => $citas->map(function ($cita) {
                    return [
                        'id' => $cita->id,
                        'fecha_hora' => $cita->fecha_hora->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                        'fecha_hora_formateada' => $cita->fecha_hora->setTimezone(config('app.timezone'))->isoFormat('dddd D [de] MMMM [de] YYYY, h:mm A'),
                        'estado' => $cita->estado,
                        'vehiculo' => [
                            'id' => $cita->vehiculo->id,
                            'marca' => $cita->vehiculo->marca,
                            'modelo' => $cita->vehiculo->modelo,
                            'placa' => $cita->vehiculo->placa,
                            'tipo' => $cita->vehiculo->tipo,
                        ],
                        'servicios' => $cita->servicios->map(function ($servicio) {
                            return [
                                'id' => $servicio->id,
                                'nombre' => $servicio->nombre,
                                'precio' => $servicio->precio,
                                'duracion_min' => $servicio->duracion_min,
                            ];
                        }),
                        'duracion_total' => $cita->servicios->sum('duracion_min'),
                        'precio_total' => $cita->servicios->sum('precio'),
                        'observaciones' => $cita->observaciones,
                        'created_at' => $cita->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $cita->updated_at->format('Y-m-d H:i:s'),
                    ];
                }),
                'meta' => [
                    'fecha_consulta' => now()->toDateTimeString(),
                    'total_registros' => $citas->count(),
                ]
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Obtener todos los servicios disponibles para el modal "Ver todos"
     */
    public function getAllServicios()
    {
        try {
            $servicios = Servicio::activos()
                ->select('id', 'nombre', 'descripcion', 'precio', 'duracion_min', 'categoria', 'activo')
                ->orderBy('categoria')
                ->orderBy('nombre')
                ->get()
                ->map(function ($servicio) {
                    return [
                        'id' => $servicio->id,
                        'nombre' => $servicio->nombre,
                        'descripcion' => $servicio->descripcion,
                        'precio' => number_format($servicio->precio, 2),
                        'duracion_min' => $servicio->duracion_min,
                        'duracion_formatted' => $servicio->getDuracionFormattedAttribute(),
                        'categoria' => $servicio->categoria,
                        'activo' => $servicio->activo
                    ];
                });

            return response()->json($servicios);
        } catch (\Exception $e) {
            Log::error('Error al obtener todos los servicios:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => true,
                'message' => 'Error al cargar los servicios'
            ], 500);
        }
    }
}
