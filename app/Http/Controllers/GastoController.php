<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GastoController extends Controller
{
    /**
     * Mostrar lista de gastos.
     */
    public function index(Request $request)
    {
        $query = Gasto::with('usuario');

        // Filtros opcionales
        if ($request->filled('tipo') && $request->tipo !== 'todos') {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->betweenDates($request->fecha_inicio, $request->fecha_fin);
        }

        $gastos = $query->latest('fecha_gasto')->paginate(15);
        // Solo mostrar usuarios admin y empleados para filtros
        $usuarios = Usuario::whereIn('rol', ['admin', 'empleado'])
            ->orderBy('nombre', 'asc')
            ->get();
        $tipos = Gasto::getTipos();

        // Estadísticas rápidas
        $estadisticas = [
            'total_mes' => Gasto::delMesActual()->sum('monto'),
            'total_gastos' => Gasto::count(),
            'promedio_diario' => Gasto::delMesActual()->avg('monto') ?? 0,
            'gasto_mayor' => Gasto::delMesActual()->max('monto') ?? 0,
        ];

        return view('admin.gastos.index', compact('gastos', 'usuarios', 'tipos', 'estadisticas'));
    }

    /**
     * Mostrar formulario para crear un nuevo gasto.
     */
    public function create()
    {
        // Solo mostrar usuarios admin y empleados
        $usuarios = Usuario::whereIn('rol', ['admin', 'empleado'])
            ->orderBy('nombre', 'asc')
            ->get();
        $tipos = Gasto::getTipos();

        return view('admin.gastos.create', compact('usuarios', 'tipos'));
    }

    /**
     * Almacenar un nuevo gasto.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'usuario_id' => 'required|exists:usuarios,id',
            'tipo' => 'required|string|in:' . implode(',', array_keys(Gasto::getTipos())),
            'detalle' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0.01',
            'fecha_gasto' => 'required|date|before_or_equal:today',
        ], [
            'usuario_id.required' => 'Debe seleccionar un usuario responsable.',
            'usuario_id.exists' => 'El usuario seleccionado no es válido.',
            'tipo.required' => 'Debe seleccionar un tipo de gasto.',
            'tipo.in' => 'El tipo de gasto seleccionado no es válido.',
            'detalle.required' => 'El detalle del gasto es obligatorio.',
            'detalle.max' => 'El detalle no puede exceder los 255 caracteres.',
            'monto.required' => 'El monto es obligatorio.',
            'monto.numeric' => 'El monto debe ser un número válido.',
            'monto.min' => 'El monto debe ser mayor a $0.00.',
            'fecha_gasto.required' => 'La fecha del gasto es obligatoria.',
            'fecha_gasto.date' => 'La fecha debe ser válida.',
            'fecha_gasto.before_or_equal' => 'La fecha no puede ser futura.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Por favor, corrige los errores del formulario.');
        }

        try {
            $gasto = Gasto::create([
                'usuario_id' => $request->usuario_id,
                'tipo' => $request->tipo,
                'detalle' => $request->detalle,
                'monto' => $request->monto,
                'fecha_gasto' => $request->fecha_gasto,
            ]);

            return redirect()->route('admin.gastos.index')
                ->with('success', 'Gasto registrado correctamente.')
                ->with('gasto_creado', $gasto->id);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar el gasto. Por favor, inténtelo de nuevo.');
        }
    }

    /**
     * Mostrar un gasto específico.
     */
    public function show($id)
    {
        $gasto = Gasto::with('usuario')->findOrFail($id);

        return view('admin.gastos.show', compact('gasto'));
    }

    /**
     * Mostrar formulario para editar un gasto.
     */
    public function edit($id)
    {
        $gasto = Gasto::findOrFail($id);
        // Solo mostrar usuarios admin y empleados
        $usuarios = Usuario::whereIn('rol', ['admin', 'empleado'])
            ->orderBy('nombre', 'asc')
            ->get();
        $tipos = Gasto::getTipos();

        return view('admin.gastos.edit', compact('gasto', 'usuarios', 'tipos'));
    }

    /**
     * Actualizar un gasto.
     */
    public function update(Request $request, $id)
    {
        $gasto = Gasto::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'usuario_id' => 'required|exists:usuarios,id',
            'tipo' => 'required|string|in:' . implode(',', array_keys(Gasto::getTipos())),
            'detalle' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0.01',
            'fecha_gasto' => 'required|date|before_or_equal:today',
        ], [
            'usuario_id.required' => 'Debe seleccionar un usuario responsable.',
            'usuario_id.exists' => 'El usuario seleccionado no es válido.',
            'tipo.required' => 'Debe seleccionar un tipo de gasto.',
            'tipo.in' => 'El tipo de gasto seleccionado no es válido.',
            'detalle.required' => 'El detalle del gasto es obligatorio.',
            'detalle.max' => 'El detalle no puede exceder los 255 caracteres.',
            'monto.required' => 'El monto es obligatorio.',
            'monto.numeric' => 'El monto debe ser un número válido.',
            'monto.min' => 'El monto debe ser mayor a $0.00.',
            'fecha_gasto.required' => 'La fecha del gasto es obligatoria.',
            'fecha_gasto.date' => 'La fecha debe ser válida.',
            'fecha_gasto.before_or_equal' => 'La fecha no puede ser futura.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Por favor, corrige los errores del formulario.');
        }

        try {
            $gasto->update([
                'usuario_id' => $request->usuario_id,
                'tipo' => $request->tipo,
                'detalle' => $request->detalle,
                'monto' => $request->monto,
                'fecha_gasto' => $request->fecha_gasto,
            ]);

            return redirect()->route('admin.gastos.index')
                ->with('success', 'Gasto actualizado correctamente.')
                ->with('gasto_actualizado', $gasto->id);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el gasto. Por favor, inténtelo de nuevo.');
        }
    }

    /**
     * Eliminar un gasto.
     */
    public function destroy($id)
    {
        try {
            $gasto = Gasto::findOrFail($id);
            $montoEliminado = $gasto->monto;
            $detalleEliminado = $gasto->detalle;

            $gasto->delete();

            return redirect()->route('admin.gastos.index')
                ->with('success', "Gasto eliminado correctamente: $detalleEliminado (\${$montoEliminado})");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el gasto. Por favor, inténtelo de nuevo.');
        }
    }

    /**
     * Filtrar gastos por tipo.
     */
    public function filtrarPorTipo($tipo)
    {
        if (!array_key_exists($tipo, Gasto::getTipos())) {
            return redirect()->route('admin.gastos.index')
                ->with('error', 'Tipo de gasto no válido.');
        }

        $gastos = Gasto::byTipo($tipo)->with('usuario')->latest('fecha_gasto')->paginate(15);
        // Solo mostrar usuarios admin y empleados
        $usuarios = Usuario::whereIn('rol', ['admin', 'empleado'])
            ->orderBy('nombre', 'asc')
            ->get();
        $tipos = Gasto::getTipos();
        $tipoActual = $tipo;

        // Estadísticas específicas del tipo
        $estadisticas = [
            'total_mes' => Gasto::byTipo($tipo)->delMesActual()->sum('monto'),
            'total_gastos' => Gasto::byTipo($tipo)->count(),
            'promedio_diario' => Gasto::byTipo($tipo)->delMesActual()->avg('monto') ?? 0,
            'gasto_mayor' => Gasto::byTipo($tipo)->delMesActual()->max('monto') ?? 0,
        ];

        return view('admin.gastos.index', compact('gastos', 'usuarios', 'tipos', 'estadisticas', 'tipoActual'));
    }

    /**
     * Filtrar gastos entre fechas.
     */
    public function filtrarPorFechas(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ], [
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_fin.required' => 'La fecha de fin es obligatoria.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
        ]);

        $gastos = Gasto::betweenDates($request->fecha_inicio, $request->fecha_fin)
            ->with('usuario')
            ->latest('fecha_gasto')
            ->paginate(15);

        // Solo mostrar usuarios admin y empleados
        $usuarios = Usuario::whereIn('rol', ['admin', 'empleado'])
            ->orderBy('nombre', 'asc')
            ->get();
        $tipos = Gasto::getTipos();

        // Estadísticas del rango de fechas
        $estadisticas = [
            'total_mes' => Gasto::betweenDates($request->fecha_inicio, $request->fecha_fin)->sum('monto'),
            'total_gastos' => Gasto::betweenDates($request->fecha_inicio, $request->fecha_fin)->count(),
            'promedio_diario' => Gasto::betweenDates($request->fecha_inicio, $request->fecha_fin)->avg('monto') ?? 0,
            'gasto_mayor' => Gasto::betweenDates($request->fecha_inicio, $request->fecha_fin)->max('monto') ?? 0,
        ];

        $filtroFechas = [
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
        ];

        return view('admin.gastos.index', compact('gastos', 'usuarios', 'tipos', 'estadisticas', 'filtroFechas'));
    }

    /**
     * Resumen de estadísticas para dashboard.
     */
    public function resumen()
    {
        $resumen = [
            'total_mes_actual' => Gasto::delMesActual()->sum('monto'),
            'total_gastos' => Gasto::count(),
            'gastos_por_tipo' => Gasto::select('tipo', DB::raw('SUM(monto) as total'))
                ->delMesActual()
                ->groupBy('tipo')
                ->get()
                ->pluck('total', 'tipo'),
            'ultimos_gastos' => Gasto::with('usuario')
                ->latest('fecha_gasto')
                ->limit(5)
                ->get(),
        ];

        return response()->json($resumen);
    }

    /**
     * Estadísticas detalladas por tipo.
     */
    public function estadisticasPorTipo()
    {
        $estadisticas = Gasto::select(
                'tipo',
                DB::raw('COUNT(*) as cantidad'),
                DB::raw('SUM(monto) as total'),
                DB::raw('AVG(monto) as promedio'),
                DB::raw('MAX(monto) as maximo'),
                DB::raw('MIN(monto) as minimo')
            )
            ->groupBy('tipo')
            ->get()
            ->map(function ($item) {
                $tipos = Gasto::getTipos();
                return [
                    'tipo' => $item->tipo,
                    'tipo_formateado' => $tipos[$item->tipo] ?? $item->tipo,
                    'cantidad' => $item->cantidad,
                    'total' => $item->total,
                    'promedio' => round($item->promedio, 2),
                    'maximo' => $item->maximo,
                    'minimo' => $item->minimo,
                ];
            });

        return response()->json($estadisticas);
    }
}
