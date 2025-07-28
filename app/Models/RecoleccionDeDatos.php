<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecoleccionDeDatos extends Model
{
    protected $table = 'recoleccion_de_datos';
    public $timestamps = false;
    protected $fillable = [
        'actor', 'categoria', 'rol', 'influencia', 'relcamclim', 'vinagro', 'observaciones', 'created_at'
    ];

    public function categoria()
    {
        // Relación por texto: categoria (en recoleccion_de_datos) <-> nombre_cat (en categoria)
        return $this->belongsTo(Categoria::class, 'categoria', 'nombre_cat');
    }

    public function rol()
    {
        // Relación por texto: rol (en recoleccion_de_datos) <-> nombre_rol (en rol_sector_agropecuario)
        return $this->belongsTo(RolSectorAgropecuario::class, 'rol', 'nombre_rol');
    }
}
