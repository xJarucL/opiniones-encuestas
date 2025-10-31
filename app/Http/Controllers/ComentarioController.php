<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth; // <-- AÑADIDO PARA CORREGIR LOS ERRORES DEL EDITOR

class ComentarioController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Constructor - Aplicar middleware de autenticación
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Listar todos los comentarios para el panel de administración
     * (Tu código ya estaba bien aquí)
     */
    public function index()
    {
        // 1. Obtener conteos (Optimización)
        // Usamos withTrashed() para que coincida con la consulta principal
        $totalCount = Comentario::withTrashed()->count();
        $ocultoCount = Comentario::withTrashed()->where('estatus', 'oculto')->count();
        $visibleCount = Comentario::withTrashed()->where('estatus', '!=', 'oculto')->count();

        // 2. Cargar comentarios con sus relaciones (Tu consulta original)
        $comentarios = Comentario::with(['autor', 'perfil'])
            ->withTrashed() // Incluir comentarios eliminados
            ->latest()
            ->paginate(15);

        // 3. Pasar todo a la vista
        return view('comentarios.index', compact(
            'comentarios',
            'totalCount',
            'visibleCount',
            'ocultoCount'
        ));
    }


    /**
     * Crear un nuevo comentario en el perfil de un usuario
     */
    public function store(Request $r, $id)
    {
        $r->validate([
            'contenido' => 'required|string|max:1000',
            'anonimo' => 'nullable|boolean',
        ]);

        $perfil = User::findOrFail($id);

        Comentario::create([
            // === CORREGIDO AQUÍ ===
            'fk_autor' => Auth::user()->pk_usuario,
            'fk_perfil_user' => $perfil->pk_usuario,
            'contenido' => $r->contenido,
            'anonimo' => (bool)$r->anonimo,
            'estatus' => 'visible',
        ]);

        return back()->with('success', 'Comentario publicado correctamente.');
    }

    /**
     * Responder a un comentario existente
     */
    public function reply(Request $r, Comentario $comentario)
    {
        $r->validate(['contenido' => 'required|string|max:1000']);

        Comentario::create([
            // === CORREGIDO AQUÍ ===
            'fk_autor' => Auth::user()->pk_usuario,
            'fk_perfil_user' => $comentario->fk_perfil_user,
            'fk_coment_respuesta' => $comentario->pk_comentario,
            'contenido' => $r->contenido,
            'anonimo' => false,
            'estatus' => 'visible',
        ]);

        return back()->with('success', 'Respuesta publicada correctamente.');
    }

    /**
     * Eliminar un comentario (solo propietario o admin)
     * (Tu código original - sin cambios)
     */
    public function destroy(Comentario $comentario)
    {
        $this->authorize('delete', $comentario);
        $comentario->update(['estatus' => 'eliminado']);
        $comentario->delete();
        return back()->with('success', 'Comentario eliminado.');
    }

    /**
     * Ocultar un comentario (solo admin)
     * (Tu código original - sin cambios)
     */
    public function hide(Comentario $comentario)
    {
        Gate::authorize('moderate-comments');
        $comentario->update(['estatus' => 'oculto']);
        return back()->with('success', 'Comentario ocultado correctamente.');
    }

    /**
     * Mostrar un comentario oculto (solo admin)
     * (Tu código original - sin cambios)
     */
    public function showComment(Comentario $comentario)
    {
        Gate::authorize('moderate-comments');
        $comentario->update(['estatus' => 'visible']);
        return back()->with('success', 'Comentario visible nuevamente.');
    }

    /**
     * Actualizar/editar un comentario (solo propietario)
     * (Tu código original - sin cambios)
     */
    public function update(Request $r, Comentario $comentario)
    {
        $this->authorize('update', $comentario);
        
        $r->validate([
            'contenido' => 'required|string|max:1000'
        ]);

        $comentario->update([
            'contenido' => $r->contenido
        ]);

        return back()->with('success', 'Comentario actualizado correctamente.');
    }
}
    