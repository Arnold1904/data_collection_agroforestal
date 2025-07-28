<?php

namespace App\Http\Controllers;

use App\Models\RecoleccionDeDatos;
use App\Models\Categoria;
use App\Models\RolSectorAgropecuario;
use Illuminate\Http\Request;
use App\Models\Influencia;

class RecoleccionDeDatosController extends Controller
{
    public function index()
    {
        $registros = RecoleccionDeDatos::with(['categoria', 'rol'])->get();
        return view('trabajos.index', compact('registros'));
    }

    public function create()
    {
        $categorias = Categoria::pluck('nombre_cat', 'nombre_cat');
        $roles = RolSectorAgropecuario::pluck('nombre_rol', 'nombre_rol');
        $influencias = Influencia::pluck('nivel', 'nivel');
        return view('trabajos.create', compact('categorias', 'roles', 'influencias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'actor' => 'required|string',
            'categoria' => 'required|string|exists:categoria,nombre_cat',
            'rol' => 'required|string|exists:rol_sector_agropecuario,nombre_rol',
            'influencia' => 'required|string',
            'relcamclim' => 'required|string',
            'vinagro' => 'required|string',
            'observaciones' => 'nullable|string',
        ]);
        RecoleccionDeDatos::create($data);
        return redirect()->route('trabajos.index')->with('success', 'Registro creado correctamente');
    }

    public function edit(RecoleccionDeDatos $trabajo)
    {
        $categorias = Categoria::pluck('nombre_cat', 'nombre_cat');
        $roles = RolSectorAgropecuario::pluck('nombre_rol', 'nombre_rol');
        $influencias = Influencia::pluck('nivel', 'nivel');
        return view('trabajos.edit', compact('trabajo', 'categorias', 'roles', 'influencias'));
    }

    public function update(Request $request, RecoleccionDeDatos $trabajo)
    {
        $data = $request->validate([
            'actor' => 'required|string',
            'categoria' => 'required|string|exists:categoria,nombre_cat',
            'rol' => 'required|string|exists:rol_sector_agropecuario,nombre_rol',
            'influencia' => 'required|string',
            'relcamclim' => 'required|string',
            'vinagro' => 'required|string',
            'observaciones' => 'nullable|string',
        ]);
        $trabajo->update($data);
        return redirect()->route('trabajos.index')->with('success', 'Registro actualizado correctamente');
    }

    public function destroy(RecoleccionDeDatos $trabajo)
    {
        $trabajo->delete();
        return redirect()->route('trabajos.index')->with('success', 'Registro eliminado correctamente');
    }
    
    public function mapaActores()
    {
        $registros = RecoleccionDeDatos::all();
        return view('trabajos.mapa-actores', compact('registros'));
    }

    public function show(RecoleccionDeDatos $trabajo)
    {
        // Puedes personalizar la vista o solo retornar los datos
        return view('trabajos.show', compact('trabajo'));
    }
}
