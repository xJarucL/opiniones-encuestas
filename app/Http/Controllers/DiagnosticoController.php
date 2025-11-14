<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiagnosticoController extends Controller
{
    /**
     * Diagnostica el estado de las respuestas
     * Ruta sugerida: Route::get('/diagnostico/{encuestaId}', [DiagnosticoController::class, 'diagnosticar']);
     */
    public function diagnosticar($encuestaId)
    {
        // 1. Información de la encuesta
        $encuesta = DB::table('encuestas')->where('id', $encuestaId)->first();
        
        // 2. Preguntas de la encuesta
        $preguntas = DB::table('preguntas')
            ->where('encuesta_id', $encuestaId)
            ->get();
        
        // 3. Todas las respuestas con detalles
        $respuestas = DB::table('respuestas')
            ->join('preguntas', 'respuestas.pregunta_id', '=', 'preguntas.id')
            ->join('users', 'respuestas.user_id', '=', 'users.id')
            ->where('preguntas.encuesta_id', $encuestaId)
            ->select(
                'respuestas.*',
                'preguntas.texto as pregunta_texto',
                'users.name as votante_nombre',
                'users.email as votante_email'
            )
            ->get();
        
        // 4. Análisis por pregunta
        $analisisPorPregunta = [];
        foreach ($preguntas as $pregunta) {
            $respuestasPregunta = DB::table('respuestas')
                ->where('pregunta_id', $pregunta->id)
                ->get();
            
            $agrupadas = $respuestasPregunta->groupBy('texto')->map(function($group) {
                return [
                    'nombre_nominado' => $group->first()->texto ?? 'VACÍO',
                    'total_votos' => $group->count(),
                    'es_vacio' => empty($group->first()->texto)
                ];
            })->sortByDesc('total_votos')->values();
            
            $analisisPorPregunta[] = [
                'pregunta_id' => $pregunta->id,
                'pregunta_texto' => $pregunta->texto,
                'total_respuestas' => $respuestasPregunta->count(),
                'respuestas_vacias' => $respuestasPregunta->filter(function($r) {
                    return empty($r->texto);
                })->count(),
                'top_3' => $agrupadas->take(3),
                'todas_agrupadas' => $agrupadas
            ];
        }
        
        // 5. Verificar estructura de tabla respuestas
        $columnasRespuestas = DB::select("DESCRIBE respuestas");
        
        // 6. Muestra de datos crudos
        $muestraRespuestas = DB::table('respuestas')
            ->join('preguntas', 'respuestas.pregunta_id', '=', 'preguntas.id')
            ->where('preguntas.encuesta_id', $encuestaId)
            ->limit(10)
            ->get();
        
        return view('diagnostico', compact(
            'encuesta',
            'preguntas',
            'respuestas',
            'analisisPorPregunta',
            'columnasRespuestas',
            'muestraRespuestas'
        ));
    }
    
    /**
     * Corrige respuestas vacías usando el user_id
     * SOLO usar si confirmamos que el problema es que texto está vacío
     */
    public function corregirRespuestasVacias($encuestaId)
    {
        $respuestasVacias = DB::table('respuestas')
            ->join('preguntas', 'respuestas.pregunta_id', '=', 'preguntas.id')
            ->where('preguntas.encuesta_id', $encuestaId)
            ->whereNull('respuestas.texto')
            ->orWhere('respuestas.texto', '')
            ->get();
        
        $corregidas = 0;
        
        foreach ($respuestasVacias as $respuesta) {
            // Buscar el usuario nominado
            $nominado = DB::table('users')->where('id', $respuesta->user_id)->first();
            
            if ($nominado) {
                DB::table('respuestas')
                    ->where('id', $respuesta->id)
                    ->update(['texto' => $nominado->name]);
                $corregidas++;
            }
        }
        
        return response()->json([
            'success' => true,
            'respuestas_vacias_encontradas' => $respuestasVacias->count(),
            'respuestas_corregidas' => $corregidas
        ]);
    }
}