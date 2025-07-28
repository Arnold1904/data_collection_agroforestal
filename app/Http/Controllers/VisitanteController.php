<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VisitanteController extends Controller
{
    public function index()
    {
        // Vista para usuarios visitantes
        return view('visitante.index');
    }
}
