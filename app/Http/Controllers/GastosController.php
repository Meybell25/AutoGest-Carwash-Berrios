<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GastoController extends Controller
{
    /**
     * Mostrar lista de gastos.
     */
    public function index()
    {
        $gastos = Gasto::with('usuario')->latest()->paginate(10);
        return view('gastos.index', compact('gastos'));
    }

    /**
     * Mostrar formulario para crear un nuevo gasto.
     */
    public function create()
    {
        $usuarios = Usuario::all();
        $tipos = Gasto::getTipos();
        return view('gastos.create', compact('usuarios', 'tipos'));
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
            'monto' => 'required|numeric|min:0',
            'fecha_gasto' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Gasto::create($request->all());

        return redirect()->route('gastos.index')->with('success', 'Gasto registrado correctamente.');
    }

    /**
     * Mostrar un gasto específico.
     */
    public function show($id)
    {
        $gasto = Gasto::with('usuario')->findOrFail($id);
        return view('gastos.show', compact('gasto'));
    }

    /**
     * Mostrar formulario para editar un gasto.
     */
    public function edit($id)
    {
        $gasto = Gasto::findOrFail($id);
        $usuarios = Usuario::all();
        $tipos = Gasto::getTipos();
        return view('gastos.edit', compact('gasto', 'usuarios', 'tipos'));
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
            'monto' => 'required|numeric|min:0',
            'fecha_gasto' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $gasto->update($request->all());

        return redirect()->route('gastos.index')->with('success', 'Gasto actualizado correctamente.');
    }

    /**
     * Eliminar un gasto.
     */
    public function destroy($id)
    {
        $gasto = Gasto::findOrFail($id);
        $gasto->delete();

        return redirect()->route('gastos.index')->with('success', 'Gasto eliminado correctamente.');
    }

    /**
     * Filtrar gastos por tipo.
     */
    public function filtrarPorTipo($tipo)
    {
        $gastos = Gasto::byTipo($tipo)->with('usuario')->paginate(10);
        return view('gastos.index', compact('gastos'));
    }

    /**
     * Filtrar gastos entre fechas.
     */
    public function filtrarPorFechas(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $gastos = Gasto::betweenDates($request->fecha_inicio, $request->fecha_fin)->with('usuario')->paginate(10);
        return view('gastos.index', compact('gastos'));
    }
}
