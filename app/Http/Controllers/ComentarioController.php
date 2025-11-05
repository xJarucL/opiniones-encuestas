<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

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
     */
    public function index(Request $request) // Añadido Request para filtros
    {
        // 1. Obtener conteos (Optimización)
        $totalCount = Comentario::withTrashed()->count();
        $ocultoCount = Comentario::withTrashed()->where('estatus', 'oculto')->count();
        $visibleCount = Comentario::withTrashed()->where('estatus', '!=', 'oculto')->count();

        // 2. Cargar comentarios con sus relaciones (Consulta principal)
        $query = Comentario::with(['autor', 'perfil'])
            ->withTrashed() // Incluir comentarios eliminados
            ->latest();

        // 3. Aplicar filtros si existen en la URL
        if ($request->has('filtro')) {
            if ($request->filtro == 'visible') {
                $query->where('estatus', '!=', 'oculto');
            } elseif ($request->filtro == 'oculto') {
                $query->where('estatus', 'oculto');
            }
        }

        $comentarios = $query->paginate(15);

        // 4. Pasar todo a la vista
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
     * Eliminar un comentario (solo propietario)
     * Esta función es llamada por la ruta 'comentarios.destroy' (para usuarios normales)
     */
    public function destroy(Comentario $comentario) // Corregido para usar Route-Model Binding
    {
        $this->authorize('delete', $comentario); // Usar Policy para verificar propietario
        
        try {
            $comentario->update(['estatus' => 'eliminado']); // Opcional: marcar antes de borrar
            $comentario->delete(); // Soft delete o hard delete (depende de tu modelo)
            
            return back()->with('success', 'Comentario eliminado.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el comentario: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar un comentario (solo admin)
     * Esta función es llamada por la ruta 'admin.comentarios.destroy'
     */
    public function destroyAdmin(Comentario $comentario)
    {
        Gate::authorize('moderate-comments'); // Verificar que es admin
        
        try {
            $comentario->delete(); // Soft delete o hard delete
            return redirect()->route('admin.comentarios.index')
                ->with('success', 'Comentario eliminado correctamente');
        } catch (\Exception $e) {
            return redirect()->route('admin.comentarios.index')
                ->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
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
     */
    public function showComment(Comentario $comentario)
    {
        Gate::authorize('moderate-comments');
        $comentario->update(['estatus' => 'visible']);
        return back()->with('success', 'Comentario visible nuevamente.');
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

