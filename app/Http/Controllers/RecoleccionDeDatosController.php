<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectDataTable;
use App\Models\DynamicRecord;

class RecoleccionDeDatosController extends Controller
{
    /**
     * Mostrar el mapa de actores con datos dinámicos de proyectos
     */
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
        
        // Registros antiguos vacíos (sistema anterior eliminado)
        $registrosAntiguos = [];
        
        return view('trabajos.mapa-actores', compact('projects', 'selectedProject', 'selectedProjectId', 'chartData', 'columns', 'registrosAntiguos'));
    }
}
