<?php
namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::all();
        return view('categoria.index', compact('categorias'));
    }

    public function create()
    {
        return view('categoria.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_cat' => 'required|string|unique:categoria,nombre_cat',
        ]);
        Categoria::create($data);
        return redirect()->route('categoria.index')->with('success', 'Categoría creada correctamente');
    }

    public function edit(Categoria $categoria)
    {
        return view('categoria.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $data = $request->validate([
            'nombre_cat' => 'required|string|unique:categoria,nombre_cat,' . $categoria->id,
        ]);
        $categoria->update($data);
        return redirect()->route('categoria.index')->with('success', 'Categoría actualizada correctamente');
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return redirect()->route('categoria.index')->with('success', 'Categoría eliminada correctamente');
    }
}
