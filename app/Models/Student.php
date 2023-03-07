<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'alumnos';
    protected $primaryKey = 'ida';
    protected $fillable = [
        'nombre','app','apm','matricula','genero','grupo_id','promedio_general','estatus_id','motivo','activo'
    ];

}
