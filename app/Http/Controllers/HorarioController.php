<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HorarioController extends Controller
{
    public function index()
    {
        $horarios = Horario::ordenadoPorDia()->get();

        if (request()->ajax()) {
            return response()->json($horarios);
        }

        return view('HorariosViews.index', compact('horarios'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dia_semana' => 'required|integer|between:0,6',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'activo' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $exists = Horario::where('dia_semana', $request->dia_semana)
            ->where('hora_inicio', $request->hora_inicio)
            ->exists();

        if ($exists) {
            return response()->json(['errors' => ['duplicado' => 'Ya existe un horario con ese día y hora de inicio.']], 422);
        }

        $horario = Horario::create($request->all());
        return response()->json(['message' => 'Horario creado exitosamente.', 'horario' => $horario]);
    }

    public function show($id)
    {
        $horario = Horario::findOrFail($id);
        return response()->json($horario);
    }

    public function update(Request $request, $id)
    {
        $horario = Horario::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'dia_semana' => 'required|integer|between:0,6',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'activo' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $exists = Horario::where('dia_semana', $request->dia_semana)
            ->where('hora_inicio', $request->hora_inicio)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return response()->json(['errors' => ['duplicado' => 'Ya existe un horario con ese día y hora de inicio.']], 422);
        }

        $horario->update($request->all());
        return response()->json(['message' => 'Horario actualizado exitosamente.', 'horario' => $horario]);
    }

    public function destroy($id)
    {
        $horario = Horario::findOrFail($id);
        $horario->delete();

        return response()->json(['message' => 'Horario eliminado correctamente.']);
    }

  
     public function porDia($dia)
    {
       $horarios = Horario::byDia($dia)->ordenadoPorDia()->get();

        return response()->json([
           'horarios' => $horarios,
           'nombre_dia' => Horario::NOMBRES_DIAS[$dia] ?? 'Día desconocido'
       ]);
    }

}

