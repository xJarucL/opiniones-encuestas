<?php

namespace App\Http\Controllers;

// Imports de tu controlador
use App\Models\Encuesta;
use App\Models\Categoria;
use App\Models\Pregunta;
use Illuminate\Http\Request;
use Carbon\Carbon;

// Import añadido para 'Auth'
use Illuminate\Support\Facades\Auth;

class EncuestaController extends Controller
{
    /**
    * ==========================================================
    * AÑADIDO: Constructor para proteger las rutas
    * ==========================================================
    */
    public function __construct()
    {
        // Protege TODAS las rutas de este controlador
        $this->middleware('auth'); 
        
        // Aplica el middleware 'admin' a todas las rutas EXCEPTO a la nueva
        $this->middleware('admin')->except(['showPublicIndex']);
    }

    /**
    * ==========================================================
    * AÑADIDO: NUEVA FUNCIÓN PARA USUARIOS
    * ==========================================================
    */
    public function showPublicIndex()
    {
        // Asumo que 'estado' == 1 es como marcas las encuestas visibles
        $encuestas = Encuesta::where('estado', 1) // <-- CORREGIDO para usar 'estado'
                            ->with('preguntas') // Carga las preguntas para saber si está vacía
                            ->latest()
                            ->paginate(10);
                            
        // ==========================================================
        // ¡LA CORRECCIÓN ESTÁ AQUÍ!
        // Apuntamos a 'encuestas' (tu archivo) en lugar de 'encuestas.public-index'
        // ==========================================================
        return view('encuestas', [
            'encuestas' => $encuestas,
            'usuario' => Auth::user() // Pasa el usuario al layout 'components.menu'
        ]);
    }

    // ==========================================================
    // FUNCIONES DE ADMINISTRADOR (Tu código original)
    // ==========================================================

    /**
    * Mostrar lista de encuestas
    */
    public function index()
    {
        $encuestas = Encuesta::with('categoria')
            ->withCount('preguntas')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalRespuestas = 0; 
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
            'preguntas.*.tipo' => 'required|in:multiple', // Solo permitimos 'multiple' ahora
            'preguntas.*.opciones' => 'required|array|min:2', // Debe haber al menos 2 opciones
            'preguntas.*.opciones.*' => 'required|string|max:255', // Cada opción debe ser string
            
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ], [
            'titulo.required' => 'El título es obligatorio',
            'categoria_id.required' => 'Debes seleccionar una categoría',
            'preguntas.required' => 'Debes agregar al menos una pregunta',
            'preguntas.*.texto.required' => 'El texto de la pregunta es obligatorio',
            'preguntas.*.opciones.min' => 'Cada pregunta debe tener al menos dos opciones de respuesta.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
        ]);

        // Crear la encuesta
        $encuesta = Encuesta::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'categoria_id' => $request->categoria_id,
            'estado' => 1, // Por defecto ACTIVA
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
        ]);

        // Crear las preguntas
        foreach ($request->preguntas as $index => $preguntaData) {
            Pregunta::create([
                'encuesta_id' => $encuesta->id,
                'texto' => $preguntaData['texto'],
                'tipo' => 'multiple', // Forzamos el tipo a 'multiple'
                'orden' => $index,
                // GUARDA LAS OPCIONES COMO JSON
                'opciones' => json_encode($preguntaData['opciones']), 
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
            // Pequeña corrección: Asegurarse de que existan respuestas antes de acceder
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
        // Se añade 'opciones' al modelo Pregunta si existe el campo JSON, 
        // y se decodifica para pasarlo a la vista si es necesario.
        $encuesta = Encuesta::with('preguntas')->findOrFail($id);
    
        // Decodificar el JSON de opciones para que la vista pueda iterar
        if ($encuesta->preguntas->isNotEmpty()) {
            foreach ($encuesta->preguntas as $pregunta) {
                // Asumimos que el campo se llama 'opciones' y contiene un JSON string
                $pregunta->opciones_array = json_decode($pregunta->opciones, true) ?? [];
            }
        }
        // <<<--- ¡AQUÍ ESTABA LA LLAVE "}" EXTRA! (Ha sido eliminada)
        
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
            'preguntas.*.tipo' => 'required|in:multiple', // Solo permitimos 'multiple' ahora
            'preguntas.*.opciones' => 'required|array|min:2', // Debe haber al menos 2 opciones
            'preguntas.*.opciones.*' => 'required|string|max:255',
            
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
                'tipo' => 'multiple', // Forzamos el tipo a 'multiple'
                'orden' => $index,
                // GUARDA LAS OPCIONES COMO JSON
                'opciones' => json_encode($preguntaData['opciones']),
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

