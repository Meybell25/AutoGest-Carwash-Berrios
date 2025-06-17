<?php

// En app/Http/Middleware/EnsureIsAjax.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureIsAjax
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->ajax() && !$request->wantsJson()) {
            return response()->json(['error' => 'Solo se aceptan peticiones AJAX'], 406);
        }

        return $next($request);
    }
}