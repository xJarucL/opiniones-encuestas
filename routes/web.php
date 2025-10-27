<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\EncuestaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\PresentacionController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UsuarioMiddleware;
use Illuminate\Support\Facades\Auth;

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
Route::get('/presentacion/{preguntaId}', [PresentacionController::class, 'index'])->name('presentacion');
Route::get('/podio/{preguntaId}', [PresentacionController::class, 'podio'])->name('podio');
Route::get('/resultados/{preguntaId}', [PresentacionController::class, 'resultados'])->name('resultados');


// ==========================================================
// 2.5. RUTAS PARA TODOS LOS USUARIOS AUTENTICADOS (NUEVA SECCIÓN)
// ==========================================================
Route::middleware(['auth'])->group(function () {

    // LOGOUT
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    
    // EDICIÓN DE PERFIL (Movidas desde el grupo de Admin)
    // Ahora cualquier usuario autenticado puede editar su propio perfil.
    Route::get('/usuarios/editar/{id}', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/actualizar/{id}', [UserController::class, 'guardarUsuario'])->name('usuarios.update');

    // RUTA DE DEBUG (la tenías al final)
    Route::get('/debug-user', function () {
        if (Auth::check()) {
            $user = Auth::user();
            return response()->json([
                'autenticado' => true,
                'id' => $user->pk_usuario,
                'username' => $user->username,
                'email' => $user->email,
                'tipo_usuario' => $user->fk_tipo_user,
                'tipo_nombre' => $user->tipo_usuario->nombre ?? 'Sin tipo',
                'ruta_correcta' => $user->fk_tipo_user == 1 ? route('admin.dashboard') : route('inicio')
            ]);
        }
        
        return response()->json([
            'autenticado' => false,
            'mensaje' => 'No hay usuario autenticado'
        ]);
    })->name('debug.user');
});


// ==========================================================
// 3. RUTAS PARA USUARIOS NORMALES (Tipo 2)
// ==========================================================
Route::middleware(['auth', 'usuario'])->group(function () {

    // DASHBOARD USUARIO NORMAL
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('inicio');

    // PERFIL PERSONAL
    Route::get('/perfil', [UserController::class, 'perfil'])->name('perfil');

    // COMPAÑEROS
    Route::get('/usuarios/compañeros', [UserController::class, 'listarCompañeros'])->name('compañeros');
    Route::get('/usuarios/compañero/{id}', [UserController::class, 'mostrarCompañero'])->name('compañero.show');

    // COMENTARIOS
    Route::prefix('comentarios')->group(function () {
        Route::post('/usuarios/compañero/{id}/comentarios', [ComentarioController::class, 'store'])->name('comentarios.store');
        Route::post('/{comentario}/responder', [ComentarioController::class, 'reply'])->name('comentarios.reply');
        Route::delete('/{comentario}', [ComentarioController::class, 'destroy'])->name('comentarios.destroy');
        Route::patch('/{comentario}/editar', [ComentarioController::class, 'update'])->name('comentarios.update');
    });
});

// ==========================================================
// 4. RUTAS PARA ADMINISTRADORES (Tipo 1)
// ==========================================================
Route::middleware(['auth', 'admin'])->group(function () {

    // ---- PANEL CENTRAL ADMIN ----
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');
        
        // GESTIÓN DE COMENTARIOS
        Route::prefix('comentarios')->name('comentarios.')->group(function () {
            Route::get('/', [ComentarioController::class, 'index'])->name('index');
            Route::patch('/{comentario}/ocultar', [ComentarioController::class, 'hide'])->name('hide');
            Route::patch('/{comentario}/mostrar', [ComentarioController::class, 'showComment'])->name('show');
        });

        // GESTIÓN DE ENCUESTAS
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

        // GESTIÓN DE CATEGORÍAS
        Route::prefix('categorias')->name('categorias.')->group(function () {
            Route::get('/', [CategoriaController::class, 'index'])->name('index');
            Route::post('/guardar', [CategoriaController::class, 'store'])->name('store');
            Route::put('/{id}', [CategoriaController::class, 'update'])->name('update');
            Route::delete('/{id}', [CategoriaController::class, 'destroy'])->name('destroy');
        });
    });

    // ---- ADMINISTRACIÓN DE USUARIOS ----
    Route::prefix('usuarios')->name('usuarios.')->group(function () {
        // La ruta de la lista de usuarios se llama: 'usuarios.lista'
        Route::get('/', [UserController::class, 'listaUsuarios'])->name('lista');
        // La ruta de la lista de inactivos se llama: 'usuarios.inactivos'
        Route::get('/inactivos', [UserController::class, 'listaUsuarios_inactivos'])->name('inactivos');
        
        Route::get('/registro', function () {
            return view('users.formulario');
        })->name('registro');
        
        // --- RUTAS MOVIDAS ---
        // Route::get('/editar/{id}', [UserController::class, 'edit'])->name('edit'); // <-- MOVIDA
        // Route::put('/actualizar/{id}', [UserController::class, 'guardarUsuario'])->name('update'); // <-- MOVIDA
        
        // Esta ruta (POST) es para crear nuevos usuarios, lo cual es solo de Admin
        Route::post('/guardar', [UserController::class, 'guardarUsuario'])->name('guardar'); 
        
        // Estas rutas son solo de Admin
        Route::put('/cambiar-tipo/{id}', [UserController::class, 'cambiarTipo'])->name('cambiar-tipo');
        Route::delete('/eliminar/{id}', [UserController::class, 'eliminar'])->name('eliminar');
        Route::post('/restaurar/{id}', [UserController::class, 'restaurar'])->name('restaurar');
    });
});

// La ruta de LOGOUT y DEBUG se movieron al grupo de 'auth' general