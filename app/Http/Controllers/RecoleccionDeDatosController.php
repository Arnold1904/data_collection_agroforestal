<?php

namespace App\Http\Controllers;

use App\Models\RecoleccionDeDatos;
use App\Models\Categoria;
use App\Models\RolSectorAgropecuario;
use Illuminate\Http\Request;
use App\Models\Influencia;
use App\Models\Project;
use App\Models\ProjectDataTable;
use App\Models\DynamicRecord;

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
    
    public function mapaActores(Request $request)
    {
        // Obtener todos los proyectos disponibles
        $projects = Project::with('dataTable.columns')->get();
        
        // Proyecto seleccionado (por defecto el primero o el especificado en la URL)
        $selectedProjectId = $request->get('proyecto') ?? $projects->first()?->id;
        $selectedProject = $projects->find($selectedProjectId);
        
        // Datos para la gráfica
        $chartData = [];
        $columns = collect();
        
        if ($selectedProject && $selectedProject->dataTable) {
            $columns = $selectedProject->dataTable->columns;
            $records = $selectedProject->dataTable->records;
            
            // Preparar datos para la gráfica
            foreach ($records as $record) {
                $node = [
                    'id' => 'record_' . $record->id,
                    'name' => $record->datos['Actor'] ?? 'Sin nombre',
                    'data' => $record->datos,
                    'created_at' => $record->created_at->format('d/m/Y')
                ];
                $chartData[] = $node;
            }
        }
        
        // También incluir registros antiguos para compatibilidad
        $registrosAntiguos = RecoleccionDeDatos::all();
        
        return view('trabajos.mapa-actores', compact('projects', 'selectedProject', 'selectedProjectId', 'chartData', 'columns', 'registrosAntiguos'));
    }

    public function show(RecoleccionDeDatos $trabajo)
    {
        // Puedes personalizar la vista o solo retornar los datos
        return view('trabajos.show', compact('trabajo'));
    }
}
