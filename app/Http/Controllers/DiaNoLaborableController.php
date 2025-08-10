<?php

namespace App\Http\Controllers;

use App\Models\DiaNoLaborable;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DiaNoLaborableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Si es una petición AJAX, devolver JSON
        if ($request->ajax()) {
            $dias = DiaNoLaborable::ordenadoPorFecha()->get();
            return response()->json($dias);
        }

        // Si es una petición normal, devolver vista
        $dias = DiaNoLaborable::ordenadoPorFecha()->paginate(10);
        $motivosDisponibles = DiaNoLaborable::getMotivosDisponibles();

        return view('admin.dias-no-laborables.index', compact('dias', 'motivosDisponibles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $motivosDisponibles = DiaNoLaborable::getMotivosDisponibles();
        return view('admin.dias-no-laborables.create', compact('motivosDisponibles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date|after_or_equal:today|unique:dias_no_laborables,fecha',
            'motivo' => 'required|string|max:255',
        ], [
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha debe ser válida.',
            'fecha.after_or_equal' => 'La fecha debe ser igual o posterior a hoy.',
            'fecha.unique' => 'Ya existe un día no laborable para esta fecha.',
            'motivo.required' => 'El motivo es obligatorio.',
            'motivo.max' => 'El motivo no puede exceder 255 caracteres.',
        ]);

        $dia = DiaNoLaborable::create([
            'fecha' => $request->fecha,
            'motivo' => $request->motivo,
        ]);

        // Registrar en bitácora
        \App\Models\Bitacora::create([
            'usuario_id' => auth()->id(),
            'accion' => 'crear',
            'tabla_afectada' => 'dias_no_laborables',
            'registro_id' => $dia->id,
            'detalles' => "Creó día no laborable: {$dia->fecha->format('d/m/Y')} - {$dia->motivo}",
        ]);

        if ($request->ajax()) {
            return response()->json($dia, 201);
        }

        return redirect()->route('admin.dias-no-laborables.index')
                        ->with('success', 'Día no laborable creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $dia = DiaNoLaborable::findOrFail($id);

        if ($request->ajax()) {
            return response()->json($dia);
        }

        return view('admin.dias-no-laborables.show', compact('dia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $dia = DiaNoLaborable::findOrFail($id);
        $motivosDisponibles = DiaNoLaborable::getMotivosDisponibles();

        return view('admin.dias-no-laborables.edit', compact('dia', 'motivosDisponibles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $dia = DiaNoLaborable::findOrFail($id);

        $request->validate([
            'fecha' => 'required|date|after_or_equal:today|unique:dias_no_laborables,fecha,' . $id,
            'motivo' => 'required|string|max:255',
        ], [
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha debe ser válida.',
            'fecha.after_or_equal' => 'La fecha debe ser igual o posterior a hoy.',
            'fecha.unique' => 'Ya existe un día no laborable para esta fecha.',
            'motivo.required' => 'El motivo es obligatorio.',
            'motivo.max' => 'El motivo no puede exceder 255 caracteres.',
        ]);

        $fechaAnterior = $dia->fecha->format('d/m/Y');
        $motivoAnterior = $dia->motivo;

        $dia->update([
            'fecha' => $request->fecha,
            'motivo' => $request->motivo,
        ]);

        // Registrar en bitácora
        \App\Models\Bitacora::create([
            'usuario_id' => auth()->id(),
            'accion' => 'actualizar',
            'tabla_afectada' => 'dias_no_laborables',
            'registro_id' => $dia->id,
            'detalles' => "Actualizó día no laborable de {$fechaAnterior} - {$motivoAnterior} a {$dia->fecha->format('d/m/Y')} - {$dia->motivo}",
        ]);

        if ($request->ajax()) {
            return response()->json($dia);
        }

        return redirect()->route('admin.dias-no-laborables.index')
                        ->with('success', 'Día no laborable actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Request $request)
    {
        $dia = DiaNoLaborable::findOrFail($id);
        $fechaEliminada = $dia->fecha->format('d/m/Y');
        $motivoEliminado = $dia->motivo;

        $dia->delete();

        // Registrar en bitácora
        \App\Models\Bitacora::create([
            'usuario_id' => auth()->id(),
            'accion' => 'eliminar',
            'tabla_afectada' => 'dias_no_laborables',
            'registro_id' => $id,
            'detalles' => "Eliminó día no laborable: {$fechaEliminada} - {$motivoEliminado}",
        ]);

        if ($request->ajax()) {
            return response()->json(['mensaje' => 'Día no laborable eliminado correctamente.']);
        }

        return redirect()->route('admin.dias-no-laborables.index')
                        ->with('success', 'Día no laborable eliminado exitosamente.');
    }

    /**
     * Métodos adicionales para funcionalidades específicas
     */
    public function proximos()
    {
        $dias = DiaNoLaborable::getProximosNoLaborables();
        return response()->json($dias);
    }

    public function delMes(Request $request)
    {
        $mes = $request->input('mes', Carbon::now()->month);
        $año = $request->input('año', Carbon::now()->year);

        $dias = DiaNoLaborable::getNoLaborablesDelMes($mes, $año);
        return response()->json($dias);
    }

    public function diasLaborables(Request $request)
    {
        $request->validate([
            'inicio' => 'required|date',
            'fin' => 'required|date|after_or_equal:inicio',
        ]);

        $dias = DiaNoLaborable::getDiasLaborablesEnRango($request->inicio, $request->fin);
        return response()->json([
            'dias_laborables' => $dias,
            'total' => count($dias)
        ]);
    }

    public function motivos()
    {
        return response()->json(DiaNoLaborable::getMotivosDisponibles());
    }

    /**
     * Método para verificar si una fecha específica es laborable
     */
    public function verificarFecha(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
        ]);

        $fecha = Carbon::parse($request->fecha);
        $esNoLaborable = DiaNoLaborable::esNoLaborable($fecha);
        $esDomingo = $fecha->dayOfWeek === 0;

        return response()->json([
            'fecha' => $fecha->format('Y-m-d'),
            'es_laborable' => !$esNoLaborable && !$esDomingo,
            'es_no_laborable' => $esNoLaborable,
            'es_domingo' => $esDomingo,
            'motivo' => $esNoLaborable ? DiaNoLaborable::where('fecha', $fecha->format('Y-m-d'))->first()->motivo : null
        ]);
    }
}
