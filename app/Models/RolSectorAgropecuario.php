<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolSectorAgropecuario extends Model
{
    protected $table = 'rol_sector_agropecuario';
    public $timestamps = false;
    protected $fillable = ['nombre_rol', 'created_at'];
}
