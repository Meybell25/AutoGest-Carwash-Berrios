<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HorarioController extends Controller
{
    // Días hábiles (1 = Lunes ... 6 = Sábado)
    const DIAS_SEMANA = [
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
    ];

    public function index(Request $request)
    {
        $horarios = Horario::whereBetween('dia_semana', [1, 6])
            ->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->get();

        if ($request->expectsJson()) {
            $data = $horarios->map(fn($h) => $this->formatHorarioResponse($h));
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Listado de horarios'
            ]);
        }

        return view('HorariosViews.index', [
            'horarios' => $horarios,
            'dias' => self::DIAS_SEMANA,
        ]);
    }

    public function create()
    {
        return view('HorariosViews.create', [
            'dias' => self::DIAS_SEMANA,
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'dia_semana'  => ['required','integer','between:1,6'],
            'hora_inicio' => ['required','date_format:H:i',
                Rule::unique('horarios','hora_inicio')->where(fn($q) => $q->where('dia_semana', $request->input('dia_semana')))
            ],
            'hora_fin'    => ['required','date_format:H:i','after:hora_inicio'],
            'activo'      => ['sometimes','boolean'],
        ];

        $data = $request->validate($rules);
        $data['activo'] = $request->boolean('activo');

        // Opcional: evitar solapamientos en el mismo día
        $overlap = Horario::where('dia_semana', $data['dia_semana'])
            ->where(function ($q) use ($data) {
                $q->whereBetween('hora_inicio', [$data['hora_inicio'], $data['hora_fin']])
                  ->orWhereBetween('hora_fin', [$data['hora_inicio'], $data['hora_fin']])
                  ->orWhere(function ($qq) use ($data) {
                      $qq->where('hora_inicio', '<', $data['hora_inicio'])
                         ->where('hora_fin', '>', $data['hora_fin']);
                  });
            })->exists();
        if ($overlap) {
            return $this->jsonOrRedirectBack($request, false, null, ['horario' => ['Existe solapamiento con otro horario en el mismo día.']], 422);
        }

        $horario = Horario::create($data);

        return $this->jsonOrRedirect($request, true, $this->formatHorarioResponse($horario), 'Horario creado correctamente', route('admin.horarios.index'));
    }

    public function show(Request $request, Horario $horario)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $this->formatHorarioResponse($horario),
                'message' => 'Detalle de horario'
            ]);
        }
        return redirect()->route('admin.horarios.edit', $horario);
    }

    public function edit(Horario $horario)
    {
        return view('HorariosViews.edit', [
            'horario' => $horario,
            'dias' => self::DIAS_SEMANA,
        ]);
    }

    public function update(Request $request, Horario $horario)
    {
        $rules = [
            'dia_semana'  => ['required','integer','between:1,6'],
            'hora_inicio' => ['required','date_format:H:i',
                Rule::unique('horarios','hora_inicio')
                    ->where(fn($q) => $q->where('dia_semana', $request->input('dia_semana')))
                    ->ignore($horario->id)
            ],
            'hora_fin'    => ['required','date_format:H:i','after:hora_inicio'],
            'activo'      => ['sometimes','boolean'],
        ];

        $data = $request->validate($rules);
        $data['activo'] = $request->boolean('activo');

        // Opcional: evitar solapamientos en el mismo día (excluyendo actual)
        $overlap = Horario::where('dia_semana', $data['dia_semana'])
            ->where('id', '!=', $horario->id)
            ->where(function ($q) use ($data) {
                $q->whereBetween('hora_inicio', [$data['hora_inicio'], $data['hora_fin']])
                  ->orWhereBetween('hora_fin', [$data['hora_inicio'], $data['hora_fin']])
                  ->orWhere(function ($qq) use ($data) {
                      $qq->where('hora_inicio', '<', $data['hora_inicio'])
                         ->where('hora_fin', '>', $data['hora_fin']);
                  });
            })->exists();
        if ($overlap) {
            return $this->jsonOrRedirectBack($request, false, null, ['horario' => ['Existe solapamiento con otro horario en el mismo día.']], 422);
        }

        $horario->update($data);
        return $this->jsonOrRedirect($request, true, $this->formatHorarioResponse($horario), 'Horario actualizado correctamente', route('admin.horarios.index'));
    }

    public function destroy(Request $request, Horario $horario)
    {
        $horario->delete();
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Horario eliminado correctamente']);
        }
        return redirect()->route('admin.horarios.index')->with('success', 'Horario eliminado correctamente');
    }

    protected function formatHorarioResponse(Horario $horario): array
    {
        return [
            'id' => $horario->id,
            'dia_semana' => $horario->dia_semana,
            'nombre_dia' => self::DIAS_SEMANA[$horario->dia_semana] ?? (string) $horario->dia_semana,
            'hora_inicio' => $horario->hora_inicio?->format('H:i:s'),
            'hora_fin' => $horario->hora_fin?->format('H:i:s'),
            'activo' => (bool) $horario->activo,
            'created_at' => optional($horario->created_at)->toDateTimeString(),
            'updated_at' => optional($horario->updated_at)->toDateTimeString(),
        ];
    }

    private function jsonOrRedirect(Request $request, bool $success, $data, string $message, string $redirect)
    {
        if ($request->expectsJson()) {
            return response()->json(['success' => $success, 'data' => $data, 'message' => $message]);
        }
        return redirect()->to($redirect)->with('success', $message);
    }

    private function jsonOrRedirectBack(Request $request, bool $success, $data, $errors = null, int $status = 422)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => $success,
                'errors' => $errors,
                'message' => $errors ? 'Validación fallida' : '',
            ], $status);
        }
        return back()->withErrors($errors)->withInput();
    }
}

