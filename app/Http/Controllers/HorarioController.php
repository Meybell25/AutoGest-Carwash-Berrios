<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function index()
    {
        // Excluir domingos (día 0) y ordenar por dIa y hora
        $horarios = Horario::where('dia_semana', '>=', 1)
            ->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->get();

        // Si es AJAX, devolver JSON con nombres de dia
        if (request()->ajax()) {
            $horarios->transform(function ($horario) {
                $horario->nombre_dia = self::DIAS_SEMANA[$horario->dia_semana] ?? 'Desconocido';
                return $horario;
            });
            return response()->json($horarios);
        }

        return view('HorariosViews.index', compact('horarios'));
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'dia_semana' => 'required|integer|between:1,6', // 1-6 (Lunes a Sábado)
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => [
                'required',
                'date_format:H:i',
                'after:hora_inicio',
                function ($attribute, $value, $fail) use ($data) {
                    // Validar que no exceda el límite de horas (ej. máximo 8 horas por bloque)
                    $inicio = \Carbon\Carbon::createFromFormat('H:i', $data['hora_inicio']);
                    $fin = \Carbon\Carbon::createFromFormat('H:i', $value);
                    
                    if ($fin->diffInHours($inicio) > 8) {
                        $fail('El bloque horario no puede exceder las 8 horas.');
                    }
                }
            ],
            'activo' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Validar superposición de horarios
        $existeSuperposicion = Horario::where('dia_semana', $data['dia_semana'])
            ->where(function ($query) use ($data) {
                $query->whereBetween('hora_inicio', [$data['hora_inicio'], $data['hora_fin']])
                      ->orWhereBetween('hora_fin', [$data['hora_inicio'], $data['hora_fin']])
                      ->orWhere(function ($q) use ($data) {
                          $q->where('hora_inicio', '<', $data['hora_inicio'])
                            ->where('hora_fin', '>', $data['hora_fin']);
                      });
            })
            ->exists();

        if ($existeSuperposicion) {
            return response()->json([
                'errors' => ['horario' => 'Existe superposición con otro horario en el mismo día.']
            ], 422);
        }

        $horario = Horario::create($data);
        
        return response()->json([
            'message' => 'Horario creado exitosamente.',
            'horario' => $this->formatHorarioResponse($horario)
        ]);
    }

    public function show($id)
    {
        $horario = Horario::findOrFail($id);
        return response()->json($this->formatHorarioResponse($horario));
    }

    public function update(Request $request, $id)
    {
        $horario = Horario::findOrFail($id);

        $validator = Validator::make($data, [
            'dia_semana' => 'required|integer|between:1,6', // 1-6 (Lunes a Sábado)
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => [
                'required',
                'date_format:H:i',
                'after:hora_inicio',
                function ($attribute, $value, $fail) use ($data) {
                    $inicio = \Carbon\Carbon::createFromFormat('H:i', $data['hora_inicio']);
                    $fin = \Carbon\Carbon::createFromFormat('H:i', $value);
                    
                    if ($fin->diffInHours($inicio) > 8) {
                        $fail('El bloque horario no puede exceder las 8 horas.');
                    }
                }
            ],
            'activo' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Validar superposición excluyendo el horario actual
        $existeSuperposicion = Horario::where('dia_semana', $data['dia_semana'])
            ->where('id', '!=', $id)
            ->where(function ($query) use ($data) {
                $query->whereBetween('hora_inicio', [$data['hora_inicio'], $data['hora_fin']])
                      ->orWhereBetween('hora_fin', [$data['hora_inicio'], $data['hora_fin']])
                      ->orWhere(function ($q) use ($data) {
                          $q->where('hora_inicio', '<', $data['hora_inicio'])
                            ->where('hora_fin', '>', $data['hora_fin']);
                      });
            })
            ->exists();

        if ($existeSuperposicion) {
            return response()->json([
                'errors' => ['horario' => 'Existe superposición con otro horario en el mismo día.']
            ], 422);
        }

        $horario->update($data);
        
        return response()->json([
            'message' => 'Horario actualizado exitosamente.',
            'horario' => $this->formatHorarioResponse($horario)
        ]);
    }

    public function destroy($id)
    {
        $horario = Horario::findOrFail($id);
        
        // Validar si el horario está siendo usado en citas futuras
        if ($horario->citas()->where('fecha_hora', '>', now())->exists()) {
            return response()->json([
                'error' => 'No se puede eliminar el horario porque tiene citas programadas.'
            ], 422);
        }

        $horario->delete();

        return response()->json([
            'message' => 'Horario eliminado correctamente.'
        ]);
    }

    /**
     * Formatea la respuesta del horario con datos adicionales
     */
    protected function formatHorarioResponse($horario)
    {
        return [
            'id' => $horario->id,
            'dia_semana' => $horario->dia_semana,
            'nombre_dia' => self::DIAS_SEMANA[$horario->dia_semana] ?? 'Desconocido',
            'hora_inicio' => $horario->hora_inicio,
            'hora_fin' => $horario->hora_fin,
            'activo' => $horario->activo,
            'created_at' => $horario->created_at,
            'updated_at' => $horario->updated_at
        ];
    }
}