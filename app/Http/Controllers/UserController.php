<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tipo_usuario; // Asegúrate que el nombre del modelo es este
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
    /**
     * Maneja el inicio de sesión.
     */
    public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email', // Mejor usar 'email'
            'password' => 'required|string',
        ]);

        // Intentar autenticar
        $credentials = $request->only('email', 'password');

        // ¡IMPORTANTE! Verificar si el usuario está activo (no "soft deleted")
        $user = User::where('email', $request->email)->first();

        if ($user && $user->trashed()) {
             return response()->json([
                'mensaje' => 'Tu cuenta está desactivada.',
                'class' => 'error'
            ], 403); // Forbidden
        }


        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Regenerar sesión por seguridad
            $usuario = Auth::user(); // Obtener el usuario autenticado

            // Redirigir según tipo de usuario
            $ruta = match($usuario->fk_tipo_user) {
                1 => route('admin.dashboard'),  // Administrador
                2 => route('inicio'),           // Usuario normal
                default => route('login'),      // Ruta por defecto si no coincide
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
            ], 422); // Unprocessable Entity (error de validación)
        }
    }

    /**
     * Muestra el dashboard (puede variar según el rol, aquí genérico).
     */
    public function dashboard(){
        $usuario = Auth::user(); 
        // Determinar qué vista mostrar basado en el rol si es necesario
        // Ejemplo: if ($usuario->fk_tipo_user == 1) return view('admin.dashboard', compact('usuario'));
        return view('users.dashboard', compact('usuario')); // Vista para usuario normal
    }

    /**
     * Muestra la lista de usuarios ACTIVOS.
     */
    public function listaUsuarios(){
        $usuarios = User::with('tipo_usuario') // Carga la relación
                          ->orderBy('created_at', 'desc') // Ordenar por creación descendente
                          ->paginate(10); // Paginar usuarios activos (SoftDeletes lo hace por defecto)
                          
        $tipos_usuario = Tipo_usuario::all();

        // Cálculos de estadísticas para la vista de activos
        // Clonamos para no afectar la consulta principal de activos
        $queryActivos = User::query(); 

        $totalAdmins = (clone $queryActivos)->whereHas('tipo_usuario', function($q) {
                            $q->where('nombre', 'like', '%admin%');
                         })->count();

        $totalRegulares = (clone $queryActivos)->whereHas('tipo_usuario', function($q) {
                            $q->where('nombre', 'not like', '%admin%');
                         })->count();
        
        $mostrarInactivos = false; // Variable para la vista

        return view('users.listado', compact(
            'usuarios', 
            'tipos_usuario', 
            'totalAdmins', 
            'totalRegulares', 
            'mostrarInactivos' // Pasar la variable
        ));
    }

    /**
     * Muestra la lista de usuarios INACTIVOS (Soft Deleted).
     */
    public function listaUsuarios_inactivos(){
        $usuarios = User::onlyTrashed() // Obtiene SOLO los eliminados suavemente
                          ->with('tipo_usuario')
                          ->orderBy('deleted_at', 'desc') // Ordenar por fecha de eliminación
                          ->paginate(10);
                          
        $tipos_usuario = Tipo_usuario::all();

        // Estadísticas para inactivos (puedes decidir si las necesitas)
        $queryInactivos = User::onlyTrashed();

        $totalAdmins = (clone $queryInactivos)->whereHas('tipo_usuario', function($q) {
                            $q->where('nombre', 'like', '%admin%');
                         })->count();
        
        $totalRegulares = (clone $queryInactivos)->whereHas('tipo_usuario', function($q) {
                            $q->where('nombre', 'not like', '%admin%');
                         })->count();
        
        $mostrarInactivos = true; // Variable para la vista

        return view('users.listado', compact(
            'usuarios', 
            'tipos_usuario', 
            'totalAdmins', 
            'totalRegulares', 
            'mostrarInactivos' // Pasar la variable
        ));
    }

    /**
     * Cambia el tipo de un usuario (activo o inactivo).
     */
    public function cambiarTipo(Request $request, $id){
        $request->validate([
             // Asegúrate que la tabla se llama 'tipo_usuarios' o ajústalo
            'fk_tipo_user' => 'required|exists:tipo_usuarios,pk_tipo_user',
        ]);

        // Usamos withTrashed para poder cambiar el tipo incluso a usuarios inactivos
        $usuario = User::withTrashed()->findOrFail($id); 
        $usuario->fk_tipo_user = $request->fk_tipo_user;
        $usuario->save();

        return back()->with('success', 'Tipo de usuario actualizado correctamente.');
    }

    /**
     * Desactiva un usuario (Soft Delete).
     */
    public function eliminar($id){
        $usuario = User::findOrFail($id); // Encuentra solo activos
        $usuario->delete(); // Realiza el Soft Delete

        // Redirigir a la lista de inactivos para ver el resultado
        return redirect()->route('usuarios.inactivos')
               ->with('success', 'Usuario movido a inactivos.');      
    }

    /**
     * Reactiva un usuario (Restaura Soft Delete).
     */
    public function restaurar($id){
        // Usamos withTrashed para encontrar al usuario inactivo
        $usuario = User::withTrashed()->findOrFail($id); 
        $usuario->restore(); // Restaura el usuario

        // Redirigir a la lista de activos para ver el resultado
        return redirect()->route('usuarios.lista') 
               ->with('success', 'Usuario activado correctamente.');
    }
    
    /**
     * ¡NUEVO! Elimina permanentemente un usuario inactivo.
     * Requiere una ruta definida para 'usuarios.eliminar-permanente'.
     */
    public function eliminarPermanente($id)
    {
        // Solo permite eliminar permanentemente a usuarios YA inactivos
        $usuario = User::onlyTrashed()->findOrFail($id); 
        
        // Aquí podrías añadir lógica para eliminar archivos asociados si es necesario
        // if ($usuario->img_user && Storage::disk('public')->exists($usuario->img_user)) {
        //     Storage::disk('public')->delete($usuario->img_user);
        // }
        
        $usuario->forceDelete(); // Eliminación física de la base de datos

        return redirect()->route('usuarios.inactivos')
               ->with('success', 'Usuario eliminado permanentemente del sistema.');
    }

    /**
     * Guarda un usuario nuevo o actualiza uno existente.
     */
    public function guardarUsuario(Request $request){
        $isEdit = $request->filled('id'); // Verifica si se está editando (viene un ID)

        // Regla de validación única para email, diferente si es edición o creación
        $emailRule = $isEdit 
            ? 'required|email|unique:users,email,' . $request->id . ',pk_usuario' // Ignora el propio ID al editar
            : 'required|email|unique:users,email'; // Exige unicidad al crear

        $reglas = [
            'username'   => 'required|string|max:255',
            'nombres'    => 'required|string|max:255',
            'ap_paterno' => 'required|string|max:255',
            'ap_materno' => 'nullable|string|max:255',
            'email'      => $emailRule,
            'img_user'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Validar mimes
        ];

        // Añadir regla de contraseña solo si se está creando O si se proporcionó al editar
        if (!$isEdit || $request->filled('password')) {
            $reglas['password'] = 'required|string|min:6'; // Mínimo 6 caracteres
        }

        $request->validate($reglas);

        // Busca el usuario si es edición, o crea uno nuevo si no
        $usuario = $isEdit ? User::findOrFail($request->id) : new User();

        // Asignar datos del request
        $usuario->username   = $request->username;
        $usuario->nombres    = $request->nombres;
        $usuario->ap_paterno = $request->ap_paterno;
        $usuario->ap_materno = $request->ap_materno;
        $usuario->email      = $request->email;

        // Hashear contraseña si se proporcionó
        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        // Manejar subida de imagen
        if ($request->hasFile('img_user')) {
            // Eliminar imagen anterior si existe (solo en edición)
            if ($isEdit && $usuario->img_user && Storage::disk('public')->exists($usuario->img_user)) {
                Storage::disk('public')->delete($usuario->img_user);
            }

            // Guardar nueva imagen
            $file = $request->file('img_user');
            $filename = uniqid('user_') . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('usuarios', $filename, 'public'); // Guarda en storage/app/public/usuarios
            $usuario->img_user = $path;
        }

        // Asignar valores por defecto al crear
        if (!$isEdit) {
            $usuario->remember_token = Str::random(10);
            $usuario->fk_tipo_user = 2; // Asignar tipo 'Usuario normal' por defecto
        }

        $usuario->save(); // Guardar en la base de datos

        // Redirigir a la lista de usuarios activos con mensaje de éxito
        return redirect()->route('usuarios.lista')
               ->with('success', $isEdit ? 'Usuario actualizado correctamente.' : 'Usuario registrado correctamente.');
    }
    
    /**
     * Muestra el formulario para editar un usuario.
     */
    public function edit($id){
        // Usamos findOrFail para manejar el caso de que el ID no exista
        $usuario = User::findOrFail($id); 
        $tipos_usuario = Tipo_usuario::all();
        // Pasamos 'usuario' y 'tipos_usuario' a la vista del formulario
        return view('users.formulario', compact('usuario', 'tipos_usuario'));
    }

    /**
     * Maneja el cierre de sesión.
     */
    public function logout(Request $request){
        Auth::logout(); // Cierra la sesión del usuario

        $request->session()->invalidate(); // Invalida la sesión actual
        $request->session()->regenerateToken(); // Regenera el token CSRF

        // Redirige a la página de login con mensaje de éxito
        return redirect()->route('login')->with('success', 'Cerraste sesión correctamente.');
    }

    // ======================================================
    // MÉTODOS RELACIONADOS CON EL PERFIL Y COMPAÑEROS
    // ======================================================

    /**
     * Muestra el perfil del usuario autenticado.
     */
    public function perfil(){
        $usuario = Auth::user(); // Obtiene el usuario logueado
        // Llama al método privado para obtener datos y comentarios
        $data = $this->_getPerfilData($usuario); 
        
        return view('users.perfil', $data);    
    }

    /**
     * Muestra una lista de otros usuarios (compañeros).
     * Asume que 'estatus' = true significa activo (no soft-deleted).
     * Y 'fk_tipo_user' = 2 es usuario normal.
     */
    public function listarCompañeros(){
         // Obtiene usuarios activos (no soft-deleted) que sean tipo 2
        $usuarios = User::where('fk_tipo_user', 2)->get();

        return view('users.compañeros', compact('usuarios'));
    }

    /**
     * Muestra el perfil público de otro usuario (compañero).
     */
    public function mostrarCompañero($id){
        // Busca al usuario por ID o falla si no existe
        $usuario = User::findOrFail($id); 
        // Llama al método privado para obtener datos y comentarios
        $data = $this->_getPerfilData($usuario);

        return view('users.perfil', $data);
    }
    
    /**
     * Método privado para obtener datos del perfil y comentarios de un usuario.
     * Evita duplicar código en perfil() y mostrarCompañero().
     */
    private function _getPerfilData(User $usuario)
    {
        // Carga los comentarios del perfil, incluyendo autor y respuestas con su autor
        $comentarios = Comentario::with(['autor','respuestas.autor'])
            ->where('fk_perfil_user', $usuario->pk_usuario) // Comentarios de este perfil
            ->whereIn('estatus', ['visible','oculto']) // Solo visibles u ocultos (admin)
            ->whereNull('fk_coment_respuesta') // Solo comentarios principales (no respuestas)
            ->orderByDesc('fecha_creacion') // Más recientes primero
            ->get();
            
        // Devuelve un array con el usuario y sus comentarios
        return [
            'usuario' => $usuario,
            'comentarios' => $comentarios
        ];
    }
}