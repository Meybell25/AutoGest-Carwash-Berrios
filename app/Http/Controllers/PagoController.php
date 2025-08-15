<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::with('cita')->get();
        return view('PagosViews.index', compact('pagos'));
    }

    public function create()
    {
        $citas = Cita::all();
        return view('PagosViews.create', compact('citas'));
    }

    public function store(Request $request)
    {
        $data = $request->all();

        // Validar que no haya doble pago para la misma cita
        $existePago = Pago::where('cita_id', $data['cita_id'])
            ->where('estado', Pago::ESTADO_COMPLETADO)
            ->exists();

        if ($existePago) {
            return back()->withErrors(['cita_id' => 'Esta cita ya tiene un pago completado.'])->withInput();
        }

        // Calcular vuelto si es efectivo
        $vuelto = 0;
        if ($data['metodo'] === Pago::METODO_EFECTIVO) {
            if ($data['monto_recibido'] < $data['monto']) {
                return back()->withErrors(['monto_recibido' => 'El monto recibido es menor al monto a pagar.'])->withInput();
            }
            $vuelto = $data['monto_recibido'] - $data['monto'];
        }

        $pago = new Pago();
        $pago->fill($data);
        $pago->vuelto = $vuelto;
        $pago->fecha_pago = now();
        $pago->estado = Pago::ESTADO_COMPLETADO;
        $pago->save();

        return redirect()->route('pagos.index')->with('success', 'Pago registrado correctamente.');
    }

    public function show($id)
    {
        $pago = Pago::with('cita')->findOrFail($id);
        return view('PagosViews.show', compact('pago'));
    }

    public function edit($id)
    {
        $pago = Pago::findOrFail($id);
        $citas = Cita::all();
        return view('PagosViews.edit', compact('pago', 'citas'));
    }

    public function update(Request $request, $id)
    {
        $pago = Pago::findOrFail($id);
        $data = $request->all();

        if ($data['metodo'] === Pago::METODO_EFECTIVO) {
            if ($data['monto_recibido'] < $data['monto']) {
                return back()->withErrors(['monto_recibido' => 'El monto recibido es menor al monto a pagar.'])->withInput();
            }
            $data['vuelto'] = $data['monto_recibido'] - $data['monto'];
        } else {
            $data['vuelto'] = 0;
        }

        $pago->update($data);
        return redirect()->route('pagos.index')->with('success', 'Pago actualizado correctamente.');
    }

    public function destroy($id)
    {
        $pago = Pago::findOrFail($id);
        $pago->delete();

        return redirect()->route('pagos.index')->with('success', 'Pago eliminado correctamente.');
    }
}