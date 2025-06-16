<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FixRailwayCookies
{
    public function handle(Request $request, Closure $next)
    {
        config([
            'session.secure' => false,
            'session.same_site' => 'lax',
            'session.http_only' => true,
        ]);
        
        return $next($request);
    }
}