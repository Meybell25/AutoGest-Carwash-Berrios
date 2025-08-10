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

            // Filtrar citas próximas (futuras y con estados específicos)
            $proximas_citas = $citas->filter(function ($cita) {
                return $cita->fecha_hora >= now() &&
                    in_array($cita->estado, ['pendiente', 'confirmada', 'en_proceso']);
            });

            // Filtrar historial (pasadas o con estados finalizados)
            $historial_citas = $citas->filter(function ($cita) {
                return $cita->fecha_hora < now() ||
                    in_array($cita->estado, ['finalizada', 'cancelada']);
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
                'proximas_citas' => $proximas_citas->take(5),
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

    public function citas(): View
    {
        $citas = Cita::where('usuario_id', Auth::id())
            ->with(['vehiculo', 'servicios'])
            ->orderBy('fecha_hora', 'desc')
            ->paginate(10);

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
                'fecha' => [
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
                            $diaNoLaborable = DiaNoLaborable::where('fecha', $fecha->format('Y-m-d'))->first();
                            $motivosDisponibles = DiaNoLaborable::getMotivosDisponibles();
                            $motivoTexto = $motivosDisponibles[$diaNoLaborable->motivo] ?? $diaNoLaborable->motivo;
                            $fail("No se pueden agendar citas este día. Motivo: {$motivoTexto}");
                        }
                    },
                ],
                'hora' => [
                    'required',
                    'date_format:H:i',
                    function ($attribute, $value, $fail) use ($request) {
                        // Validar horario laboral usando el modelo Horario
                        $fecha = Carbon::parse($request->fecha);
                        $diaSemana = $fecha->dayOfWeek;

                        // Convertir domingo (0) a 7 para coincidir con la BD
                        $diaSemanaDB = $diaSemana === 0 ? 7 : $diaSemana;

                        $horario = Horario::where('dia_semana', $diaSemanaDB)
                            ->where('activo', true)
                            ->first();

                        if (!$horario) {
                            $fail('No hay horarios de atención configurados para este día.');
                            return;
                        }

                        $horaSeleccionada = Carbon::parse($value);
                        $horaInicio = Carbon::parse($horario->hora_inicio);
                        $horaFin = Carbon::parse($horario->hora_fin);

                        if ($horaSeleccionada->lt($horaInicio) || $horaSeleccionada->gt($horaFin)) {
                            $fail("El horario debe estar entre {$horario->hora_inicio} y {$horario->hora_fin}.");
                        }
                    },
                ],
                'servicios' => 'required|array|min:1',
                'servicios.*' => 'exists:servicios,id',
                'observaciones' => 'nullable|string|max:500'
            ]);

            // Combinar fecha y hora correctamente
            $fechaCita = Carbon::parse($validated['fecha'] . ' ' . $validated['hora']);

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

            if ($horario && $horaFin->gt(Carbon::parse($fechaCita->format('Y-m-d') . ' ' . $horario->hora_fin))) {
                return response()->json([
                    'message' => 'Los servicios seleccionados no pueden completarse antes del cierre.',
                    'duracion_total' => $duracionTotal,
                    'hora_cierre' => $horario->hora_fin,
                    'servicios_disponibles' => $this->getServiciosDisponiblesParaHora($fechaCita, $horario->hora_fin)
                ], 400);
            }

            // Verificar colisión con otras citas
            if ($this->existeColisionHorario($fechaCita, $duracionTotal)) {
                return response()->json([
                    'message' => 'Existe un conflicto de horario con otra cita.',
                    'horarios_disponibles' => $this->getHorariosDisponibles($fechaCita->format('Y-m-d')),
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

    /**
     * Verificar si existe colisión de horario con otras citas
     */
    private function existeColisionHorario($fechaCita, $duracionTotal, $citaExcluir = null)
    {
        $horaFin = $fechaCita->copy()->addMinutes($duracionTotal);

        $query = Cita::with('servicios')
            ->whereDate('fecha_hora', $fechaCita->format('Y-m-d'))
            ->where('estado', '!=', 'cancelada');

        if ($citaExcluir) {
            $query->where('id', '!=', $citaExcluir);
        }

        $citasExistentes = $query->get();

        foreach ($citasExistentes as $citaExistente) {
            $horaInicioCitaExistente = Carbon::parse($citaExistente->fecha_hora);
            $duracionCitaExistente = $citaExistente->servicios->sum('duracion_min');
            $horaFinCitaExistente = $horaInicioCitaExistente->copy()->addMinutes($duracionCitaExistente);

            // Verificar superposición
            if ($fechaCita->lt($horaFinCitaExistente) && $horaFin->gt($horaInicioCitaExistente)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Obtener horarios disponibles para una fecha específica
     */
    private function getHorariosDisponibles($fecha)
    {
        $fecha = Carbon::parse($fecha);

        // Verificar si es día laborable
        if ($fecha->dayOfWeek === 0 || DiaNoLaborable::esNoLaborable($fecha)) {
            return [];
        }

        // Obtener horario laboral para ese día
        $diaSemana = $fecha->dayOfWeek === 0 ? 7 : $fecha->dayOfWeek;
        $horario = Horario::where('dia_semana', $diaSemana)->where('activo', true)->first();

        if (!$horario) {
            return [];
        }

        // Obtener citas existentes
        $citasExistentes = Cita::with('servicios')
            ->whereDate('fecha_hora', $fecha)
            ->where('estado', '!=', 'cancelada')
            ->get()
            ->map(function ($cita) {
                $horaInicio = Carbon::parse($cita->fecha_hora);
                $duracion = $cita->servicios->sum('duracion_min');
                return [
                    'inicio' => $horaInicio,
                    'fin' => $horaInicio->copy()->addMinutes($duracion)
                ];
            });

        // Generar horarios disponibles (cada 30 minutos)
        $horariosDisponibles = [];
        $horaActual = $fecha->copy()->setTimeFromTimeString($horario->hora_inicio);
        $horaCierre = $fecha->copy()->setTimeFromTimeString($horario->hora_fin);

        while ($horaActual->lt($horaCierre)) {
            $horaFin = $horaActual->copy()->addMinutes(30); // Mínimo 30 minutos por cita

            // Verificar si este intervalo está disponible
            $disponible = true;
            foreach ($citasExistentes as $ocupado) {
                if ($horaActual->lt($ocupado['fin']) && $horaFin->gt($ocupado['inicio'])) {
                    $disponible = false;
                    break;
                }
            }

            if ($disponible) {
                $horariosDisponibles[] = $horaActual->format('H:i');
            }

            $horaActual->addMinutes(30);
        }

        return $horariosDisponibles;
    }

    /**
     * Obtener servicios disponibles para una hora específica
     */
    private function getServiciosDisponiblesParaHora($fechaHora, $horaCierre)
    {
        $tiempoDisponible = Carbon::parse($horaCierre)->diffInMinutes($fechaHora);

        return Servicio::activos()
            ->where('duracion_min', '<=', $tiempoDisponible)
            ->get(['id', 'nombre', 'duracion_min', 'precio']);
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
        $user = Auth::user();

        if (!$user || !$user->isCliente()) {
            abort(403, 'Acceso no autorizado');
        }

        try {
            $vehiculos = $user->vehiculos()
                ->withCount('citas')
                ->orderByDesc('citas_count')
                ->get();

            $vehiculosDashboard = $vehiculos->take(3);

            // Obtener TODAS las citas del usuario
            $todasLasCitas = $user->citas()
                ->with(['vehiculo', 'servicios'])
                ->orderBy('fecha_hora', 'desc')
                ->get();

            // Filtrar citas próximas
            $proximas_citas = $todasLasCitas->where('fecha_hora', '>=', now())
                ->whereIn('estado', ['pendiente', 'confirmada', 'en_proceso']);

            // Filtrar historial
            $historial_citas = $todasLasCitas->filter(function ($cita) {
                return $cita->fecha_hora < now() ||
                    in_array($cita->estado, ['finalizada', 'cancelada']);
            });

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
                'message' => 'Error al obtener datos del dashboard'
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

        return response()->json([
            'success' => true,
            'cita' => $cita->load(['vehiculo', 'servicios']),
            'servicios' => Servicio::activos()->where('categoria', $cita->vehiculo->tipo)->get(),
            'vehiculos' => Auth::user()->vehiculos,
            'horarios_disponibles' => $this->getHorariosDisponibles($cita->fecha_hora->format('Y-m-d')),
            'dias_no_laborables' => DiaNoLaborable::futuros()->pluck('fecha')->map(function($fecha) {
                return $fecha->format('Y-m-d');
            })
        ]);
    }

    public function updateCita(Request $request, Cita $cita)
    {
        // Forzar servicios como array (incluso si viene como string)
        $request->merge(['servicios' => (array)$request->input('servicios', [])]);

        Log::debug('Datos recibidos para actualizar cita:', $request->all());

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

        // Validación
        $validated = $request->validate([
            'vehiculo_id' => 'required|exists:vehiculos,id,usuario_id,' . Auth::id(),
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required|date_format:H:i',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'required|integer|exists:servicios,id',
            'observaciones' => 'nullable|string|max:500',
            'cita_id' => 'required|exists:citas,id'
        ]);

        try {
            DB::beginTransaction();

            // Actualizar cita
            $fechaCita = Carbon::parse($validated['fecha'] . ' ' . $validated['hora']);
            $cita->update([
                'vehiculo_id' => $validated['vehiculo_id'],
                'fecha_hora' => $fechaCita,
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

            return response()->json([
                'success' => true,
                'message' => 'Cita actualizada exitosamente',
                'data' => [
                    'cita_id' => $cita->id,
                    'fecha_hora' => $fechaCita->format('Y-m-d H:i:s'),
                    'servicios_nombres' => $cita->servicios->pluck('nombre')->join(', '),
                    'vehiculo_marca' => $cita->vehiculo->marca,
                    'vehiculo_modelo' => $cita->vehiculo->modelo,
                    'vehiculo_placa' => $cita->vehiculo->placa ?? ''
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la cita: ' . $e->getMessage(),
                'received_data' => $request->all()
            ], 500);
        }
    }

    public function getHorariosOcupados(Request $request)
    {
        try {
            $fecha = $request->query('fecha');
            $citaExcluir = $request->query('excluir_cita'); // Para excluir una cita al editar

            if (!$fecha) {
                return response()->json(['horariosOcupados' => []]);
            }

            // Verificar si es día laborable
            $fechaCarbon = Carbon::parse($fecha);
            if ($fechaCarbon->dayOfWeek === 0 || DiaNoLaborable::esNoLaborable($fecha)) {
                return response()->json([
                    'horariosOcupados' => [],
                    'es_dia_laborable' => false,
                    'motivo' => $fechaCarbon->dayOfWeek === 0 ? 'Es domingo' : 'Día no laborable'
                ]);
            }

            // Obtener todas las citas para esa fecha con sus servicios
            $query = Cita::with('servicios')
                ->whereDate('fecha_hora', $fecha)
                ->where('estado', '!=', 'cancelada');

            if ($citaExcluir) {
                $query->where('id', '!=', $citaExcluir);
            }

            $citas = $query->get();

            $horariosOcupados = $citas->map(function ($cita) {
                $horaInicio = Carbon::parse($cita->fecha_hora);
                $duracionTotal = $cita->servicios->sum('duracion_min');
                return [
                    'hora_inicio' => $horaInicio->format('H:i'),
                    'duracion' => $duracionTotal,
                    'hora_fin' => $horaInicio->copy()->addMinutes($duracionTotal)->format('H:i'),
                    'cita_id' => $cita->id,
                    'cliente' => $cita->usuario->nombre ?? 'N/A'
                ];
            });

            return response()->json([
                'horariosOcupados' => $horariosOcupados,
                'es_dia_laborable' => true,
                'total_citas' => $citas->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error en getHorariosOcupados', [
                'fecha' => $fecha,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'horariosOcupados' => [],
                'error' => 'Error al obtener horarios'
            ], 500);
        }
    }

    /**
     * Verificar disponibilidad de una fecha y hora específica
     */
    public function verificarDisponibilidad(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date|after:now',
            'hora' => 'required|date_format:H:i',
            'duracion' => 'required|integer|min:15',
            'cita_excluir' => 'nullable|integer'
        ]);

        $fechaHora = Carbon::parse($request->fecha . ' ' . $request->hora);

        // Verificaciones básicas
        $verificaciones = [
            'es_domingo' => $fechaHora->dayOfWeek === 0,
            'es_dia_no_laborable' => DiaNoLaborable::esNoLaborable($request->fecha),
            'esta_en_horario_laboral' => $this->estaEnHorarioLaboral($fechaHora),
            'tiene_colision' => $this->existeColisionHorario($fechaHora, $request->duracion, $request->cita_excluir)
        ];

        $disponible = !$verificaciones['es_domingo'] &&
                     !$verificaciones['es_dia_no_laborable'] &&
                     $verificaciones['esta_en_horario_laboral'] &&
                     !$verificaciones['tiene_colision'];

        $response = [
            'disponible' => $disponible,
            'verificaciones' => $verificaciones,
            'fecha_hora' => $fechaHora->format('Y-m-d H:i'),
        ];

        // Agregar información adicional si no está disponible
        if (!$disponible) {
            if ($verificaciones['es_dia_no_laborable']) {
                $diaNoLaborable = DiaNoLaborable::where('fecha', $request->fecha)->first();
                $response['motivo_no_laborable'] = $diaNoLaborable ? $diaNoLaborable->motivo : 'Día no laborable';
            }

            if ($verificaciones['tiene_colision']) {
                $response['horarios_alternativos'] = $this->getHorariosDisponibles($request->fecha);
            }
        }

        return response()->json($response);
    }

    /**
     * Verificar si una fecha/hora está en horario laboral
     */
    private function estaEnHorarioLaboral($fechaHora)
    {
        $diaSemana = $fechaHora->dayOfWeek === 0 ? 7 : $fechaHora->dayOfWeek;
        $horario = Horario::where('dia_semana', $diaSemana)->where('activo', true)->first();

        if (!$horario) {
            return false;
        }

        $horaInicio = Carbon::parse($horario->hora_inicio);
        $horaFin = Carbon::parse($horario->hora_fin);
        $horaCita = Carbon::parse($fechaHora->format('H:i'));

        return $horaCita->between($horaInicio, $horaFin);
    }

    /**
     * Obtener información de días no laborables para el frontend
     */
    public function getDiasNoLaborables(Request $request)
    {
        try {
            $fechaInicio = $request->query('fecha_inicio', now()->format('Y-m-d'));
            $fechaFin = $request->query('fecha_fin', now()->addMonths(2)->format('Y-m-d'));

            $diasNoLaborables = DiaNoLaborable::enRango($fechaInicio, $fechaFin)
                ->ordenadoPorFecha()
                ->get()
                ->map(function ($dia) {
                    $motivosDisponibles = DiaNoLaborable::getMotivosDisponibles();
                    return [
                        'fecha' => $dia->fecha->format('Y-m-d'),
                        'motivo' => $dia->motivo,
                        'motivo_texto' => $motivosDisponibles[$dia->motivo] ?? $dia->motivo,
                        'es_hoy' => $dia->es_hoy,
                        'es_futuro' => $dia->es_futuro,
                        'dias_restantes' => $dia->dias_restantes
                    ];
                });

            return response()->json([
                'success' => true,
                'dias_no_laborables' => $diasNoLaborables,
                'total' => $diasNoLaborables->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener días no laborables', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener días no laborables'
            ], 500);
        }
    }
}
