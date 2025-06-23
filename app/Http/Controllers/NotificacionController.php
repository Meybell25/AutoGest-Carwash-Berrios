<?php
// app/Http/Controllers/NotificacionController.php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificacionController extends Controller
{
    /**
     * Obtener notificaciones de un usuario específico ordenadas por fecha_envio DESC
     */
    public function porUsuario($usuarioId, Request $request)
    {
        // Validar que el usuario exista
        $usuario = Usuario::findOrFail($usuarioId);

        // Consulta base
        $query = Notificacion::byUsuario($usuarioId)
                    ->orderBy('fecha_envio', 'DESC');

        // Filtrar por estado de lectura si se especifica
        if ($request->has('leido')) {
            $leido = filter_var($request->leido, FILTER_VALIDATE_BOOLEAN);
            $leido ? $query->leidas() : $query->noLeidas();
        }

        // Paginación
        $perPage = $request->per_page ?? 15;
        $notificaciones = $query->paginate($perPage);

        return response()->json([
            'usuario' => $usuario->only(['id', 'nombre', 'email']),
            'notificaciones' => $notificaciones
        ]);
    }

    /**
     * Marcar una notificación como leída
     */
    public function marcarLeida($id)
    {
        $notificacion = Notificacion::findOrFail($id);
        
        if ($notificacion->leido) {
            return response()->json([
                'message' => 'La notificación ya estaba marcada como leída',
                'notificacion' => $notificacion
            ], 200);
        }

        $notificacion->marcarComoLeida();
        
        return response()->json([
            'message' => 'Notificación marcada como leída correctamente',
            'notificacion' => $notificacion
        ]);
    }

    /**
     * Marcar todas las notificaciones de un usuario como leídas
     */
    public function marcarTodasLeidas($usuarioId)
    {
        $count = Notificacion::byUsuario($usuarioId)
                    ->noLeidas()
                    ->update(['leido' => true]);

        return response()->json([
            'message' => "{$count} notificaciones marcadas como leídas",
            'usuario_id' => $usuarioId
        ]);
    }

    /**
     * Obtener el conteo de notificaciones no leídas para un usuario
     */
    public function contarNoLeidas($usuarioId)
    {
        $count = Notificacion::byUsuario($usuarioId)
                    ->noLeidas()
                    ->count();

        return response()->json([
            'usuario_id' => $usuarioId,
            'no_leidas' => $count
        ]);
    }
}