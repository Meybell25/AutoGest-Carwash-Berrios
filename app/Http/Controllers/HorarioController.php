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
        // Si es AJAX, devolver JSON
        if (request()->ajax()) {
            return response()->json($horarios);
        }
        return view('HorariosViews.index', compact('horarios'));
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'dia_semana' => 'required|integer|between:0,6',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'activo' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $existe = Horario::where('dia_semana', $data['dia_semana'])
            ->where('hora_inicio', $data['hora_inicio'])
            ->exists();

        if ($existe) {
            return response()->json(['errors' => ['dia_semana' => 'Ya existe un horario con este día y hora de inicio.']], 422);
        }

        $horario = Horario::create($data);
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
        $data = $request->all();

        $validator = Validator::make($data, [
            'dia_semana' => 'required|integer|between:0,6',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'activo' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $existe = Horario::where('dia_semana', $data['dia_semana'])
            ->where('hora_inicio', $data['hora_inicio'])
            ->where('id', '!=', $id)
            ->exists();

        if ($existe) {
            return response()->json(['errors' => ['dia_semana' => 'Ya existe un horario con este día y hora de inicio.']], 422);
        }

        $horario->update($data);
        return response()->json(['message' => 'Horario actualizado exitosamente.', 'horario' => $horario]);
    }

    public function destroy($id)
    {
        $horario = Horario::findOrFail($id);
        $horario->delete();

        return response()->json(['message' => 'Horario eliminado correctamente.']);
    }
}

