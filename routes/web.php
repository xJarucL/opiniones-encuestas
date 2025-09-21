<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('login');
});

Route::post('/iniciando_sesion', [UserController::class, 'login']) -> name('iniciando');

Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('inicio');

