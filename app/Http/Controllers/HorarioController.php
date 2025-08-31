<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HorarioController extends Controller
{
    const DIAS_SEMANA = [
        0 => 'Domingo',
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado'
    ];

    public function index(Request $request)
    {
        $horarios = Horario::orderBy('dia_semana')->orderBy('hora_inicio')->get();

        if ($request->expectsJson()) {
            $data = $horarios->map(fn($h) => $this->formatHorarioResponse($h));
            return response()->json(['data' => $data]);
        }

        return view('horarios.index', compact('horarios'));
    }

    public function create(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'dias' => self::DIAS_SEMANA
            ]);
        }

        return view('horarios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dia_semana' => ['required', 'integer', 'between:0,6'],
            'hora_inicio' => [
                'required', 'date_format:H:i',
                Rule::unique('horarios', 'hora_inicio')->where(fn($q) => $q->where('dia_semana', $request->integer('dia_semana'))),
            ],
            'hora_fin' => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'activo' => ['required', 'boolean'],
        ]);

        $horario = Horario::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Horario creado exitosamente',
                'data' => $this->formatHorarioResponse($horario)
            ], 201);
        }

        return redirect()->route('admin.horarios.index')->with('success', 'Horario creado exitosamente');
    }

    public function show(Request $request, Horario $horario)
    {
        if ($request->expectsJson()) {
            return response()->json(['data' => $this->formatHorarioResponse($horario)]);
        }
        return redirect()->route('admin.horarios.index');
    }

    public function edit(Request $request, Horario $horario)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'data' => $this->formatHorarioResponse($horario),
                'dias' => self::DIAS_SEMANA,
            ]);
        }

        return view('horarios.edit', compact('horario'));
    }

    public function update(Request $request, Horario $horario)
    {
        $validated = $request->validate([
            'dia_semana' => ['required', 'integer', 'between:0,6'],
            'hora_inicio' => [
                'required', 'date_format:H:i',
                Rule::unique('horarios', 'hora_inicio')
                    ->where(fn($q) => $q->where('dia_semana', $request->integer('dia_semana')))
                    ->ignore($horario->id),
            ],
            'hora_fin' => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'activo' => ['required', 'boolean'],
        ]);

        $horario->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Horario actualizado exitosamente',
                'data' => $this->formatHorarioResponse($horario)
            ]);
        }

        return redirect()->route('admin.horarios.index')->with('success', 'Horario actualizado exitosamente');
    }

    public function destroy(Request $request, Horario $horario)
    {
        $horario->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Horario eliminado correctamente']);
        }

        return redirect()->route('admin.horarios.index')->with('success', 'Horario eliminado correctamente');
    }

    protected function formatHorarioResponse(Horario $horario): array
    {
        return [
            'id' => $horario->id,
            'dia_semana' => $horario->dia_semana,
            'nombre_dia' => self::DIAS_SEMANA[$horario->dia_semana] ?? 'Desconocido',
            'hora_inicio' => optional($horario->hora_inicio)->format('H:i') ?? (string) $horario->hora_inicio,
            'hora_fin' => optional($horario->hora_fin)->format('H:i') ?? (string) $horario->hora_fin,
            'activo' => (bool) $horario->activo,
            'created_at' => optional($horario->created_at)->toDateTimeString(),
            'updated_at' => optional($horario->updated_at)->toDateTimeString(),
        ];
    }
}

