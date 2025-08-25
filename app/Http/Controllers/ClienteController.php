<?php
// app/Http/Controllers/ClienteController.php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Vehiculo;
use App\Models\Servicio;
use App\Models\Usuario;
use App\Models\DiaNoLaborable;
use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClienteController extends Controller
{
    public function dashboard(): View
    {
        $user = Auth::user();

        if (!$user || !$user->isCliente()) {
            abort(403, 'Acceso no autorizado');
        }

        try {

            $this->procesarCitasExpiradas();

            $vehiculos = $user->vehiculos()
                ->withCount('citas')
                ->orderByDesc('citas_count')
                ->get();

            $vehiculosDashboard = $vehiculos->take(3);

            // Obtener todas las citas del usuario
            $citas = $user->citas()
                ->with(['vehiculo', 'servicios'])
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

            // Obtener próximos días no laborables para mostrar en dashboard
            $proximosDiasNoLaborables = DiaNoLaborable::getProximosNoLaborables(3);
            return view('cliente.dashboard', [
                'user' => $user,
                'stats' => [
                    'total_vehiculos' => $vehiculos->count(),
                    'total_citas' => $citas->count(),
                    'citas_pendientes' => $citas->where('estado', 'pendiente')->count(),
                    'citas_confirmadas' => $citas->where('estado', 'confirmada')->count(),
                ],
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
                'trace' => $e->getTraceAsString()
            ]);

            return view('cliente.dashboard', [
                'user' => $user,
                'stats' => [
                    'total_vehiculos' => 0,
                    'total_citas' => 0,
                    'citas_pendientes' => 0,
                    'citas_confirmadas' => 0,
                ],
                'mis_vehiculos' => collect(),
                'mis_citas' => collect(),
                'proximas_citas' => collect(),
                'historial_citas' => collect(),
                'proximos_dias_no_laborables' => collect(),
                'notificaciones' => collect(),
                'notificacionesNoLeidas' => 0,
                'servicios' => collect()
            ]);
        }
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
        $citas = $query->orderBy('fecha_hora', 'desc')->paginate(10);

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

            $validated = $request->validate([
                'vehiculo_id' => 'required|exists:vehiculos,id,usuario_id,' . Auth::id(),
                'fecha_hora' => [ // ✅ Cambiado a fecha_hora
                    'required',
                    'date',
                    'after:now',
                    function ($attribute, $value, $fail) {
                        // Validar que no sea domingo
                        $fecha = Carbon::parse($value);
                        if ($fecha->dayOfWeek === 0) {
                            $fail('No se pueden agendar citas los domingos.');
                        }

                        // Validar que no sea un día no laborable usando el modelo
                        if (DiaNoLaborable::esNoLaborable($value)) {
                            $diaNoLaborable = DiaNoLaborable::whereDate('fecha', $fecha->format('Y-m-d'))->first();
                            $motivosDisponibles = DiaNoLaborable::getMotivosDisponibles();
                            $motivoTexto = $motivosDisponibles[$diaNoLaborable->motivo] ?? $diaNoLaborable->motivo;
                            $fail("No se pueden agendar citas este día. Motivo: {$motivoTexto}");
                        }

                        // Validar horario laboral
                        $diaSemana = $fecha->dayOfWeek;
                        $diaSemanaDB = $diaSemana === 0 ? 7 : $diaSemana;

                        $horario = Horario::where('dia_semana', $diaSemanaDB)
                            ->where('activo', true)
                            ->first();

                        if (!$horario) {
                            $fail('No hay horarios de atención configurados para este día.');
                            return;
                        }

                        $horaSeleccionada = $fecha->format('H:i');
                        $horaInicio = $horario->hora_inicio->format('H:i');
                        $horaFin = $horario->hora_fin->format('H:i');

                        if ($horaSeleccionada < $horaInicio || $horaSeleccionada > $horaFin) {
                            $fail("El horario debe estar entre {$horaInicio} y {$horaFin}.");
                        }
                    },
                ],
                'servicios' => 'required|array|min:1',
                'servicios.*' => 'exists:servicios,id',
                'observaciones' => 'nullable|string|max:500'
            ]);

            // Parsear fecha y hora (ya vienen combinadas)
            $fechaCita = Carbon::parse($validated['fecha_hora'], config('app.timezone'));

            // Validar que la fecha y hora no sean en el pasado
            if ($fechaCita->lt(now())) {
                throw new \Exception('No puedes agendar citas en fechas u horas pasadas.', 400);
            }

            // Validar 1 mes de anticipación
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

            // Verificar que la cita termine dentro del horario laboral
            $diaSemana = $fechaCita->dayOfWeek === 0 ? 7 : $fechaCita->dayOfWeek;
            $horario = Horario::where('dia_semana', $diaSemana)->where('activo', true)->first();

            if ($horario && $horaFin->gt(Carbon::parse($fechaCita->format('Y-m-d') . ' ' . $horario->hora_fin->format('H:i:s')))) {
                return response()->json([
                    'message' => 'Los servicios seleccionados no pueden completarse antes del cierre.',
                    'duracion_total' => $duracionTotal,
                    'hora_cierre' => $horario->hora_fin->format('H:i')
                ], 400);
            }

            // Verificar colisión con otras citas (excluir cita actual si existe)
            $citasQuery = Cita::where('estado', '!=', 'cancelada')
                ->where(function ($query) use ($fechaCita, $horaFin) {
                    $query->whereBetween('fecha_hora', [$fechaCita, $horaFin])
                        ->orWhere(function ($q) use ($fechaCita, $horaFin) {
                            $q->where('fecha_hora', '<', $fechaCita)
                                ->whereRaw('DATE_ADD(fecha_hora, INTERVAL (
                            SELECT SUM(servicios.duracion_min)
                            FROM cita_servicio
                            JOIN servicios ON cita_servicio.servicio_id = servicios.id
                            WHERE cita_servicio.cita_id = citas.id
                        ) MINUTE) > ?', [$fechaCita]);
                        })
                        ->orWhere(function ($q) use ($fechaCita, $horaFin) {
                            $q->where('fecha_hora', '>', $fechaCita)
                                ->where('fecha_hora', '<', $horaFin);
                        });
                });

            // Excluir la cita actual si se está editando
            if ($request->has('cita_id') && $request->cita_id) {
                $citasQuery->where('id', '!=', $request->cita_id);
            }

            $citasSuperpuestas = $citasQuery->exists();

            if ($citasSuperpuestas) {
                $horariosDisponibles = $this->getAvailableTimes($fechaCita->format('Y-m-d'), $request->cita_id ?? null);
                return response()->json([
                    'message' => 'Existe un conflicto de horario con otra cita.',
                    'horarios_disponibles' => $horariosDisponibles,
                    'duracion_total' => $duracionTotal
                ], 409);
            }

            DB::beginTransaction();

            // Crear la cita
            $cita = Cita::create([
                'usuario_id' => Auth::id(),
                'vehiculo_id' => $validated['vehiculo_id'],
                'fecha_hora' => $fechaCita,
                'estado' => Cita::ESTADO_PENDIENTE,
                'observaciones' => $validated['observaciones'] ?? null
            ]);

            $serviciosConPrecio = $servicios->mapWithKeys(function ($servicio) {
                return [$servicio->id => [
                    'precio' => $servicio->precio
                ]];
            });

            $cita->servicios()->attach($serviciosConPrecio);

            // Notificación
            $cita->usuario->notificaciones()->create([
                'titulo' => 'Cita agendada exitosamente',
                'mensaje' => "Tu cita para el {$fechaCita->format('d/m/Y')} a las {$fechaCita->format('H:i')} ha sido agendada. Servicios: " . $servicios->pluck('nombre')->join(', '),
                'tipo' => 'confirmacion',
                'fecha_envio' => now(),
                'leido' => false
            ]);

            // Registrar en bitácora
            \App\Models\Bitacora::create([
                'usuario_id' => Auth::id(),
                'accion' => 'crear',
                'tabla_afectada' => 'citas',
                'registro_id' => $cita->id,
                'detalles' => "Creó cita para {$fechaCita->format('d/m/Y H:i')} - Vehículo: {$vehiculo->marca} {$vehiculo->modelo}"
            ]);

            DB::commit();

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
                'message' => $e->getMessage() ?: 'Error interno del servidor. Por favor, inténtalo de nuevo.',
                'error_type' => get_class($e)
            ], $statusCode);
        }
    }

    private function getAvailableTimes($date, $excludeCitaId = null)
    {
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

        // Obtener horarios ocupados excluyendo cita específica
        $query = Cita::whereDate('fecha_hora', $date)
            ->where('estado', '!=', 'cancelada')
            ->with('servicios');

        if ($excludeCitaId) {
            $query->where('id', '!=', $excludeCitaId);
            Log::info("Excluyendo cita ID: {$excludeCitaId}");
        }

        $horariosOcupados = $query->get()->map(function ($cita) {
            $horaInicio = \Carbon\Carbon::parse($cita->fecha_hora);
            $duracionTotal = $cita->servicios->sum('duracion_min');
            return [
                'inicio' => $horaInicio,
                'fin' => $horaInicio->copy()->addMinutes($duracionTotal)
            ];
        });

        // Obtener horarios programados para este día ISO
        $horariosDisponibles = \App\Models\Horario::where('dia_semana', $dayOfWeekISO)
            ->where('activo', true)
            ->get();

        Log::info("Horarios programados para día ISO {$dayOfWeekISO}:", [
            'count' => $horariosDisponibles->count(),
            'horarios' => $horariosDisponibles->pluck('hora_inicio', 'hora_fin')->toArray()
        ]);

        if ($horariosDisponibles->isEmpty()) {
            Log::info("No hay horarios programados para este día");
            return [];
        }

        // Generar horarios disponibles
        $horariosLibres = [];

        foreach ($horariosDisponibles as $horario) {
            $horaActual = $date->copy()
                ->setTimezone(config('app.timezone'))
                ->setTimeFromTimeString($horario->hora_inicio->format('H:i:s'));
            $horaCierre = $date->copy()->setTimeFromTimeString($horario->hora_fin->format('H:i:s'));

            while ($horaActual->lt($horaCierre)) {
                $horaFin = $horaActual->copy()->addMinutes(30);

                // Verificar si hay colisión con horarios ocupados
                $disponible = true;
                foreach ($horariosOcupados as $ocupado) {
                    if ($horaActual->lt($ocupado['fin']) && $horaFin->gt($ocupado['inicio'])) {
                        $disponible = false;
                        break;
                    }
                }

                if ($disponible && $horaFin->lte($horaCierre)) {
                    $horariosLibres[] = $horaActual->format('H:i');
                }

                $horaActual->addMinutes(30);
            }
        }

        // Si la fecha es hoy, filtrar horarios que ya pasaron
        if ($date->isToday()) {
            $horaActual = \Carbon\Carbon::now();
            $horariosLibres = array_filter($horariosLibres, function ($hora) use ($horaActual) {
                $horaCita = \Carbon\Carbon::createFromFormat('H:i', $hora);
                return $horaCita->gt($horaActual);
            });

            // Reindexar el array
            $horariosLibres = array_values($horariosLibres);

            Log::info("Horarios disponibles después de filtrar los pasados para hoy:", [
                'count' => count($horariosLibres),
                'horarios' => $horariosLibres
            ]);
        }

        Log::info("Horarios libres generados:", [
            'count' => count($horariosLibres),
            'horarios' => $horariosLibres
        ]);

        return $horariosLibres;
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
                'accion' => 'cancelar',
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
    public function edit(Cita $cita)
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
                'horarios_disponibles' => $this->getHorariosDisponibles($cita->fecha_hora->format('Y-m-d')),
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

            // Verificar colisión excluyendo esta cita
            $citasSuperpuestas = Cita::where('estado', '!=', 'cancelada')
                ->where('id', '!=', $cita->id) // EXCLUSIÓN EXPLÍCITA
                ->where(function ($query) use ($fechaCita, $horaFin) {
                    $query->whereBetween('fecha_hora', [$fechaCita, $horaFin])
                        ->orWhere(function ($q) use ($fechaCita, $horaFin) {
                            $q->where('fecha_hora', '<', $fechaCita)
                                ->whereRaw('DATE_ADD(fecha_hora, INTERVAL (
                    SELECT SUM(servicios.duracion_min)
                    FROM cita_servicio
                    JOIN servicios ON cita_servicio.servicio_id = servicios.id
                    WHERE cita_servicio.cita_id = citas.id
                ) MINUTE) > ?', [$fechaCita]);
                        })
                        ->orWhere(function ($q) use ($fechaCita, $horaFin) {
                            $q->where('fecha_hora', '>', $fechaCita)
                                ->where('fecha_hora', '<', $horaFin);
                        });
                })
                ->exists();

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

            // Validar formato de fecha y crear Carbon instance sin problemas de timezone
            try {
                $fechaCarbon = \Carbon\Carbon::createFromFormat('Y-m-d', $fecha)->startOfDay();
                Log::info("Fecha procesada correctamente:", [
                    'fecha_input' => $fecha,
                    'fecha_carbon' => $fechaCarbon->toDateString(),
                    'dia_semana' => $fechaCarbon->dayOfWeek,
                    'dia_semana_iso' => $fechaCarbon->dayOfWeekIso,
                    'nombre_dia' => $fechaCarbon->locale('es')->dayName
                ]);
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
                Log::info("Excluyendo cita ID: {$excludeCitaId} para fecha: {$fecha}");
            }

            $citas = $query->get();

            $horariosOcupados = $citas->map(function ($cita) {
                $horaInicio = \Carbon\Carbon::parse($cita->fecha_hora);
                $duracionTotal = $cita->servicios->sum('duracion_min') ?: 30; // Default 30 min

                return [
                    'cita_id' => $cita->id, // Para debug
                    'hora_inicio' => $horaInicio->format('H:i'),
                    'duracion' => $duracionTotal,
                    'hora_fin' => $horaInicio->copy()->addMinutes($duracionTotal)->format('H:i'),
                    'cita_id' => $cita->id,
                    'cliente' => $cita->usuario->nombre ?? 'N/A'
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
            ->with(['servicios', 'vehiculo'])
            ->whereIn('estado', [Cita::ESTADO_FINALIZADA, Cita::ESTADO_CANCELADA]);

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

        if ($request->filled('vehiculo_id')) {
            $query->where('vehiculo_id', $request->vehiculo_id);
        }

        // ORDENAR POR FECHA MÁS RECIENTE PRIMERO (orden cronológico descendente)
        $citas = $query->orderBy('fecha_hora', 'desc')->paginate(15);

        // Mantener los parámetros de filtro en la paginación
        $citas->appends($request->query());

        return view('cliente.historial', [
            'citas' => $citas,
            'user' => $user
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
    public function getHorariosDisponibles($fecha, Request $request) // Agregar Request $request
    {
        $excludeCitaId = $request->query('exclude'); //  Obtener parámetro exclude

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
}
