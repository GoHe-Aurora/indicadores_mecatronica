<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriasReprobadas extends Model
{
    use HasFactory;

    protected $table = 'materias_reprobadas';
    protected $primaryKey = 'idmr';
    protected $fillable = [
        'materia_id','alumno_id'
    ];

}
