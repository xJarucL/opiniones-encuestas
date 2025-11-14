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
use App\Http\Controllers\PresentaciontwoController;
use App\Http\Controllers\DiagnosticoController; // ← AGREGADO
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

// Ruta POST para guardar el voto
Route::post('/presentacion/store', [PresentacionController::class, 'store'])->name('presentacion.store');

Route::get('/podio/{preguntaId}', [PresentacionController::class, 'podio'])->name('podio');
Route::get('/resultados/{preguntaId}', [PresentacionController::class, 'resultados'])->name('resultados');


// ==========================================================
// 2.5. RUTAS PARA TODOS LOS USUARIOS AUTENTICADOS
// ==========================================================
Route::middleware(['auth'])->group(function () {

    // LOGOUT
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    
    // EDICIÓN DE PERFIL
    Route::get('/usuarios/editar/{id}', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/actualizar/{id}', [UserController::class, 'guardarUsuario'])->name('usuarios.update');

    // RUTA DE DEBUG
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

    // RUTA DE PRUEBA PARA DIAGNÓSTICO DEL PODIO
    Route::get('/test-podio/{encuestaId}/{preguntaIndex}', function($encuestaId, $preguntaIndex) {
        $preguntas = DB::table('preguntas')
            ->where('encuesta_id', $encuestaId)
            ->orderBy('id')
            ->get();
        
        if ($preguntas->isEmpty()) {
            return response()->json([
                'error' => 'No hay preguntas para esta encuesta',
                'encuesta_id' => $encuestaId
            ]);
        }
        
        if (!isset($preguntas[$preguntaIndex])) {
            return response()->json([
                'error' => 'Índice de pregunta no válido',
                'pregunta_index' => $preguntaIndex,
                'total_preguntas' => $preguntas->count(),
                'preguntas_disponibles' => $preguntas
            ]);
        }
        
        $pregunta = $preguntas[$preguntaIndex];
        
        $topRespuestas = DB::table('respuestas')
            ->select(
                'respuesta',
                DB::raw('COUNT(*) as total')
            )
            ->where('pregunta_id', $pregunta->id)
            ->whereNotNull('respuesta')
            ->where('respuesta', '!=', '')
            ->groupBy('respuesta')
            ->orderBy('total', 'DESC')
            ->limit(3)
            ->get();
        
        return response()->json([
            'pregunta_id' => $pregunta->id,
            'pregunta_texto' => $pregunta->texto,
            'total_preguntas_encuesta' => $preguntas->count(),
            'total_respuestas' => $topRespuestas,
            'count' => $topRespuestas->count()
        ]);
    });

    // Presentación normal
    Route::get('/presentacion/{preguntaId}', [PresentacionController::class, 'index'])->name('presentacion');
    Route::get('/podio/{preguntaId}', [PresentacionController::class, 'podio'])->name('podio');
    Route::get('/resultados/{preguntaId}', [PresentacionController::class, 'resultados'])->name('resultados');

    // Presentación de varias preguntas
    Route::get('/presentacionone/{encuestaId}', [PresentaciontwoController::class, 'index'])->name('presentacionone');
    Route::get('/presentaciontwo/{encuestaId}/{preguntaIndex}', [PresentaciontwoController::class, 'inicio'])->name('presentaciontwo.inicio');
    Route::get('/podiotwo/{encuestaId}/{preguntaIndex}', [PresentaciontwoController::class, 'podio'])->name('podiotwo');
    Route::get('/resultadostwo/{encuestaId}', [PresentaciontwoController::class, 'resultados'])->name('resultadostwo');

    // Validar cantidad de preguntas
    Route::get('/validarButton/{encuestaId}', [PresentaciontwoController::class, 'validar'])->name('validarButton');
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

    // RUTA DE ENCUESTAS PARA USUARIOS
    Route::get('/encuestas', [EncuestaController::class, 'showPublicIndex'])->name('encuestas.index');

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
            Route::delete('/{comentario}', [ComentarioController::class, 'destroyAdmin'])->name('destroy');
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

        // HERRAMIENTAS DE DIAGNÓSTICO
        Route::prefix('diagnostico')->name('diagnostico.')->group(function () {
            Route::get('/{encuestaId}', [DiagnosticoController::class, 'diagnosticar'])->name('ver');
            Route::post('/{encuestaId}/corregir', [DiagnosticoController::class, 'corregirRespuestasVacias'])->name('corregir');
        });
    });

    // ---- ADMINISTRACIÓN DE USUARIOS ----
    Route::prefix('usuarios')->name('usuarios.')->group(function () {
        
        Route::get('/', [UserController::class, 'listaUsuarios'])->name('lista');
        Route::get('/inactivos', [UserController::class, 'listaUsuarios_inactivos'])->name('inactivos');
        
        Route::get('/registro', function () {
            $tipos_usuario = \App\Models\Tipo_usuario::all(); 
            return view('users.formulario', ['usuario' => null, 'tipos_usuario' => $tipos_usuario]);
        })->name('registro');

        Route::post('/guardar', [UserController::class, 'guardarUsuario'])->name('guardar');
        Route::put('/cambiar-tipo/{id}', [UserController::class, 'cambiarTipo'])->name('cambiar-tipo');
        Route::delete('/eliminar/{id}', [UserController::class, 'eliminar'])->name('eliminar');
        Route::post('/restaurar/{id}', [UserController::class, 'restaurar'])->name('restaurar'); 
        Route::delete('/eliminar-permanente/{id}', [UserController::class, 'eliminarPermanente'])->name('eliminar-permanente');

    });
});