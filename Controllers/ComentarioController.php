<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ComentarioController extends Controller
{
    use AuthorizesRequests;
    
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
            'fk_autor' => auth()->user()->pk_usuario,
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
            'fk_autor' => auth()->user()->pk_usuario,
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
     */
    public function hide(Comentario $comentario)
    {
        Gate::authorize('moderate-comments');
        $comentario->update(['estatus' => 'oculto']);
        return back()->with('success', 'Comentario ocultado correctamente.');
    }

    /**
     * Mostrar un comentario oculto (solo admin)
     * Renombrado para evitar conflicto con el método show() de Laravel
     */
    public function showComment(Comentario $comentario)
    {
        Gate::authorize('moderate-comments');
        $comentario->update(['estatus' => 'visible']);
        return back()->with('success', 'Comentario visible nuevamente.');
    }

    /**
     * Listar todos los comentarios para el panel de administración
     */
    public function listForAdmin()
    {
        // Cargar comentarios con sus relaciones
        $comentarios = Comentario::with(['autor', 'perfilUsuario'])
            ->withTrashed() // Incluir comentarios eliminados
            ->latest()
            ->paginate(15);

        return view('comentarios.index', compact('comentarios'));
    }

    /**
     * Actualizar/editar un comentario (solo propietario)
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