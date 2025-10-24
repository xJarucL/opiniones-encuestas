<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tipo_usuario;
use App\Models\Comentario;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

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

    public function listaUsuarios(){
        $usuarios = User::with('tipo_usuario')->paginate(10);
        $tipos_usuario = Tipo_usuario::all();

        return view('users.listado', compact('usuarios', 'tipos_usuario'));
    }

    public function cambiarTipo(Request $request, $id){
        $request->validate([
            'fk_tipo_user' => 'required|exists:tipo_user,pk_tipo_user',
        ]);

        $usuario = User::withTrashed()->findOrFail($id);
        $usuario->fk_tipo_user = $request->fk_tipo_user;
        $usuario->save();

        return back()->with('success', 'Tipo de usuario actualizado correctamente.');
    }

    public function eliminar($id){
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect()->route('lista_usuarios')->with('success', 'Usuario eliminado correctamente.');
    }

    public function restaurar($id){
        $usuario = User::withTrashed()->findOrFail($id);
        $usuario->restore();

        return redirect()->route('lista_usuarios_inactivos')->with('success', 'Usuario restaurado correctamente.');
    }

    public function listaUsuarios_inactivos(){
        $usuarios = User::onlyTrashed()->with('tipo_usuario')->paginate(10);
        $tipos_usuario = Tipo_usuario::all();

        return view('users.listado', compact('usuarios', 'tipos_usuario'));
    }

    public function guardarUsuario(Request $request){
        $isEdit = $request->filled('id');

        $emailRule = $isEdit
            ? 'required|email|unique:users,email,' . $request->id . ',pk_usuario'
            : 'required|email|unique:users,email';

        $reglas = [
            'username'   => 'required|string|max:255',
            'nombres'    => 'required|string|max:255',
            'ap_paterno' => 'required|string|max:255',
            'ap_materno' => 'nullable|string|max:255',
            'email'      => $emailRule,
            'img_user'   => 'nullable|image|max:2048',
        ];

        if (!$isEdit || $request->filled('password')) {
            $reglas['password'] = 'required|string|min:6';
        }

        $validator = Validator::make($request->all(), $reglas, [
            'username.required'   => 'El nombre de usuario es obligatorio.',
            'nombres.required'    => 'Los nombres son obligatorios.',
            'ap_paterno.required' => 'El apellido paterno es obligatorio.',
            'email.required'      => 'El correo es obligatorio.',
            'email.unique'        => 'El correo ya está en uso.',
            'email.email'         => 'El correo debe tener un formato válido.',
            'password.required'   => 'La contraseña es obligatoria.',
            'password.min'        => 'La contraseña debe tener mínimo 6 caracteres.',
            'img_user.image'      => 'El archivo debe ser una imagen.',
            'img_user.max'        => 'La imagen no debe superar los 2MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'mensaje' => $validator->errors(),
                'class'   => 'error'
            ], 422);
        }

        $usuario = $isEdit
            ? User::findOrFail($request->id)
            : new User();

        $usuario->username   = $request->username;
        $usuario->nombres    = $request->nombres;
        $usuario->ap_paterno = $request->ap_paterno;
        $usuario->ap_materno = $request->ap_materno;
        $usuario->email      = $request->email;

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        if ($request->hasFile('img_user')) {
            if ($isEdit && $usuario->img_user && Storage::disk('public')->exists($usuario->img_user)) {
                Storage::disk('public')->delete($usuario->img_user);
            }

            $file = $request->file('img_user');
            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('usuarios', $filename, 'public');
            $usuario->img_user = $path;
        }

        if (!$isEdit) {
            $usuario->remember_token = Str::random(10);
            $usuario->fk_tipo_user = 2;
        }

        $usuario->save();

        return response()->json([
            'mensaje' => $isEdit ? 'Usuario editado correctamente.' : 'Registro guardado correctamente.',
            'ruta'    => route('lista_usuarios'),
            'class'   => 'success'
        ]);
    }

    public function edit($id){
        $usuario = User::findOrFail($id);
        return view('users.formulario', compact('usuario'));
    }

    public function logout(Request $request){
        $username = Auth::user() ? Auth::user()->username : 'Usuario no autenticado';

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Cerraste sesión correctamente.');
    }

    public function perfil(){
        $usuario = auth()->user();
        $comentarios = Comentario::with(['autor','respuestas.autor'])
                ->where('fk_perfil_user', $usuario->pk_usuario)
                ->whereIn('estatus', ['visible','oculto'])   
                ->whereNull('fk_coment_respuesta')           
                ->orderByDesc('fecha_creacion')
                ->get();

            return view('users.perfil', compact('usuario','comentarios'));
        }

    public function listarCompañeros(){
        $usuarios = User::where('estatus', true)
                        ->where('fk_tipo_user', 2)
                        ->get();

        return view('users.compañeros', compact('usuarios'));
    }

    public function mostrarCompañero($id){
        $usuario = User::findOrFail($id);
        $comentarios = \App\Models\Comentario::with(['autor','respuestas.autor'])
            ->where('fk_perfil_user', $usuario->pk_usuario)
            ->whereIn('estatus', ['visible','oculto'])
            ->whereNull('fk_coment_respuesta')
            ->orderByDesc('fecha_creacion')
            ->get();

        return view('users.perfil', compact('usuario', 'comentarios'));

    }

}
