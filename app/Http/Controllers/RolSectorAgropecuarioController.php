<?php
namespace App\Http\Controllers;

use App\Models\RolSectorAgropecuario;
use Illuminate\Http\Request;

class RolSectorAgropecuarioController extends Controller
{
    public function index()
    {
        $roles = RolSectorAgropecuario::all();
        return view('rol.index', compact('roles'));
    }

    public function create()
    {
        return view('rol.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_rol' => 'required|string|unique:rol_sector_agropecuario,nombre_rol',
        ]);
        RolSectorAgropecuario::create($data);
        return redirect()->route('rol.index')->with('success', 'Rol creado correctamente');
    }

    public function edit(RolSectorAgropecuario $rol)
    {
        return view('rol.edit', compact('rol'));
    }

    public function update(Request $request, RolSectorAgropecuario $rol)
    {
        $data = $request->validate([
            'nombre_rol' => 'required|string|unique:rol_sector_agropecuario,nombre_rol,' . $rol->id,
        ]);
        $rol->update($data);
        return redirect()->route('rol.index')->with('success', 'Rol actualizado correctamente');
    }

    public function destroy(RolSectorAgropecuario $rol)
    {
        $rol->delete();
        return redirect()->route('rol.index')->with('success', 'Rol eliminado correctamente');
    }
}
