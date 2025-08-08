<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectDataTable;
use App\Models\DynamicColumn;
use Illuminate\Http\Request;

class ProjectDataTableController extends Controller
{
    public function show(Project $project)
    {
        $dataTable = $project->dataTable()->with('columns')->first();
        
        if (!$dataTable) {
            return redirect()->route('projects.index')->with('error', 'No se encontró la tabla de datos.');
        }

        return view('project-data-tables.show', compact('project', 'dataTable'));
    }

    public function addColumn(Request $request, Project $project)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:text,number,date,select,boolean',
            'es_requerido' => 'boolean',
            'opciones' => 'nullable|array'
        ]);

        $dataTable = $project->dataTable;
        $lastOrder = $dataTable->columns()->max('orden') ?? 0;

        $dataTable->columns()->create([
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
            'es_requerido' => $request->es_requerido ?? false,
            'es_fijo' => false,
            'opciones' => $request->tipo === 'select' ? $request->opciones : null,
            'orden' => $lastOrder + 1
        ]);

        return redirect()->route('projects.data-table', $project)->with('success', 'Columna agregada exitosamente');
    }

    public function removeColumn(Project $project, DynamicColumn $column)
    {
        if ($column->es_fijo) {
            return redirect()->back()->with('error', 'No se puede eliminar una columna fija.');
        }

        $column->delete();

        return redirect()->route('projects.data-table', $project)->with('success', 'Columna eliminada exitosamente');
    }

    public function updateColumnOptions(Project $project, DynamicColumn $column, Request $request)
    {
        if (!$column->es_fijo || $column->tipo !== 'select') {
            return redirect()->back()->with('error', 'Solo se pueden editar opciones de columnas fijas tipo select.');
        }

        $request->validate([
            'opciones' => 'required|array|min:1',
            'opciones.*' => 'required|string|max:255'
        ]);

        $column->update([
            'opciones' => array_filter($request->opciones)
        ]);

        return redirect()->route('projects.data-table', $project)->with('success', 'Opciones actualizadas exitosamente');
    }

    public function manageData(Project $project)
    {
        $dataTable = $project->dataTable;
        $columns = $dataTable->columns;
        $records = $dataTable->records()->latest()->paginate(10);

        return view('project-data-tables.manage-data', compact('project', 'dataTable', 'columns', 'records'));
    }

    public function storeRecord(Project $project, Request $request)
    {
        $dataTable = $project->dataTable;
        $columns = $dataTable->columns;
        
        $rules = [];
        $datos = [];
        
        foreach ($columns as $column) {
            $fieldName = 'campo_' . $column->id;
            
            if ($column->es_requerido) {
                $rules[$fieldName] = 'required';
            }
            
            if ($column->tipo === 'number') {
                $rules[$fieldName] = ($rules[$fieldName] ?? '') . '|numeric';
            } elseif ($column->tipo === 'date') {
                $rules[$fieldName] = ($rules[$fieldName] ?? '') . '|date';
            } elseif ($column->tipo === 'select' && $column->opciones) {
                $rules[$fieldName] = ($rules[$fieldName] ?? '') . '|in:' . implode(',', $column->opciones);
            }
            
            $datos[$column->nombre] = $request->input($fieldName);
        }
        
        $request->validate($rules);
        
        $dataTable->records()->create([
            'datos' => $datos
        ]);

        return redirect()->route('projects.data-table.manage-data', $project)->with('success', 'Registro agregado exitosamente');
    }

    public function editRecord(Project $project, $recordId)
    {
        $dataTable = $project->dataTable;
        $columns = $dataTable->columns;
        $record = $dataTable->records()->findOrFail($recordId);

        return view('project-data-tables.edit-record', compact('project', 'dataTable', 'columns', 'record'));
    }

    public function updateRecord(Project $project, $recordId, Request $request)
    {
        $dataTable = $project->dataTable;
        $columns = $dataTable->columns;
        $record = $dataTable->records()->findOrFail($recordId);
        
        $rules = [];
        $datos = [];
        
        foreach ($columns as $column) {
            $fieldName = 'campo_' . $column->id;
            
            if ($column->es_requerido) {
                $rules[$fieldName] = 'required';
            }
            
            if ($column->tipo === 'number') {
                $rules[$fieldName] = ($rules[$fieldName] ?? '') . '|numeric';
            } elseif ($column->tipo === 'date') {
                $rules[$fieldName] = ($rules[$fieldName] ?? '') . '|date';
            } elseif ($column->tipo === 'select' && $column->opciones) {
                $rules[$fieldName] = ($rules[$fieldName] ?? '') . '|in:' . implode(',', $column->opciones);
            }
            
            $datos[$column->nombre] = $request->input($fieldName);
        }
        
        $request->validate($rules);
        
        $record->update([
            'datos' => $datos
        ]);

        return redirect()->route('projects.data-table.manage-data', $project)->with('success', 'Registro actualizado exitosamente');
    }

    public function deleteRecord(Project $project, $recordId)
    {
        $dataTable = $project->dataTable;
        $record = $dataTable->records()->findOrFail($recordId);
        $record->delete();

        return redirect()->route('projects.data-table.manage-data', $project)->with('success', 'Registro eliminado exitosamente');
    }
}
