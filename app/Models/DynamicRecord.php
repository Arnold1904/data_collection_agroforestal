<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_data_table_id',
        'datos'
    ];

    protected $casts = [
        'datos' => 'array'
    ];

    public function projectDataTable()
    {
        return $this->belongsTo(ProjectDataTable::class);
    }
}
