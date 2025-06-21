<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class ServicioController extends Controller
{
    public function __construct()
    {
        // Solo requerir autenticación para crear, editar o eliminar
        $this->middleware('auth:api')->except(['index', 'show']);
        
        // Verificar que sea administrador para acciones sensibles
        $this->middleware(function ($request, $next) {
            if (!Gate::allows('is-admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acción no autorizada. Se requiere rol de administrador'
                ], 403);
            }
            return $next($request);
        })->only(['store', 'update', 'destroy']);
    }

    /**
     * Obtener listado de todos los servicios
     */
    public function index()
    {
        try {
            $servicios = Servicio::with('citas')->get();
            
            return response()->json([
                'success' => true,
                'data' => $servicios
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el listado de servicios',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear un nuevo servicio (solo admin)
     */
    public function store(Request $request)
    {
        $reglasValidacion = [
            'nombre' => 'required|string|max:100|unique:servicios,nombre',
            'descripcion' => 'nullable|string|max:255',
            'precio' => 'required|numeric|min:0.01',
            'duracion_min' => 'required|integer|min:5',
            'activo' => 'boolean',
            'categoria' => 'required|string|max:50'
        ];

        $validator = Validator::make($request->all(), $reglasValidacion);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $servicio = Servicio::create($request->all());

            return response()->json([
                'success' => true,
                'data' => $servicio,
                'message' => 'Servicio creado correctamente'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el servicio',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar un servicio específico
     */
    public function show($id)
    {
        try {
            $servicio = Servicio::with('citas')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $servicio
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Servicio no encontrado',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Actualizar un servicio (solo admin)
     */
    public function update(Request $request, $id)
    {
        try {
            $servicio = Servicio::findOrFail($id);

            $reglasValidacion = [
                'nombre' => 'sometimes|required|string|max:100|unique:servicios,nombre,'.$servicio->id,
                'descripcion' => 'nullable|string|max:255',
                'precio' => 'sometimes|required|numeric|min:0.01',
                'duracion_min' => 'sometimes|required|integer|min:5',
                'activo' => 'sometimes|boolean',
                'categoria' => 'sometimes|required|string|max:50'
            ];

            $validator = Validator::make($request->all(), $reglasValidacion);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $servicio->update($request->all());

            return response()->json([
                'success' => true,
                'data' => $servicio,
                'message' => 'Servicio actualizado correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el servicio',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un servicio (solo admin)
     */
    public function destroy($id)
    {
        try {
            $servicio = Servicio::findOrFail($id);

            // Verificar si el servicio tiene citas asociadas
            if ($servicio->citas()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el servicio porque tiene citas asociadas'
                ], 409);
            }

            $servicio->delete();

            return response()->json([
                'success' => true,
                'message' => 'Servicio eliminado correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el servicio',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener servicios activos por categoría
     */
    public function porCategoria($categoria)
    {
        try {
            $servicios = Servicio::activos()
                ->byCategoria($categoria)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $servicios
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener servicios por categoría',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}