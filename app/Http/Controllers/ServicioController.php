<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ServicioController extends Controller
{
    public function __construct()
    {
        // Middleware para autenticación web
        $this->middleware('auth');
        
        // Middleware para verificar rol de admin
        $this->middleware(function ($request, $next) {
            if (!Gate::allows('is-admin')) {
                abort(403, 'Acción no autorizada. Se requiere rol de administrador');
            }
            return $next($request);
        })->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Mostrar listado de servicios
     */
    public function index()
    {
        $servicios = Servicio::all();
        return view('admin.servicios.index', compact('servicios'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('admin.servicios.create');
    }

    /**
     * Almacenar nuevo servicio
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:servicios,nombre',
            'descripcion' => 'nullable|string|max:255',
            'precio' => 'required|numeric|min:0.01',
            'duracion_min' => 'required|integer|min:5',
            'categoria' => 'required|string|max:50'
        ]);

        try {
            Servicio::create($request->all());
           // $servicio = Servicio::create($request->all());
            Bitacora::registrar(Bitacora::ACCION_CREAR_SERVICIO, null, $request->ip());

            return redirect()->route('admin.servicios.index')
                ->with('success', 'Servicio creado correctamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear el servicio: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $servicio = Servicio::findOrFail($id);
        return view('admin.servicios.edit', compact('servicio'));
    }

    /**
     * Actualizar servicio
     */
    public function update(Request $request, $id)
    {
        $servicio = Servicio::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100|unique:servicios,nombre,'.$servicio->id,
            'descripcion' => 'nullable|string|max:255',
            'precio' => 'required|numeric|min:0.01',
            'duracion_min' => 'required|integer|min:5',
            'categoria' => 'required|string|max:50'
        ]);

        try {
            $servicio->update($request->all());
            Bitacora::registrar(Bitacora::ACCION_ACTUALIZAR_SERVICIO, null, $request->ip());

            return redirect()->route('admin.servicios.index')
                ->with('success', 'Servicio actualizado correctamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar el servicio: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Eliminar servicio
     */
    public function destroy($id)
    {
        try {
            $servicio = Servicio::findOrFail($id);

            if ($servicio->citas()->exists()) {
                return redirect()->route('admin.servicios.index')
                    ->with('error', 'No se puede eliminar el servicio porque tiene citas asociadas');
            }

            $servicio->delete();
            Bitacora::registrar(Bitacora::ACCION_ELIMINAR_SERVICIO, null, request()->ip());


            return redirect()->route('admin.servicios.index')
                ->with('success', 'Servicio eliminado correctamente');

        } catch (\Exception $e) {
            return redirect()->route('admin.servicios.index')
                ->with('error', 'Error al eliminar el servicio: ' . $e->getMessage());
        }
    }

    /**
     * Vista de servicios para admin dashboard
     */
    public function adminIndex()
    {
        $servicios = Servicio::all();
        return view('admin.servicios.index', compact('servicios'));
    }
}