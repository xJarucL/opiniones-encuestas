<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use App\Models\Pregunta;
use App\Models\OpcionRespuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EncuestaController extends Controller
{
    // Listar todas las encuestas (Admin)
    public function index()
    {
        $encuestas = Encuesta::with('creador', 'preguntas')
            ->withCount('preguntas')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.encuestas.index', compact('encuestas'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        return view('admin.encuestas.create');
    }

    // Guardar nueva encuesta
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'preguntas' => 'required|array|min:1',
            'preguntas.*.pregunta' => 'required|string',
            'preguntas.*.tipo' => 'required|in:opcion_multiple,texto_libre,escala,si_no',
            'preguntas.*.es_obligatoria' => 'boolean',
            'preguntas.*.opciones' => 'required_if:preguntas.*.tipo,opcion_multiple,escala,si_no|array',
        ]);

        DB::beginTransaction();
        try {
            // Crear encuesta
            $encuesta = Encuesta::create([
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'estatus' => 'activa',
                'fk_user_creador' => Auth::id()
            ]);

            // Crear preguntas
            foreach ($request->preguntas as $index => $preguntaData) {
                $pregunta = Pregunta::create([
                    'fk_encuesta' => $encuesta->pk_encuesta,
                    'pregunta' => $preguntaData['pregunta'],
                    'tipo' => $preguntaData['tipo'],
                    'es_obligatoria' => $preguntaData['es_obligatoria'] ?? false,
                    'orden' => $index + 1
                ]);

                // Crear opciones si es necesario
                if (in_array($preguntaData['tipo'], ['opcion_multiple', 'escala', 'si_no']) && isset($preguntaData['opciones'])) {
                    foreach ($preguntaData['opciones'] as $opcionIndex => $opcionTexto) {
                        if (!empty($opcionTexto)) {
                            OpcionRespuesta::create([
                                'fk_pregunta' => $pregunta->pk_pregunta,
                                'texto_opcion' => $opcionTexto,
                                'orden' => $opcionIndex + 1
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.encuestas.index')
                ->with('success', 'Encuesta creada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear la encuesta: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Mostrar detalle de encuesta
    public function show($id)
    {
        $encuesta = Encuesta::with(['preguntas.opciones.respuestas', 'creador'])
            ->findOrFail($id);

        // Calcular estadísticas
        $totalRespuestas = $encuesta->totalRespuestas();
        
        return view('admin.encuestas.show', compact('encuesta', 'totalRespuestas'));
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $encuesta = Encuesta::with('preguntas.opciones')->findOrFail($id);
        return view('admin.encuestas.edit', compact('encuesta'));
    }

    // Actualizar encuesta
    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'estatus' => 'required|in:activa,inactiva,finalizada',
        ]);

        $encuesta = Encuesta::findOrFail($id);
        
        $encuesta->update([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'estatus' => $request->estatus,
        ]);

        return redirect()->route('admin.encuestas.index')
            ->with('success', 'Encuesta actualizada exitosamente');
    }

    // Eliminar encuesta
    public function destroy($id)
    {
        $encuesta = Encuesta::findOrFail($id);
        $encuesta->delete();

        return redirect()->route('admin.encuestas.index')
            ->with('success', 'Encuesta eliminada exitosamente');
    }

    // Cambiar estado de encuesta
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estatus' => 'required|in:activa,inactiva,finalizada'
        ]);

        $encuesta = Encuesta::findOrFail($id);
        $encuesta->update(['estatus' => $request->estatus]);

        return back()->with('success', 'Estado actualizado exitosamente');
    }
}