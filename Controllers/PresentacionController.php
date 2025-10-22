<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PresentacionController extends Controller
{
    public function index()
    {
        // Tu lógica para la vista de presentación
        return view('presentacion.index');
    }

    public function podio()
    {
        // Tu lógica para el podio
        return view('presentacion.podio');
    }

    public function resultados()
    {
        // Tu lógica para resultados
        return view('presentacion.resultados');
    }
}