<?php
// app/Http/Controllers/NotificacionController.php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Usuario;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificacionController extends Controller
{
public function marcarComoLeida(Notificacion $notificacion)
{
    if ($notificacion->usuario_id !== auth()->id()) {
        abort(403);
    }

    $notificacion->update(['leido' => true]);
    
    return response()->json(['success' => true]);
}

}