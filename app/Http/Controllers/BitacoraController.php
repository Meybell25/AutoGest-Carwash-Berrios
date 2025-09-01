<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\Response;



class BitacoraController extends Controller
{
    public function index(Request $request): View
    {
        $query = Bitacora::with('usuario')->orderByDesc('fecha');

        if ($request->filled('usuario_id')) {
            $query->where('usuario_id', $request->usuario_id);
        }
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha', '<=', $request->fecha_fin);
        }

        $logs = $query->paginate(20)->withQueryString();
        $usuarios = Usuario::orderBy('nombre')->pluck('nombre', 'id');

        return view('BitacoraViews.index', compact('logs', 'usuarios'));
    }

    /**
     * Exportar bitácora a Excel
     */
    public function exportExcel(Request $request)
    {
        try {
            // Obtener todos los registros con los filtros aplicados (sin paginación)
            $query = Bitacora::with('usuario')->orderByDesc('fecha');

            if ($request->filled('usuario_id')) {
                $query->where('usuario_id', $request->usuario_id);
            }
            if ($request->filled('fecha_inicio')) {
                $query->whereDate('fecha', '>=', $request->fecha_inicio);
            }
            if ($request->filled('fecha_fin')) {
                $query->whereDate('fecha', '<=', $request->fecha_fin);
            }

            $logs = $query->get();

            // Preparar datos para Excel
            $excelData = $logs->map(function ($log) {
                return [
                    'Fecha' => $log->fecha->format('d/m/Y H:i:s'),
                    'Usuario' => $log->usuario->nombre ?? 'Sistema',
                    'Acción' => $log->accion,
                    'IP' => $log->ip
                ];
            });

            // Crear respuesta JSON con los datos
            return response()->json([
                'success' => true,
                'data' => $excelData,
                'total' => $logs->count(),
                'fecha_generacion' => now()->format('d/m/Y H:i:s')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar el archivo Excel: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar bitácora a PDF
     */
    public function exportPdf(Request $request)
    {
        try {
            // Obtener todos los registros con los filtros aplicados (sin paginación)
            $query = Bitacora::with('usuario')->orderByDesc('fecha');

            if ($request->filled('usuario_id')) {
                $query->where('usuario_id', $request->usuario_id);
            }
            if ($request->filled('fecha_inicio')) {
                $query->whereDate('fecha', '>=', $request->fecha_inicio);
            }
            if ($request->filled('fecha_fin')) {
                $query->whereDate('fecha', '<=', $request->fecha_fin);
            }

            $logs = $query->get();

            // Preparar datos para PDF
            $pdfData = $logs->map(function ($log) {
                return [
                    $log->fecha->format('d/m/Y H:i:s'),
                    $log->usuario->nombre ?? 'Sistema',
                    $log->accion,
                    $log->ip
                ];
            });

            // Información adicional
            $filtrosAplicados = [];
            if ($request->filled('usuario_id')) {
                $usuario = Usuario::find($request->usuario_id);
                $filtrosAplicados[] = "Usuario: " . ($usuario->nombre ?? 'Desconocido');
            }
            if ($request->filled('fecha_inicio')) {
                $filtrosAplicados[] = "Desde: " . $request->fecha_inicio;
            }
            if ($request->filled('fecha_fin')) {
                $filtrosAplicados[] = "Hasta: " . $request->fecha_fin;
            }

            // Crear respuesta JSON con los datos
            return response()->json([
                'success' => true,
                'data' => $pdfData,
                'total' => $logs->count(),
                'fecha_generacion' => now()->format('d/m/Y H:i:s'),
                'filtros' => $filtrosAplicados
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar el archivo PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}