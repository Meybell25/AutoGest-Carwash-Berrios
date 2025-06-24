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
        return view('HorariosViews.index', compact('horarios'));
    }

    public function create()
    {
        return view('HorariosViews.create');
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
            return back()->withErrors($validator)->withInput();
        }

        $existe = Horario::where('dia_semana', $data['dia_semana'])
            ->where('hora_inicio', $data['hora_inicio'])
            ->exists();

        if ($existe) {
            return back()->withErrors(['dia_semana' => 'Ya existe un horario con este día y hora de inicio.'])->withInput();
        }

        Horario::create($data);

        return redirect()->route('horarios.index')->with('success', 'Horario creado exitosamente.');
    }

    public function edit($id)
    {
        $horario = Horario::findOrFail($id);
        return view('HorariosViews.edit', compact('horario'));
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
            return back()->withErrors($validator)->withInput();
        }

        $existe = Horario::where('dia_semana', $data['dia_semana'])
            ->where('hora_inicio', $data['hora_inicio'])
            ->where('id', '!=', $id)
            ->exists();

        if ($existe) {
            return back()->withErrors(['dia_semana' => 'Ya existe un horario con este día y hora de inicio.'])->withInput();
        }

        $horario->update($data);

        return redirect()->route('horarios.index')->with('success', 'Horario actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $horario = Horario::findOrFail($id);
        $horario->delete();

        return redirect()->route('horarios.index')->with('success', 'Horario eliminado correctamente.');
    }
}
