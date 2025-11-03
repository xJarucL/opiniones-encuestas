<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresentacionController extends Controller
{
    public function index($preguntaId)
    {
        // Obtener el título de la encuesta
        $encuesta = DB::select("
            SELECT e.titulo 
            FROM encuestas e
            INNER JOIN preguntas p ON e.id = p.encuesta_id
            WHERE p.id = ?
            LIMIT 1
        ", [$preguntaId]);
        
        $tituloEncuesta = $encuesta[0]->titulo ?? 'Nominados';

        return view('users.presentacion', compact('preguntaId', 'tituloEncuesta'));
    }

    public function podio($preguntaId)
    {
        $resultados = DB::select("
            SELECT 
                texto AS nombre, 
                COUNT(*) AS total_votos, 
                ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM respuestas WHERE pregunta_id = ?)), 2) AS porcentaje 
            FROM respuestas 
            WHERE pregunta_id = ? 
            GROUP BY texto 
            ORDER BY total_votos DESC
            LIMIT 3
        ", [$preguntaId, $preguntaId]);
        
        $podio = [
            'primero' => $resultados[0] ?? null,
            'segundo' => $resultados[1] ?? null,
            'tercero' => $resultados[2] ?? null,
        ];
        
        return view('users.podio', compact('podio', 'preguntaId'));
    }

    public function resultados($preguntaId)
    {
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