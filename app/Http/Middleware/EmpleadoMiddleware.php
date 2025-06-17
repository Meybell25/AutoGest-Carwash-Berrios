<?php
// app/Http/Middleware/EmpleadoMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // ← Agregar esta línea

class EmpleadoMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !Auth::user()->isEmpleado()) { 
            abort(403, 'Acceso denegado. Solo empleados pueden acceder.');
        }

        return $next($request);
    }
}