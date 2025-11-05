<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use App\Models\Categoria;
use App\Models\Pregunta;
use App\Models\User; // <-- Lo mantenemos por si acaso, pero no es crítico aquí
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EncuestaController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('auth'); 
        $this->middleware('admin')->except(['showPublicIndex']);
    }

    /**
     * Vista pública de encuestas
     */
    public function showPublicIndex()
    {
        $encuestas = Encuesta::where('estado', 1)
                            ->with('preguntas') 
                            ->latest()
                            ->paginate(10);
                            
        return view('encuestas', [
            'encuestas' => $encuestas,
            'usuario' => Auth::user()
        ]);
    }


    // ==========================================================
    // FUNCIONES DE ADMINISTRADOR
    // ==========================================================

    public function index()
    {
        $encuestas = Encuesta::with('categoria')
            ->withCount('preguntas')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalRespuestas = 0; // Esto necesitará una lógica más avanzada
        $totalCategorias = Categoria::count();

        return view('admin.encuestas.index', compact('encuestas', 'totalRespuestas', 'totalCategorias'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $categorias = Categoria::all();
        // <-- CAMBIO: Ya no necesitamos pasar la lista de '$users'
        return view('admin.encuestas.create', compact('categorias'));
    }

    /**
     * Guardar nueva encuesta
     */
    public function store(Request $request)
    {
        // <-- CAMBIO: Validación SÚPER SIMPLIFICADA
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            
            'preguntas' => 'required|array|min:1',
            'preguntas.*.texto' => 'required|string',
            'preguntas.*.tipo' => 'required|in:nominados', 
            // <-- CAMBIO: La validación de 'opciones' ha sido ELIMINADA
            
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ], [
            'titulo.required' => 'El título es obligatorio',
            'categoria_id.required' => 'Debes seleccionar una categoría',
            'preguntas.required' => 'Debes agregar al menos una pregunta',
            'preguntas.*.texto.required' => 'El texto de la pregunta es obligatorio',
        ]);

        $encuesta = Encuesta::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'categoria_id' => $request->categoria_id,
            'estado' => 1, 
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
        ]);

        // Crear las preguntas
        foreach ($request->preguntas as $index => $preguntaData) {
            Pregunta::create([
                'encuesta_id' => $encuesta->id,
                'texto' => $preguntaData['texto'],
                'tipo' => $preguntaData['tipo'], 
                'orden' => $index,
                // <-- CAMBIO: Guardamos un JSON vacío o nulo.
                // La lógica de votación cargará a todos los usuarios.
                'opciones' => json_encode([]), 
            ]);
        }

        return redirect()->route('admin.encuestas.index')
            ->with('success', 'Encuesta creada exitosamente');
    }

    /**
     * Mostrar resultados de una encuesta
     */
    public function show($id)
    {
        $encuesta = Encuesta::with(['categoria', 'preguntas.respuestas'])->findOrFail($id);
        
        $totalRespuestas = 0;
        foreach ($encuesta->preguntas as $pregunta) {
            $totalRespuestas += $pregunta->respuestas->count();
        }
        
        $promedioRespuestas = $encuesta->preguntas->count() > 0 
            ? $totalRespuestas / $encuesta->preguntas->count() 
            : 0;
        
        $ultimaRespuesta = null;
        if ($totalRespuestas > 0) {
            $primeraPreguntaConRespuesta = $encuesta->preguntas->first(function($p) { return $p->respuestas->isNotEmpty(); });
            if ($primeraPreguntaConRespuesta) {
                $ultimaRespuesta = $primeraPreguntaConRespuesta->respuestas->sortByDesc('created_at')->first()->created_at->format('d/m/Y');
            }
        }

        return view('admin.encuestas.show', compact('encuesta', 'totalRespuestas', 'promedioRespuestas', 'ultimaRespuesta'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $encuesta = Encuesta::with('preguntas')->findOrFail($id);
    
        // <-- CAMBIO: Ya no necesitamos decodificar 'opciones_array'
        // ni tampoco necesitamos pasar '$users'
        
        $categorias = Categoria::all();
        
        return view('admin.encuestas.create', compact('encuesta', 'categorias'));
    }

    /**
     * Actualizar encuesta
     */
    public function update(Request $request, $id)
    {
        $encuesta = Encuesta::findOrFail($id);

        // <-- CAMBIO: Validación SÚPER SIMPLIFICADA
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            
            'preguntas' => 'required|array|min:1',
            'preguntas.*.texto' => 'required|string',
            'preguntas.*.tipo' => 'required|in:nominados', 
            // <-- CAMBIO: La validación de 'opciones' ha sido ELIMINADA
            
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        // Actualizar encuesta
        $encuesta->update([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'categoria_id' => $request->categoria_id,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
        ]);

        // Eliminar preguntas antiguas y crear nuevas
        $encuesta->preguntas()->delete();

        foreach ($request->preguntas as $index => $preguntaData) {
            Pregunta::create([
                'encuesta_id' => $encuesta->id, 
                'texto' => $preguntaData['texto'],
                'tipo' => $preguntaData['tipo'],
                'orden' => $index,
                'opciones' => json_encode([]), // <-- CAMBIO
            ]);
        }

        return redirect()->route('admin.encuestas.index')
            ->with('success', 'Encuesta actualizada exitosamente');
    }

    /**
     * Eliminar encuesta
     */
    public function destroy($id)
    {
        $encuesta = Encuesta::findOrFail($id);
        $encuesta->delete();

        return redirect()->route('admin.encuestas.index')
            ->with('success', 'Encuesta eliminada exitosamente');
    }

    /**
     * Cambiar estado de encuesta (activa/inactiva)
     */
    public function cambiarEstado($id)
    {
        $encuesta = Encuesta::findOrFail($id);
        $encuesta->estado = !$encuesta->estado;
        $encuesta->save();

        $mensaje = $encuesta->estado ? 'Encuesta activada manualmente' : 'Encuesta desactivada manualmente';

        return redirect()->route('admin.encuestas.index')
            ->with('success', $mensaje);
    }
}