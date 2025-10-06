<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Middleware\AdminMiddleware;

// LOGIN (solo para invitados)
Route::get('/', function () {
    return view('login');
})->middleware('guest')->name('login');

Route::post('/iniciando_sesion', [UserController::class, 'login'])
    ->middleware('guest')
    ->name('iniciando');

Route::get('/recuperar_contraseña', function () {
    return view('passwordrecovery.recuperar_contraseña');
})->middleware('guest')->name('recuperar_contraseña');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])
    ->middleware('guest')
    ->name('password.update');

// RUTAS ASEGURADAS
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('inicio');

    // Perfil personal (accesible por cualquier usuario)
    Route::get('/perfil', [UserController::class, 'perfil'])->name('perfil');
    Route::get('/editar/{id}', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('/editando/{id}', [UserController::class, 'guardarUsuario'])->name('usuarios.update');

    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    // Rutas de usuarios que requieren ser administrador
    Route::middleware([AdminMiddleware::class])->prefix('usuarios')->group(function () {
        Route::get('/', [UserController::class, 'listaUsuarios'])->name('lista_usuarios');
        Route::put('/cambiar-tipo/{id}', [UserController::class, 'cambiarTipo'])->name('usuarios.cambiar-tipo');
        Route::get('/registro', function () { return view('users.formulario'); })->name('usuarios.registro');
        Route::post('/guardar', [UserController::class, 'guardarUsuario'])->name('guardar.user');
        Route::delete('/delete/{id}', [UserController::class, 'eliminar'])->name('usuarios.eliminar');
        Route::post('/restaurar/{id}', [UserController::class, 'restaurar'])->name('usuarios.restaurar');
        Route::get('/inactivos', [UserController::class, 'listaUsuarios_inactivos'])->name('lista_usuarios_inactivos');
    });

    // Compañeros (accesible por cualquier usuario)
    Route::get('/usuarios/compañeros', [UserController::class, 'listarCompañeros'])->name('compañeros');
    Route::get('/usuarios/compañero/{id}', [UserController::class, 'mostrarCompañero'])->name('compañero.show');

    Route::post('/usuarios/compañero/{id}/comentarios', [ComentarioController::class, 'store'])->name('comentarios.store');
    Route::post('/comentarios/{comentario}/responder', [ComentarioController::class, 'reply'])->name('comentarios.reply');
    Route::delete('/comentarios/{comentario}', [ComentarioController::class, 'destroy'])->name('comentarios.destroy');

    Route::middleware('can:moderate-comments')->group(function () {
        Route::patch('/comentarios/{comentario}/ocultar', [ComentarioController::class, 'hide'])->name('comentarios.hide');
        Route::patch('/comentarios/{comentario}/mostrar', [ComentarioController::class, 'show'])->name('comentarios.show');
    });
});
