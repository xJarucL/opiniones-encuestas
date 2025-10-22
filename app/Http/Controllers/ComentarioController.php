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

    public function destroy(Comentario $comentario)
    {
        $this->authorize('delete', $comentario);
        $comentario->update(['estatus' => 'eliminado']);
        $comentario->delete();
        return back()->with('success', 'Comentario eliminado.');
    }

    public function hide(Comentario $comentario)
    {
        Gate::authorize('moderate-comments');
        $comentario->update(['estatus' => 'oculto']);
        return back()->with('success', 'Comentario oculto.');
    }

    public function show(Comentario $comentario)
    {
        Gate::authorize('moderate-comments');
        $comentario->update(['estatus' => 'visible']);
        return back()->with('success', 'Comentario visible.');
    }
}
