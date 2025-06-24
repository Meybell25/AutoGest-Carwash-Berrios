<?php
// app/Http/Controllers/ClienteController.php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Vehiculo;
use App\Models\Servicio;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
                'mis_vehiculos' => $vehiculos->take(3),
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
        // Validar estado del usuario primero
        if (!Auth::user()->estado) {
            return response()->json([
                'message' => 'Tu cuenta está inactiva. No puedes crear nuevas citas.'
            ], 403);
        }

        $validated = $request->validate([
            'vehiculo_id' => 'required|exists:vehiculos,id,usuario_id,' . Auth::id(),
            'fecha_hora' => 'required|date|after:now',
            'servicios' => 'required|array',
            'servicios.*' => 'exists:servicios,id',
            'observaciones' => 'nullable|string|max:500'
        ]);

        try {
            // Crear la cita
            $cita = new Cita();
            $cita->usuario_id = Auth::id();
            $cita->vehiculo_id = $validated['vehiculo_id'];
            $cita->fecha_hora = $validated['fecha_hora'];
            $cita->estado = Cita::ESTADO_PENDIENTE;
            $cita->observaciones = $validated['observaciones'] ?? null;
            $cita->save();

            // Adjuntar servicios con sus precios actuales
            $serviciosConPrecio = [];
            foreach ($validated['servicios'] as $servicioId) {
                $servicio = Servicio::find($servicioId);
                $serviciosConPrecio[$servicioId] = ['precio' => $servicio->precio];
            }

            $cita->servicios()->attach($serviciosConPrecio);

            // Opcional: Crear notificación para el cliente
            $cita->usuario->notificaciones()->create([
                'titulo' => 'Cita agendada',
                'mensaje' => 'Tu cita para el ' . $cita->fecha_hora->format('d/m/Y H:i') . ' ha sido agendada.',
                'tipo' => 'confirmacion',
                'fecha_envio' => now(),
                'leido' => false
            ]);

            return response()->json([
                'message' => 'Cita creada exitosamente',
                'cita' => $cita
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la cita: ' . $e->getMessage()
            ], 500);
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
}
