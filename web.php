<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\EncuestaController;
use App\Http\Controllers\PresentacionController;
use App\Http\Middleware\AdminMiddleware;

// ==========================================================
// 1. RUTAS DE ACCESO (Middleware: guest)
// ==========================================================
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

// ==========================================================
// 2. RUTAS PÚBLICAS (Sin autenticación)
// ==========================================================
Route::get('/presentacion', [PresentacionController::class, 'index'])->name('presentacion');
Route::get('/podio', [PresentacionController::class, 'podio'])->name('podio');
Route::get('/resultados', [PresentacionController::class, 'resultados'])->name('resultados');

// ==========================================================
// 3. RUTAS ASEGURADAS (Middleware: auth)
// ==========================================================
Route::middleware(['auth'])->group(function () {

    // DASHBOARD GENERAL
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('inicio');

    // =====================================================
    // PERFIL PERSONAL (accesible por cualquier usuario)
    // =====================================================
    Route::get('/perfil', [UserController::class, 'perfil'])->name('perfil');
    Route::get('/editar/{id}', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('/editando/{id}', [UserController::class, 'guardarUsuario'])->name('usuarios.update');

    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    // =====================================================
    // COMPAÑEROS (accesible por cualquier usuario)
    // =====================================================
    Route::get('/usuarios/compañeros', [UserController::class, 'listarCompañeros'])->name('compañeros');
    Route::get('/usuarios/compañero/{id}', [UserController::class, 'mostrarCompañero'])->name('compañero.show');

    // =====================================================
    // COMENTARIOS (Rutas para usuarios normales)
    // =====================================================
    Route::prefix('comentarios')->group(function () {
        // Crear comentario en perfil de usuario
        Route::post('/usuarios/compañero/{id}/comentarios', [ComentarioController::class, 'store'])
            ->name('comentarios.store');
        
        // Responder a un comentario
        Route::post('/{comentario}/responder', [ComentarioController::class, 'reply'])
            ->name('comentarios.reply');
        
        // Eliminar comentario (propietario o admin)
        Route::delete('/{comentario}', [ComentarioController::class, 'destroy'])
            ->name('comentarios.destroy');
        
        // Editar comentario (propietario)
        Route::patch('/{comentario}/editar', [ComentarioController::class, 'update'])
            ->name('comentarios.update');
    });

    // =====================================================
    // ADMINISTRACIÓN DE USUARIOS (Middleware: AdminMiddleware)
    // =====================================================
    Route::middleware([AdminMiddleware::class])->prefix('usuarios')->group(function () {
        Route::get('/', [UserController::class, 'listaUsuarios'])->name('lista_usuarios');
        Route::put('/cambiar-tipo/{id}', [UserController::class, 'cambiarTipo'])->name('usuarios.cambiar-tipo');
        Route::get('/registro', function () { return view('users.formulario'); })->name('usuarios.registro');
        Route::post('/guardar', [UserController::class, 'guardarUsuario'])->name('guardar.user');
        Route::delete('/delete/{id}', [UserController::class, 'eliminar'])->name('usuarios.eliminar');
        Route::post('/restaurar/{id}', [UserController::class, 'restaurar'])->name('usuarios.restaurar');
        Route::get('/inactivos', [UserController::class, 'listaUsuarios_inactivos'])->name('lista_usuarios_inactivos');
    });

    // =====================================================
    // PANEL DE ADMINISTRACIÓN CENTRAL
    // =====================================================
    Route::middleware([AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
        
        // Vista principal del panel de administración
        Route::get('/', function () {
            return view('admin.dashboard'); 
        })->name('dashboard');

        // =====================================================
        // GESTIÓN DE COMENTARIOS
        // =====================================================
        Route::prefix('comentarios')->name('comentarios.')->group(function () {
            // Listado de comentarios para moderación
            Route::get('/', [ComentarioController::class, 'listForAdmin'])->name('index');
            
            // Ocultar comentario (solo admin)
            Route::patch('/{comentario}/ocultar', [ComentarioController::class, 'hide'])->name('hide');
            
            // Mostrar comentario oculto (solo admin)
            Route::patch('/{comentario}/mostrar', [ComentarioController::class, 'showComment'])->name('show');
        });

        // =====================================================
        // GESTIÓN DE ENCUESTAS
        // =====================================================
        Route::prefix('encuestas')->name('encuestas.')->group(function () {
            Route::get('/', [EncuestaController::class, 'index'])->name('index');
            Route::get('/crear', [EncuestaController::class, 'create'])->name('create');
            Route::post('/guardar', [EncuestaController::class, 'store'])->name('store');
            Route::get('/{id}', [EncuestaController::class, 'show'])->name('show');
            Route::get('/{id}/editar', [EncuestaController::class, 'edit'])->name('edit');
            Route::put('/{id}', [EncuestaController::class, 'update'])->name('update');
            Route::delete('/{id}', [EncuestaController::class, 'destroy'])->name('destroy');
            Route::patch('/{id}/estado', [EncuestaController::class, 'cambiarEstado'])->name('cambiar-estado');
        });
    });
});