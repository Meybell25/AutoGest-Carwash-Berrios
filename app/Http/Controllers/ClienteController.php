<?php
// app/Http/Controllers/ClienteController.php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Vehiculo;
use App\Models\Servicio;
use App\Models\Usuario;
use App\Models\DiaNoLaborable;
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
                'notificaciones' => $user->notificaciones()->orderBy('fecha_envio', 'desc')->get(),
                'notificacionesNoLeidas' => $user->notificaciones()->where('leido', false)->count()
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
                'notificaciones' => collect(),
                'notificacionesNoLeidas' => 0
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
                throw new \Exception('Tu cuenta está inactiva.', 403);
            }

            $validated = $request->validate([
                'vehiculo_id' => 'required|exists:vehiculos,id,usuario_id,' . Auth::id(),
                'fecha' => 'required|date|after_or_equal:today',
                'hora' => 'required|date_format:H:i',
                'servicios' => 'required|array|min:1',
                'servicios.*' => 'exists:servicios,id',
                'observaciones' => 'nullable|string|max:500'
            ]);

            // Combinar fecha y hora
            $fechaCita = Carbon::parse($validated['fecha'] . ' ' . $validated['hora']);

            // Validar que no sea domingo
            if ($fechaCita->isSunday()) {
                throw new \Exception('No atendemos domingos.', 400);
            }

            // Validar 1 mes de anticipación
            if ($fechaCita->gt(Carbon::now()->addMonth())) {
                throw new \Exception('Máximo 1 mes de anticipación.', 400);
            }

            // Validar día no laborable
            if (DiaNoLaborable::whereDate('fecha', $fechaCita)->exists()) {
                throw new \Exception('Día no laborable.', 400);
            }

            // Validar horario laboral (8AM a 6PM)
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

            // Verificar horario de cierre
            if ($horaFin->format('H:i') > '18:00') {
                throw new \Exception('Los servicios seleccionados exceden el horario de cierre.', 400);
            }

            // Verificar colisión con otras citas
            $citasSuperpuestas = Cita::where('estado', '!=', 'cancelada')
                ->when($request->has('cita_id'), function ($query) use ($request) {
                    $query->where('id', '!=', $request->cita_id); // Excluir la cita actual
                })
                ->where(function ($query) use ($fechaCita, $horaFin) {
                    $query->whereBetween('fecha_hora', [$fechaCita, $horaFin])
                        ->orWhere(function ($q) use ($fechaCita) {
                            $q->where('fecha_hora', '<', $fechaCita)
                                ->whereHas('servicios', function ($subQuery) use ($fechaCita) {
                                    $subQuery->select(DB::raw('SUM(servicios.duracion_min) as total'))
                                        ->havingRaw('DATE_ADD(citas.fecha_hora, INTERVAL total MINUTE) > ?', [$fechaCita]);
                                });
                        });
                })
                ->exists();

            if ($citasSuperpuestas) {
                $horariosDisponibles = $this->getAvailableTimes($fechaCita->format('Y-m-d'));
                return response()->json([
                    'success' => false,
                    'message' => 'El horario seleccionado está ocupado.',
                    'data' => [
                        'available_times' => $horariosDisponibles,
                        'duracion_total' => $duracionTotal
                    ]
                ], 409);
            }

            DB::beginTransaction();

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
                'titulo' => 'Cita agendada',
                'mensaje' => 'Tu cita para el ' . $fechaCita->format('d/m/Y H:i') . ' ha sido agendada.',
                'tipo' => 'confirmacion',
                'fecha_envio' => now(),
                'leido' => false
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
                    'vehiculo_placa' => $vehiculo->placa
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
            Log::error('Error al crear cita: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
                'error' => $e->getTraceAsString()
            ]);

            // Validar que el código de error sea un entero válido para HTTP
            $statusCode = is_int($e->getCode()) && $e->getCode() >= 100 && $e->getCode() < 600
                ? $e->getCode()
                : 400;

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error_type' => get_class($e) //  para identificar el tipo de error
            ], $statusCode);
        }
    }

    private function getAvailableTimes($date, $excludeCitaId = null)
    {
        $date = Carbon::parse($date);

        if ($date->isSunday() || DiaNoLaborable::whereDate('fecha', $date)->exists()) {
            return [];
        }

        // Obtener horarios ocupados calculando la duración sobre la marcha
        $horariosOcupados = Cita::whereDate('fecha_hora', $date)
            ->where('estado', '!=', 'cancelada')
            ->when($excludeCitaId, function ($query) use ($excludeCitaId) {
                $query->where('id', '!=', $excludeCitaId); // Excluir la cita actual si se proporciona ID
            })
            ->with('servicios')
            ->get()
            ->map(function ($cita) {
                $horaInicio = Carbon::parse($cita->fecha_hora);
                $duracionTotal = $cita->servicios->sum('duracion_min');
                return [
                    'inicio' => $horaInicio,
                    'fin' => $horaInicio->copy()->addMinutes($duracionTotal)
                ];
            });

        // Generar horarios disponibles (cada 30 minutos de 8AM a 6PM)
        $horariosDisponibles = [];
        $horaActual = $date->copy()->setTime(8, 0); // Comienza a las 8AM
        $horaCierre = $date->copy()->setTime(18, 0); // Cierra a las 6PM

        while ($horaActual <= $horaCierre) {
            $horaFin = $horaActual->copy()->addMinutes(30); // Intervalos de 30 minutos

            // Verificar si este intervalo está disponible
            $disponible = true;
            foreach ($horariosOcupados as $ocupado) {
                if ($horaActual < $ocupado['fin'] && $horaFin > $ocupado['inicio']) {
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

        try {
            DB::beginTransaction();

            $fechaOriginal = $cita->fecha_hora->format('d/m/Y H:i');
            $cita->estado = Cita::ESTADO_CANCELADA;
            $cita->save();

            // Crear notificación
            $cita->usuario->notificaciones()->create([
                'titulo' => 'Cita cancelada',
                'mensaje' => "Tu cita para el {$fechaOriginal} ha sido cancelada.",
                'tipo' => 'cancelacion',
                'fecha_envio' => now(),
                'leido' => false
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
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar la cita: ' . $e->getMessage()
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

            // Obtener TODAS las citas del usuario (esto reemplaza $mis_citas)
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

            return view('cliente.dashboard', [
                'user' => $user,
                'stats' => [
                    'total_vehiculos' => $vehiculos->count(),
                    'total_citas' => $todasLasCitas->count(),
                    'citas_pendientes' => $todasLasCitas->where('estado', 'pendiente')->count(),
                    'citas_confirmadas' => $todasLasCitas->where('estado', 'confirmada')->count(),
                ],
                'vehiculos_dashboard' => $vehiculosDashboard,
                'proximas_citas' => $proximas_citas,
                'historial_citas' => $historial_citas,
                'notificaciones' => $user->notificaciones()->orderBy('fecha_envio', 'desc')->get(),
                'notificacionesNoLeidas' => $user->notificaciones()->where('leido', false)->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener datos del dashboard: ' . $e->getMessage()
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
            'data' => [
                'cita' => $cita->load(['vehiculo', 'servicios']),
                'vehiculo_id' => $cita->vehiculo_id,
                'servicios' => $cita->servicios->pluck('id')->toArray(),
                'fecha' => $cita->fecha_hora->format('Y-m-d'),
                'hora' => $cita->fecha_hora->format('H:i'),
                'observaciones' => $cita->observaciones,
                'vehiculo_tipo' => $cita->vehiculo->tipo
            ]
        ]);
    }

    public function updateCita(Request $request, Cita $cita)
    {
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
            'servicios.*' => 'exists:servicios,id',
            'observaciones' => 'nullable|string|max:500',
            'cita_id' => 'required|exists:citas,id' // Asegurar que la cita existe
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
                'received_data' => $request->all(),
                'is_valid' => $request->has('servicios') && is_array($request->servicios)
            ], 500);
        }
    }
    public function getHorariosOcupados(Request $request)
    {
        try {
            $fecha = $request->query('fecha');

            if (!$fecha) {
                return response()->json(['horariosOcupados' => []]);
            }

            $citas = Cita::with('servicios')
                ->whereDate('fecha_hora', $fecha)
                ->where('estado', '!=', 'cancelada')
                ->get();

            $horariosOcupados = $citas->map(function ($cita) {
                $horaInicio = Carbon::parse($cita->fecha_hora);
                $duracionTotal = $cita->servicios->sum('duracion_min');
                return [
                    'hora_inicio' => $horaInicio->format('H:i'),
                    'duracion' => $duracionTotal,
                    'hora_fin' => $horaInicio->copy()->addMinutes($duracionTotal)->format('H:i')
                ];
            });

            return response()->json(['horariosOcupados' => $horariosOcupados]);
        } catch (\Exception $e) {
            Log::error('Error en getHorariosOcupados: ' . $e->getMessage());
            return response()->json(['horariosOcupados' => []]);
        }
    }
}
