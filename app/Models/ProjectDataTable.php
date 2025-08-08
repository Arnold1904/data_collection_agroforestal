<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDataTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'nombre',
        'descripcion'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function columns()
    {
        return $this->hasMany(DynamicColumn::class)->orderBy('orden');
    }

    public function records()
    {
        return $this->hasMany(DynamicRecord::class);
    }
}
