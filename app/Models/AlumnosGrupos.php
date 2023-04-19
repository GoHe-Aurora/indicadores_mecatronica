<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlumnosGrupos extends Model
{
    use HasFactory;

    protected $table = 'alumnos_grupos';
    protected $primaryKey = 'idag';
    protected $fillable = [
        'alumno_id','grupo_id'
    ];

}
