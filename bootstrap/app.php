<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UsuarioMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Registrar alias de middleware
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'usuario' => UsuarioMiddleware::class,
        ]);

        // Configurar redirección para usuarios no autenticados
        $middleware->redirectGuestsTo(fn () => route('login'));
        
        // Configurar redirección para usuarios ya autenticados
        $middleware->redirectUsersTo(function () {
            $user = auth()->user();
            
            if (!$user) {
                return route('login');
            }
            
            return match($user->fk_tipo_user) {
                1 => route('admin.dashboard'),
                2 => route('inicio'),
                default => route('login'),
            };
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();