<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request){

        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $usuario = User::where('email', $request->email)->first();


        if ($usuario && Hash::check($request->password, $usuario->password)) {
            Auth::login($usuario);

            return response()->json([
                'mensaje' => '¡Inicio de sesión exitoso!',
                'ruta' => route('inicio'),
                'class' => 'success'
            ]);

        } else {
            return response()->json([
                'mensaje' => 'Credenciales incorrectas.',
                'class' => 'error'
            ], 422);
        }
    }
}
