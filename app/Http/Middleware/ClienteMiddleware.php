<?php
// app/Http/Middleware/ClienteMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // ← Agregar esta línea

class ClienteMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !Auth::user()->isCliente()) { 
            abort(403, 'Acceso denegado. Solo clientes pueden acceder.');
        }

        return $next($request);
    }
}