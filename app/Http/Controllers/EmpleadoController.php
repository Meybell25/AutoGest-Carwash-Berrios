<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EmpleadoController extends Controller
{
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
}
