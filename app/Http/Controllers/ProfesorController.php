<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfesorController extends Controller
{
    public function index()
    {
        // Aquí irá la lógica para la vista del profesor
        return view('profesor.index');
    }
}
