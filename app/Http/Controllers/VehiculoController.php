<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class VehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        if (Auth::user()->rol === 'cliente') {
            $vehiculos = Vehiculo::where('usuario_id', Auth::id())->get();
        } else {
            $vehiculos = Vehiculo::all();
        }

        return view('VehiculosViews.index', compact('vehiculos'));
    }

    
    public function create(): View
    {
        return view('VehiculosViews.create');
    }

   
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'marca' => 'nullable|string',
            'modelo' => 'nullable|string',
            'tipo' => 'nullable|string',
            'color' => 'nullable|string',
            'descripcion' => 'nullable|string',
            'placa' => 'required|unique:vehiculos,placa',
        ]);

        $validated['usuario_id'] = Auth::id();
        $validated['fecha_registro'] = now();

        Vehiculo::create($validated);

        return redirect()->back()->with('success', 'Vehículo creado correctamente');
    }


    public function edit(Vehiculo $vehiculo): View
    {
        if (Auth::user()->rol === 'cliente' && $vehiculo->usuario_id !== Auth::id()) {
            abort(403);
        }

        return view('VehiculosViews.edit', compact('vehiculo'));
    }

 
    public function update(Request $request, Vehiculo $vehiculo): RedirectResponse
    {
        if (Auth::user()->rol === 'cliente' && $vehiculo->usuario_id !== Auth::id()) {
            abort(403);
        }

        if (Auth::user()->rol === 'empleado') {
            abort(403);
        }

        $validated = $request->validate([
            'marca' => 'nullable|string',
            'modelo' => 'nullable|string',
            'tipo' => 'nullable|string',
            'color' => 'nullable|string',
            'descripcion' => 'nullable|string',
            'placa' => 'required|unique:vehiculos,placa,' . $vehiculo->id,
        ]);

        $vehiculo->update($validated);

             return redirect()->back()->with('success', 'Vehículo actualizado correctamente');
    }


    public function destroy(Vehiculo $vehiculo): RedirectResponse
    {
        $user = Auth::user();

        if ($user->rol === 'empleado') {
            abort(403);
        }

        if ($user->rol === 'cliente' && $vehiculo->usuario_id !== $user->id) {
            abort(403);
        }

        $vehiculo->delete();

         return redirect()->back()->with('success', 'Vehículo eliminado correctamente');
    }
}