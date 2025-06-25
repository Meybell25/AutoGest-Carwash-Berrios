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

            $citas = $user->citas()->with(['vehiculo', 'servicios'])->get();

            // Cambia 'leida' por 'leido' para coincidir con la base de datos
            $notificaciones = $user->notificaciones()->orderBy('fecha_envio', 'desc')->get();
            $notificacionesNoLeidas = $user->notificaciones()->where('leido', false)->count();

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
                'mis_citas' => $citas->take(3),
                'notificaciones' => $notificaciones,
                'notificacionesNoLeidas' => $notificacionesNoLeidas
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
        // Validar estado del usuario
        if (!Auth::user()->estado) {
            return response()->json(['message' => 'Tu cuenta está inactiva.'], 403);
        }

        $validated = $request->validate([
            'vehiculo_id' => 'required|exists:vehiculos,id,usuario_id,' . Auth::id(),
            'fecha_hora' => 'required|date|after:now',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:servicios,id',
            'observaciones' => 'nullable|string|max:500'
        ]);

        $fechaCita = Carbon::parse($validated['fecha_hora']);

        // Verificar disponibilidad de horario
        $citaExistente = Cita::where('fecha_hora', $fechaCita)->exists();
        if ($citaExistente) {
            return response()->json([
                'message' => 'Lo sentimos, ese horario ya está ocupado. Por favor selecciona otro horario.',
                'available_times' => $this->getAvailableTimes($fechaCita->format('Y-m-d'))
            ], 409);
        }

        // Validar 1 mes de anticipación
        if ($fechaCita->gt(Carbon::now()->addMonth())) {
            return response()->json(['message' => 'Máximo 1 mes de anticipación.'], 400);
        }

        // Validar día no laborable
        if (DiaNoLaborable::whereDate('fecha', $fechaCita)->exists()) {
            return response()->json(['message' => 'Día no laborable.'], 400);
        }

        // Validar domingo
        if ($fechaCita->isSunday()) {
            return response()->json(['message' => 'No atendemos domingos.'], 400);
        }

        // Validar horario laboral (ejemplo: 8AM a 6PM)
        $hora = $fechaCita->format('H:i');
        if ($hora < '08:00' || $hora > '18:00') {
            return response()->json(['message' => 'Horario no laboral (8:00 AM - 6:00 PM).'], 400);
        }

        // Validar tipo de vehículo vs servicios
        $vehiculo = Vehiculo::find($validated['vehiculo_id']);
        $serviciosInvalidos = Servicio::whereIn('id', $validated['servicios'])
            ->where('categoria', '!=', $vehiculo->tipo)
            ->exists();

        if ($serviciosInvalidos) {
            return response()->json(['message' => 'Servicios no válidos para este vehículo.'], 400);
        }

        // Validar duración total de servicios
        $duracionTotal = Servicio::whereIn('id', $validated['servicios'])->sum('duracion_min');
        $horaFin = $fechaCita->copy()->addMinutes($duracionTotal);

        // Verificar si la cita termina después del horario laboral
        if ($horaFin->format('H:i') > '18:00') {
            return response()->json(['message' => 'Los servicios seleccionados no pueden completarse antes del cierre.'], 400);
        }

        // Verificar colisión con otras citas
        $citasSuperpuestas = Cita::where(function ($query) use ($fechaCita, $horaFin) {
            $query->whereBetween('fecha_hora', [$fechaCita, $horaFin])
                ->orWhereBetween(DB::raw('DATE_ADD(fecha_hora, INTERVAL duracion_total MINUTE)'), [$fechaCita, $horaFin])
                ->orWhere(function ($q) use ($fechaCita, $horaFin) {
                    $q->where('fecha_hora', '<', $fechaCita)
                        ->where(DB::raw('DATE_ADD(fecha_hora, INTERVAL duracion_total MINUTE)'), '>', $horaFin);
                });
        })->exists();

        if ($citasSuperpuestas) {
            return response()->json([
                'message' => 'Existe un conflicto de horario con otra cita.',
                'available_times' => $this->getAvailableTimes($fechaCita->format('Y-m-d'))
            ], 409);
        }

        try {
            DB::beginTransaction();

            // Crear la cita
            $cita = new Cita();
            $cita->usuario_id = Auth::id();
            $cita->vehiculo_id = $validated['vehiculo_id'];
            $cita->fecha_hora = $validated['fecha_hora'];
            $cita->duracion_total = $duracionTotal; // Guardar duración total
            $cita->estado = Cita::ESTADO_PENDIENTE;
            $cita->observaciones = $validated['observaciones'] ?? null;
            $cita->save();

            // Adjuntar servicios con sus precios actuales
            $serviciosConPrecio = [];
            foreach ($validated['servicios'] as $servicioId) {
                $servicio = Servicio::find($servicioId);
                $serviciosConPrecio[$servicioId] = [
                    'precio' => $servicio->precio,
                    'duracion' => $servicio->duracion_min
                ];
            }

            $cita->servicios()->attach($serviciosConPrecio);

            // Crear notificación para el cliente
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
                'cita' => $cita,
                'duracion_total' => $duracionTotal,
                'hora_fin' => $horaFin->format('H:i')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la cita: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getAvailableTimes($date)
    {
        $date = Carbon::parse($date);

        // Si es domingo o día no laborable, retornar vacío
        if ($date->isSunday() || DiaNoLaborable::whereDate('fecha', $date)->exists()) {
            return [];
        }

        // Obtener horarios ocupados para esa fecha
        $horariosOcupados = Cita::whereDate('fecha_hora', $date)
            ->get()
            ->map(function ($cita) {
                return [
                    'inicio' => Carbon::parse($cita->fecha_hora),
                    'fin' => Carbon::parse($cita->fecha_hora)->addMinutes($cita->duracion_total)
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

        try {
            // Obtener citas futuras con estados específicos
            $proximas_citas = $user->citas()
                ->with(['vehiculo', 'servicios'])
                ->where('fecha_hora', '>=', now())
                ->whereIn('estado', ['pendiente', 'confirmada', 'en_proceso'])
                ->orderBy('fecha_hora')
                ->get()
                ->map(function ($cita) {
                    return [
                        'id' => $cita->id,
                        'fecha_hora' => $cita->fecha_hora,
                        'estado' => $cita->estado,
                        'observaciones' => $cita->observaciones,
                        'vehiculo' => $cita->vehiculo,
                        'servicios' => $cita->servicios,
                        'duracion_total' => $cita->servicios->sum('duracion_min')
                    ];
                });

            // Obtener historial de citas
            $historial_citas = $user->citas()
                ->with(['vehiculo', 'servicios'])
                ->where(function ($query) {
                    $query->where('fecha_hora', '<', now())
                        ->orWhereIn('estado', ['finalizada', 'cancelada']);
                })
                ->orderBy('fecha_hora', 'desc')
                ->get()
                ->map(function ($cita) {
                    return [
                        'id' => $cita->id,
                        'fecha_hora' => $cita->fecha_hora,
                        'estado' => $cita->estado,
                        'observaciones' => $cita->observaciones,
                        'vehiculo' => $cita->vehiculo,
                        'servicios' => $cita->servicios,
                        'duracion_total' => $cita->servicios->sum('duracion_min')
                    ];
                });

            return response()->json([
                'success' => true,
                'proximas_citas' => $proximas_citas,
                'historial_citas' => $historial_citas,
                'total_citas' => $user->citas()->count(),
                'citas_pendientes' => $user->citas()->where('estado', 'pendiente')->count(),
                'stats' => [
                    'total_vehiculos' => $user->vehiculos()->count(),
                    'total_citas' => $user->citas()->count(),
                    'citas_pendientes' => $user->citas()->where('estado', 'pendiente')->count(),
                    'citas_confirmadas' => $user->citas()->where('estado', 'confirmada')->count(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener datos del dashboard: ' . $e->getMessage()
            ], 500);
        }
    }
}
