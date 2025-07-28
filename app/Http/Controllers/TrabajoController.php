<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrabajoController extends Controller
{
    public function index()
    {
        // Aquí irá la lógica para mostrar los trabajos
        return view('trabajos.index');
    }
}
