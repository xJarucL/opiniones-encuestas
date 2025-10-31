<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresentacionController extends Controller
{
    public function index($preguntaId)
    {
        // Tu lógica para la vista de presentación
        return view('presentacion.index');
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
        //$preguntaId = 1; ID de la pregunta "quien es el mas guapo"
        
        // Consulta para obtener los top 3
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
        
        // Si hay resultados, organizarlos para el podio
        $podio = [
            'primero' => $resultados[0] ?? null,
            'segundo' => $resultados[1] ?? null,
            'tercero' => $resultados[2] ?? null,
        ];
        
        return view('users.podio', compact('podio', 'preguntaId'));
    }

    public function resultados($preguntaId)
    {

        // Obtener el título de la encuesta
        $encuesta = DB::select("
            SELECT e.titulo 
            FROM encuestas e
            INNER JOIN preguntas p ON e.id = p.encuesta_id
            WHERE p.id = ?
            LIMIT 1
        ", [$preguntaId]);
        
        $tituloEncuesta = $encuesta[0]->titulo ?? 'Resultados';
        
        // Obtener todos los resultados ordenados
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
        
        // Contar total de participantes
        $totalParticipantes = count($resultados);

        return view('users.resultados', compact('resultados', 'tituloEncuesta', 'totalParticipantes', 'preguntaId'));
    }
}