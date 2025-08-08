<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicColumn extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_data_table_id',
        'nombre',
        'tipo',
        'es_requerido',
        'es_fijo',
        'opciones',
        'orden'
    ];

    protected $casts = [
        'opciones' => 'array',
        'es_requerido' => 'boolean',
        'es_fijo' => 'boolean'
    ];

    public function projectDataTable()
    {
        return $this->belongsTo(ProjectDataTable::class);
    }
}
