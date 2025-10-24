<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use App\Models\Categoria;
use App\Models\Pregunta;
use Illuminate\Http\Request;

class EncuestaController extends Controller
{
    /**
     * Mostrar lista de encuestas
     */
    public function index()
    {
        $encuestas = Encuesta::with('categoria')
            ->withCount('preguntas')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalRespuestas = 0; // Aquí puedes calcular el total si tienes tabla de respuestas
        $totalCategorias = Categoria::count();

        return view('admin.encuestas.index', compact('encuestas', 'totalRespuestas', 'totalCategorias'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $categorias = Categoria::all();
        return view('admin.encuestas.create', compact('categorias'));
    }

    /**
     * Guardar nueva encuesta
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'preguntas' => 'required|array|min:1',
            'preguntas.*.texto' => 'required|string',
            'preguntas.*.tipo' => 'required|in:multiple,text,rating',
        ], [
            'titulo.required' => 'El título es obligatorio',
            'categoria_id.required' => 'Debes seleccionar una categoría',
            'categoria_id.exists' => 'La categoría seleccionada no existe',
            'preguntas.required' => 'Debes agregar al menos una pregunta',
            'preguntas.*.texto.required' => 'El texto de la pregunta es obligatorio',
        ]);

        // Crear la encuesta
        $encuesta = Encuesta::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'categoria_id' => $request->categoria_id,
            'estado' => $request->has('estado') ? 1 : 0,
        ]);

        // Crear las preguntas
        foreach ($request->preguntas as $index => $preguntaData) {
            Pregunta::create([
                'encuesta_id' => $encuesta->id,
                'texto' => $preguntaData['texto'],
                'tipo' => $preguntaData['tipo'],
                'orden' => $index,
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
            $ultimaRespuesta = $encuesta->preguntas->first()->respuestas->first()->created_at->format('d/m/Y');
        }

        return view('admin.encuestas.show', compact('encuesta', 'totalRespuestas', 'promedioRespuestas', 'ultimaRespuesta'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $encuesta = Encuesta::with('preguntas')->findOrFail($id);
        $categorias = Categoria::all();
        return view('admin.encuestas.create', compact('encuesta', 'categorias'));
    }

    /**
     * Actualizar encuesta
     */
    public function update(Request $request, $id)
    {
        $encuesta = Encuesta::findOrFail($id);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'preguntas' => 'required|array|min:1',
            'preguntas.*.texto' => 'required|string',
            'preguntas.*.tipo' => 'required|in:multiple,text,rating',
        ]);

        // Actualizar encuesta
        $encuesta->update([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'categoria_id' => $request->categoria_id,
            'estado' => $request->has('estado') ? 1 : 0,
        ]);

        // Eliminar preguntas antiguas y crear nuevas
        $encuesta->preguntas()->delete();

        foreach ($request->preguntas as $index => $preguntaData) {
            Pregunta::create([
                'encuesta_id' => $encuesta->id,
                'texto' => $preguntaData['texto'],
                'tipo' => $preguntaData['tipo'],
                'orden' => $index,
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

        $mensaje = $encuesta->estado ? 'Encuesta activada' : 'Encuesta desactivada';

        return redirect()->route('admin.encuestas.index')
            ->with('success', $mensaje);
    }
}