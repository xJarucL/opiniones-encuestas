<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresentaciontwoController extends Controller
{
    public function validar($id)
    {
        $totalPreguntas = DB::table('preguntas')
            ->where('encuesta_id', $id)
            ->count();

        if ($totalPreguntas > 1) {
            return redirect()->route('presentacionone', $id);
        } else {
            return redirect()->route('presentacion', $id);
        }
    }

    public function index($encuestaId)
    {
        $encuesta = DB::table('encuestas')
            ->select('titulo')
            ->where('id', $encuestaId)
            ->first();

        $tituloEncuesta = $encuesta->titulo ?? 'Nominados';

        return view('users.presentacionone', compact('encuestaId', 'tituloEncuesta'));
    }

    public function inicio($encuestaId, $preguntaIndex)
    {
        $preguntas = DB::table('preguntas')
            ->where('encuesta_id', $encuestaId)
            ->orderBy('id')
            ->get();

        if ($preguntas->isEmpty()) {
            return redirect()->route('resultadostwo', $encuestaId);
        }

        $preguntaActual = $preguntas[$preguntaIndex] ?? null;

        if (!$preguntaActual) {
            return redirect()->route('resultadostwo', $encuestaId);
        }

        $tituloEncuesta = $preguntaActual->texto ?? 'Pregunta';

        return view('users.presentaciontwo', compact('encuestaId', 'preguntaIndex', 'tituloEncuesta'));
    }

    public function podio($encuestaId, $preguntaIndex)
    {
        $preguntas = DB::table('preguntas')
            ->where('encuesta_id', $encuestaId)
            ->orderBy('id')
            ->get();

        if (!isset($preguntas[$preguntaIndex])) {
            return redirect()->route('resultadostwo', $encuestaId);
        }

        $pregunta = $preguntas[$preguntaIndex];

        // Obtener el total de votos para esta pregunta
        $totalVotos = DB::table('respuestas')
            ->where('pregunta_id', $pregunta->id)
            ->count();

        // CORREGIDO: Usar el campo 'respuesta' en lugar de 'texto'
        $topRespuestas = DB::table('respuestas')
            ->select(
                'respuesta',
                DB::raw('COUNT(*) as total')
            )
            ->where('pregunta_id', $pregunta->id)
            ->whereNotNull('respuesta')
            ->where('respuesta', '!=', '')
            ->groupBy('respuesta')
            ->orderBy('total', 'DESC')
            ->limit(3)
            ->get();

        // Calcular porcentajes y convertir a array
        $resultados = $topRespuestas->map(function($item) use ($totalVotos) {
            $item->porcentaje = $totalVotos > 0 
                ? round(($item->total / $totalVotos) * 100, 2) 
                : 0;
            return $item;
        })->values()->all();

        $hayMasPreguntas = ($preguntaIndex + 1) < $preguntas->count();

        // DEBUG: Ver qué datos se están enviando (QUITAR DESPUÉS DE PROBAR)
        dd($resultados);

        return view('users.podiotwo', compact(
            'encuestaId',
            'preguntaIndex',
            'pregunta',
            'resultados',
            'hayMasPreguntas'
        ));
    }

    public function resultados($encuestaId)
    {
        $encuesta = DB::table('encuestas')
            ->select('titulo')
            ->where('id', $encuestaId)
            ->first();

        $tituloEncuesta = $encuesta->titulo ?? 'Resultados';

        // CORREGIDO: Usar el campo 'respuesta' en lugar de 'texto'
        $allResultados = DB::select("
            SELECT 
                p.id AS pregunta_id,
                p.texto AS pregunta,
                r.respuesta AS respuesta,
                COUNT(r.id) AS total_votos,
                ROUND(
                    COUNT(r.id) * 100.0 / NULLIF(
                        (SELECT COUNT(*) FROM respuestas r2 WHERE r2.pregunta_id = p.id),
                        0
                    ),
                    2
                ) AS porcentaje
            FROM preguntas p
            LEFT JOIN respuestas r ON p.id = r.pregunta_id
            WHERE p.encuesta_id = ?
            GROUP BY p.id, p.texto, r.respuesta
            ORDER BY p.id, total_votos DESC
        ", [$encuestaId]);

        $resultados = collect($allResultados)->groupBy('pregunta_id');

        return view('users.resultadostwo', compact('resultados', 'tituloEncuesta', 'encuestaId'));
    }
}