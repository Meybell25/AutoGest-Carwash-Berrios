<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHorarioRequest;
use App\Http\Requests\UpdateHorarioRequest;
use App\Models\Horario;
use Illuminate\Http\Request;

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
        $perPage = (int)($request->query('per_page', 10));
        if ($perPage <= 0) { $perPage = 10; }

        $paginator = Horario::whereBetween('dia_semana', [1, 6])
            ->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->paginate($perPage);

        $data = $paginator->getCollection()->map(fn($h) => $this->formatHorarioResponse($h));
        return response()->json([
            'success' => true,
            'data' => $data,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
            'message' => 'Listado de horarios'
        ]);
    }

    public function create()
    {
        return response()->json(['success' => false, 'message' => 'No disponible'], 404);
    }

    public function store(StoreHorarioRequest $request)
    {
        $data = $request->validated();
        $data['activo'] = (bool)($data['activo'] ?? true);

        $horario = Horario::create($data);

        return response()->json([
            'success' => true,
            'data' => $this->formatHorarioResponse($horario),
            'message' => 'Horario creado correctamente'
        ]);
    }

    public function show(Request $request, Horario $horario)
    {
        return response()->json([
            'success' => true,
            'data' => $this->formatHorarioResponse($horario),
            'message' => 'Detalle de horario'
        ]);
    }

    public function edit(Horario $horario)
    {
        return response()->json(['success' => false, 'message' => 'No disponible'], 404);
    }

    public function update(UpdateHorarioRequest $request, Horario $horario)
    {
        $data = $request->validated();
        $horario->update($data);

        return response()->json([
            'success' => true,
            'data' => $this->formatHorarioResponse($horario),
            'message' => 'Horario actualizado correctamente'
        ]);
    }

    public function destroy(Request $request, Horario $horario)
    {
        $horario->delete();
        return response()->json(['success' => true, 'message' => 'Horario eliminado correctamente']);
    }

    public function toggle(Request $request, Horario $horario)
    {
        $horario->activo = !$horario->activo;
        $horario->save();

        return response()->json([
            'success' => true,
            'data' => $this->formatHorarioResponse($horario),
            'message' => $horario->activo ? 'Horario activado' : 'Horario desactivado'
        ]);
    }

    protected function formatHorarioResponse(Horario $horario): array
    {
        return [
            'id' => $horario->id,
            'dia_semana' => $horario->dia_semana,
            'nombre_dia' => self::DIAS_SEMANA[$horario->dia_semana] ?? (string) $horario->dia_semana,
            'hora_inicio' => $horario->hora_inicio?->format('H:i'),
            'hora_fin' => $horario->hora_fin?->format('H:i'),
            'activo' => (bool) $horario->activo,
            'created_at' => optional($horario->created_at)->toDateTimeString(),
            'updated_at' => optional($horario->updated_at)->toDateTimeString(),
        ];
    }
}

