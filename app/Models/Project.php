<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre'
    ];

    public function dataTable()
    {
        return $this->hasOne(ProjectDataTable::class);
    }

    protected static function booted()
    {
        static::created(function ($project) {
            // Crear automáticamente la tabla de datos cuando se crea un proyecto
            $dataTable = $project->dataTable()->create([
                'nombre' => 'Datos',
                'descripcion' => 'Tabla de datos principal del proyecto'
            ]);

            // Crear las columnas fijas (no removibles)
            $dataTable->columns()->create([
                'nombre' => 'Actor',
                'tipo' => 'text',
                'es_requerido' => true,
                'es_fijo' => true,
                'orden' => 1
            ]);

            $dataTable->columns()->create([
                'nombre' => 'Rol',
                'tipo' => 'select',
                'es_requerido' => true,
                'es_fijo' => true,
                'opciones' => ['Productor', 'Comerciante', 'Investigador', 'Institucional'],
                'orden' => 2
            ]);

            $dataTable->columns()->create([
                'nombre' => 'Categoría',
                'tipo' => 'select',
                'es_requerido' => true,
                'es_fijo' => true,
                'opciones' => ['Primario', 'Secundario', 'Terciario'],
                'orden' => 3
            ]);
        });
    }
}

