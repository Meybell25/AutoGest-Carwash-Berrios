<?php
// app/Http/Controllers/DiaNoLaborableController.php

namespace App\Http\Controllers;

use App\Models\DiaNoLaborable;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DiaNoLaborableController extends Controller
{
    public function index()
    {
        $diasNoLaborables = DiaNoLaborable::orderBy('fecha', 'asc')->get();
        return view('admin.dias-no-laborables.index', compact('diasNoLaborables'));
    }

    public function create()
    {
        $motivos = DiaNoLaborable::getMotivosDisponibles();
        return view('admin.dias-no-laborables.create', compact('motivos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date|after_or_equal:today',
            'motivo' => 'nullable|string|max:255'
        ]);

        DiaNoLaborable::create([
            'fecha' => $request->fecha,
            'motivo' => $request->motivo
        ]);

        return redirect()->route('admin.dias-no-laborables.index')
            ->with('success', 'Día no laborable agregado correctamente');
    }

    public function show(DiaNoLaborable $diasNoLaborable)
    {
        return view('admin.dias-no-laborables.show', compact('diasNoLaborable'));
    }

    public function edit(DiaNoLaborable $diasNoLaborable)
    {
        $motivos = DiaNoLaborable::getMotivosDisponibles();
        return view('admin.dias-no-laborables.edit', compact('diasNoLaborable', 'motivos'));
    }

    public function update(Request $request, DiaNoLaborable $diasNoLaborable)
    {
        $request->validate([
            'fecha' => 'required|date',
            'motivo' => 'nullable|string|max:255'
        ]);

        $diasNoLaborable->update([
            'fecha' => $request->fecha,
            'motivo' => $request->motivo
        ]);

        return redirect()->route('admin.dias-no-laborables.index')
            ->with('success', 'Día no laborable actualizado correctamente');
    }

    public function destroy(DiaNoLaborable $diasNoLaborable)
    {
        $diasNoLaborable->delete();
        return redirect()->route('admin.dias-no-laborables.index')
            ->with('success', 'Día no laborable eliminado correctamente');
    }

    // API Methods
    public function getProximos()
    {
        $proximos = DiaNoLaborable::where('fecha', '>=', now())
            ->orderBy('fecha', 'asc')
            ->take(5)
            ->get();
            
        return response()->json($proximos);
    }

    public function getDelMes()
    {
        $inicioMes = now()->startOfMonth();
        $finMes = now()->endOfMonth();
        
        $dias = DiaNoLaborable::whereBetween('fecha', [$inicioMes, $finMes])
            ->orderBy('fecha', 'asc')
            ->get();
            
        return response()->json($dias);
    }
}