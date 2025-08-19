<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\View\View;



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
}