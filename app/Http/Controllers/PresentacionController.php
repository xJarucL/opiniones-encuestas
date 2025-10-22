<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PresentacionController extends Controller
{
    public function index()
    {
        return view('users.presentacion');
    }

    public function podio()
    {
        return view('users.podio');
    }

    public function resultados()
    {
        return view('users.resultados');
    }
}