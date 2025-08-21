<?php

namespace App\Http\Controllers;

use App\Models\DiaNoLaborable;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DiaNoLaborableController extends Controller
{
    public function index()
    {
        $dias = DiaNoLaborable::ordenadoPorFecha()->get();
        return response()->json($dias);
    }

    public function show($id)
    {
        $dia = DiaNoLaborable::findOrFail($id);
        return response()->json($dia);
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date|unique:dias_no_laborables,fecha',
            'motivo' => 'required|string',
        ]);

        $dia = DiaNoLaborable::create([
            'fecha' => $request->fecha,
            'motivo' => $request->motivo,
        ]);

        return response()->json($dia, 201);
    }

    public function update(Request $request, $id)
    {
        $dia = DiaNoLaborable::findOrFail($id);

        $request->validate([
            'fecha' => 'required|date|unique:dias_no_laborables,fecha,' . $id,
            'motivo' => 'required|string',
        ]);

        $dia->update([
            'fecha' => $request->fecha,
            'motivo' => $request->motivo,
        ]);

        return response()->json($dia);
    }

    public function destroy($id)
    {
        $dia = DiaNoLaborable::findOrFail($id);
        $dia->delete();

        return response()->json(['mensaje' => 'Día no laborable eliminado correctamente.']);
    }

    public function proximos()
    {
        $dias = DiaNoLaborable::getProximosNoLaborables();
        return response()->json($dias);
    }

    public function delMes(Request $request)
    {
        $mes = $request->input('mes');
        $año = $request->input('año');

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
        return response()->json($dias);
    }

    public function motivos()
    {
        return response()->json(DiaNoLaborable::getMotivosDisponibles());
    }
}
