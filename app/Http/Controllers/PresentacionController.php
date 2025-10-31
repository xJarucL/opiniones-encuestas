<?php

namespace App\Http\Controllers;

use App\Models\Pregunta;
use App\Models\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 

class PresentacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('store');
    }

    /**
     * Muestra una pregunta para votar
     */
    public function index($preguntaId)
    {
        $pregunta = Pregunta::findOrFail($preguntaId);
        
        $opciones = json_decode($pregunta->opciones, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($opciones)) {
            $opciones = []; 
        }

        $userId = Auth::id();
        $haVotado = false;

        if ($userId) {
            $haVotado = Respuesta::where('pregunta_id', $preguntaId)
                                 ->where('user_id', $userId)
                                 ->exists(); 
        }

        $pregunta->load('encuesta');

        return view('presentacion.index', [
            'pregunta' => $pregunta,
            'opciones' => $opciones,
            'haVotado' => $haVotado, 
        ]);
    }

    /**
     * Guarda la respuesta de la encuesta
     */
    public function store(Request $request)
    {
        $request->validate([
            'pregunta_id' => 'required|exists:preguntas,id',
            'respuesta' => 'required|string', 
        ]);

        $pregunta = Pregunta::findOrFail($request->pregunta_id);
        
        $votoExistente = Respuesta::where('pregunta_id', $pregunta->id)
                                  ->where('user_id', Auth::id())
                                  ->exists();

        if ($votoExistente) {
            return redirect()->route('podio', ['preguntaId' => $pregunta->id]);
        }

        Respuesta::create([
            'pregunta_id' => $pregunta->id,
            'user_id' => Auth::id(), 
            'respuesta' => $request->respuesta,
        ]);

        $siguientePregunta = Pregunta::where('encuesta_id', $pregunta->encuesta_id)
                                     ->where('orden', '>', $pregunta->orden)
                                     ->orderBy('orden', 'asc')
                                     ->first();

        if ($siguientePregunta) {
            return redirect()->route('presentacion', ['preguntaId' => $siguientePregunta->id]);
        } else {
            // ==========================================================
            // ¡AQUÍ ESTÁ EL CAMBIO!
            // ==========================================================
            // Redirigir a la lista de encuestas en lugar del dashboard
            return redirect()->route('encuestas.index') 
                   ->with('survey_completed', '¡Encuesta completada! Muchas gracias por participar.');
        }
    }

    /**
     * Muestra el Podio (Top 3)
     */
    public function podio($preguntaId)
    {
        $pregunta = Pregunta::findOrFail($preguntaId);
        
        $resultados = Respuesta::where('pregunta_id', $preguntaId)
            ->select('respuesta', DB::raw('count(*) as total'))
            ->groupBy('respuesta')
            ->orderBy('total', 'desc')
            ->take(3) 
            ->get();

        return view('presentacion.podio', [
            'pregunta' => $pregunta,
            'resultados' => $resultados,
        ]);
    }

    /**
     * Muestra los Resultados Completos
     */
    public function resultados($preguntaId)
    {
        $pregunta = Pregunta::findOrFail($preguntaId);
        
        $resultados = Respuesta::where('pregunta_id', $preguntaId)
            ->select('respuesta', DB::raw('count(*) as total'))
            ->groupBy('respuesta')
            ->orderBy('total', 'desc')
            ->get();
            
        $totalVotos = $resultados->sum('total');

        return view('presentacion.resultados', [
            'pregunta' => $pregunta,
            'resultados' => $resultados,
            'totalVotos' => $totalVotos,
        ]);
    }
}

