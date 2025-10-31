<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UsuarioMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        if (auth()->user()->fk_tipo_user == 2) {
            return $next($request);
        }
        
        if (auth()->user()->fk_tipo_user == 1) {
            return redirect()->route('admin.dashboard');
        }
        
        abort(403, 'No tienes acceso a esta sección.');
    }
}