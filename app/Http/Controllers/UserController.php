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

            // Redirigir según tipo de usuario
            $ruta = match($usuario->fk_tipo_user) {
                1 => route('admin.dashboard'),  // Administrador
                2 => route('inicio'),           // Usuario normal
                default => route('login'),
            };

            return response()->json([
                'mensaje' => '¡Inicio de sesión exitoso!',
                'ruta' => $ruta,
                'class' => 'success'
            ]);

        } else {
            return response()->json([
                'mensaje' => 'Credenciales incorrectas.',
                'class' => 'error'
            ], 422);
        }
    }

    public function dashboard(){
        // === CORREGIDO AQUÍ ===
        $usuario = Auth::user(); 
        return view('users.dashboard', compact('usuario'));
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

        return redirect()->route('usuarios.lista')->with('success', 'Usuario eliminado correctamente.');      
    }

    public function restaurar($id){
        $usuario = User::withTrashed()->findOrFail($id);
        $usuario->restore();

        return redirect()->route('usuarios.inactivos')->with('success', 'Usuario restaurado correctamente.');
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

    $request->validate($reglas);

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

    return redirect()->route('usuarios.lista')->with('success', $isEdit ? 'Usuario actualizado correctamente.' : 'Usuario registrado correctamente.');
}
    

        public function edit($id){
            $usuario = User::findOrFail($id);
            $tipos_usuario = Tipo_usuario::all();
            return view('users.formulario', compact('usuario', 'tipos_usuario'));
}

    public function logout(Request $request){
        $username = Auth::user() ? Auth::user()->username : 'Usuario no autenticado';

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Cerraste sesión correctamente.');
    }

    /**
     * MUESTRA EL PERFIL DEL USUARIO AUTENTICADO
     * ======================================================
     * CAMBIO: Se usa el método privado _getPerfilData
     */
    public function perfil(){
        // === CORREGIDO AQUÍ ===
        $usuario = Auth::user();
        $data = $this->_getPerfilData($usuario);
        
        return view('users.perfil', $data);    
    }

    public function listarCompañeros(){
        $usuarios = User::where('estatus', true)
                            ->where('fk_tipo_user', 2)
                            ->get();

        return view('users.compañeros', compact('usuarios'));
    }



    /**
     * MUESTRA EL PERFIL DE OTRO USUARIO (COMPAÑERO)
     * ======================================================
     * CAMBIO: Se usa el método privado _getPerfilData
     */
    public function mostrarCompañero($id){
        $usuario = User::findOrFail($id);
        $data = $this->_getPerfilData($usuario);

        return view('users.perfil', $data);
    }
    

    /**
     * ======================================================
     * NUEVO MÉTODO PRIVADO
     * ======================================================
     * Obtiene los datos de perfil y comentarios para un usuario específico.
     * Esto evita duplicar la consulta en perfil() y mostrarCompañero().
     */
    private function _getPerfilData(User $usuario)
    {
        $comentarios = Comentario::with(['autor','respuestas.autor'])
            ->where('fk_perfil_user', $usuario->pk_usuario)
            ->whereIn('estatus', ['visible','oculto'])
            ->whereNull('fk_coment_respuesta')
            ->orderByDesc('fecha_creacion')
            ->get();
            
        return [
            'usuario' => $usuario,
            'comentarios' => $comentarios
        ];
    }
}