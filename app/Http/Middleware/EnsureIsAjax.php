<?php

namespace App\Http\Middleware;

use Closure;

class EnsureIsAjax
{
    public function handle($request, Closure $next)
    {
        if (!$request->ajax() && !$request->wantsJson()) {
            if ($request->is('api/*')) {
                return response()->json(['error' => 'Petición no válida'], 400);
            }
            return abort(404);
        }

        return $next($request);
    }
}