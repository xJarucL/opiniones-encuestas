<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->fk_tipo_user == 1) {
            return $next($request);
        }

        return redirect()->route('inicio')->with('error', 'No tienes permisos para acceder a esta sección.');
    }
}
