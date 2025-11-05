<?php

namespace App\Http\Controllers;

use App\Models\Pregunta;
use App\Models\Respuesta;
use App\Models\User; // <-- ¡ASEGÚRATE DE QUE ESTO ESTÉ AQUÍ!
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
     * ==========================================================
     * ¡AQUÍ ESTÁ LA CORRECCIÓN!
     * ==========================================================
     * Muestra una pregunta para votar
     */
    public function index($preguntaId)
    {
        $pregunta = Pregunta::findOrFail($preguntaId);
        
        $opciones = []; // Inicializa un array vacío

        // Revisa el TIPO de pregunta
        if ($pregunta->tipo === 'nominados') {
            
            // 1. Si es 'nominados', trae a todos los usuarios
            //    (Usamos 'nombres' como nos dijiste)
            $users = User::orderBy('nombres', 'asc')->get();
            
            // 2. Convierte la colección de usuarios en un array simple de nombres
            //    Tu vista espera: ['Admin Admin', 'edi heberto', 'test test']
            $opciones = $users->pluck('nombres')->toArray();

        } else {
            // 3. Si es 'multiple' (la lógica antigua), solo decodifica el JSON
            $opcionesJSON = json_decode($pregunta->opciones, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($opcionesJSON)) {
                $opciones = $opcionesJSON; 
            }
        }

        // El resto de tu función sigue igual
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
            'opciones' => $opciones, // <-- ¡Ahora $opciones tendrá la lista de usuarios!
            'haVotado' => $haVotado, 
        ]);
    }

    /**
     * Guarda la respuesta de la encuesta
     */
    public function store(Request $request)
    {
        // ... (Este método está bien, no se toca) ...
        $request->validate([
            'pregunta_id' => 'required|exists:preguntas,id',
            'respuesta' => 'required|string', // <-- Esto funciona porque estamos guardando el NOMBRE
        ]);

        $pregunta = Pregunta::findOrFail($request->pregunta_id);
        
        $votoExistente = Respuesta::where('pregunta_id', $pregunta->id)
                                    ->where('user_id', Auth::id())
                                    ->exists();

        if ($votoExistente) {
            // Redirige al podio SI YA VOTÓ
            return redirect()->route('podio', ['preguntaId' => $pregunta->id]);
        }

        Respuesta::create([
            'pregunta_id' => $pregunta->id,
            'user_id' => Auth::id(), 
            'respuesta' => $request->respuesta, // Guarda el nombre del usuario, ej: "edi heberto"
        ]);
        
        // ==========================================================
        // ¡CAMBIO IMPORTANTE AQUÍ!
        // Después de votar, no vayas a la siguiente pregunta
        // (porque no hay "siguiente"). Ve directo al PODIO.
        // ==========================================================
        return redirect()->route('podio', ['preguntaId' => $pregunta->id])
                         ->with('voto_registrado', '¡Tu voto ha sido registrado!');
        
        /*
        // ESTA LÓGICA ANTIGUA YA NO APLICA PARA VOTACIONES
        $siguientePregunta = Pregunta::where('encuesta_id', $pregunta->encuesta_id)
                                        ->where('orden', '>', $pregunta->orden)
                                        ->orderBy('orden', 'asc')
                                        ->first();

        if ($siguientePregunta) {
            return redirect()->route('presentacion', ['preguntaId' => $siguientePregunta->id]);
        } else {
            return redirect()->route('encuestas.index') 
                    ->with('survey_completed', '¡Encuesta completada! Muchas gracias por participar.');
        }
        */
    }

    /**
     * Muestra el Podio (Top 3)
     */
    public function podio($preguntaId)
    {
        // ... (Este método está bien, no se toca) ...
        // (Asegúrate de que la consulta use "texto AS nombre" como lo dejamos)
        $pregunta = Pregunta::findOrFail($preguntaId);

        $resultados = DB::select("
            SELECT 
                texto AS nombre, 
                COUNT(*) AS total_votos 
            FROM respuestas 
            WHERE pregunta_id = ? 
            GROUP BY texto 
            ORDER BY total_votos DESC
            LIMIT 3
        ", [$preguntaId]);
        
        $podio = [
            'primero' => $resultados[0] ?? null,
            'segundo' => $resultados[1] ?? null,
            'tercero' => $resultados[2] ?? null,
        ];
        
        // NOTA: Tu vista 'users.podio' original (la que no era morada) no
        // usa $pregunta. Si quieres mostrar el título, tendrás que pasarla.
        return view('users.podio', compact('podio', 'preguntaId', 'pregunta'));
    }

    /**
     * Muestra los Resultados Completos
     */
    public function resultados($preguntaId)
    {
        // ... (Este método está bien, no se toca) ...
        $encuesta = DB::select("
            SELECT e.titulo 
            FROM encuestas e
            INNER JOIN preguntas p ON e.id = p.encuesta_id
            WHERE p.id = ?
            LIMIT 1
        ", [$preguntaId]);
        
        $tituloEncuesta = $encuesta[0]->titulo ?? 'Resultados';
        
        $resultados = DB::select("
            SELECT 
                texto AS nombre, 
                COUNT(*) AS total_votos, 
                ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM respuestas WHERE pregunta_id = ?)), 2) AS porcentaje 
            FROM respuestas 
            WHERE pregunta_id = ? 
            GROUP BY texto 
            ORDER BY total_votos DESC
        ", [$preguntaId, $preguntaId]);
        
        $totalParticipantes = count($resultados);

        return view('users.resultados', compact('resultados', 'tituloEncuesta', 'totalParticipantes', 'preguntaId'));
    }
}