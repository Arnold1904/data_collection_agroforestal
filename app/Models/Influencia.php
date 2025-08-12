<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Influencia extends Model
{
    protected $table = 'nivel_influencia';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['nivel'];
}
