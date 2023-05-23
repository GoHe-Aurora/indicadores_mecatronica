<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrayectoriaC extends Model
{
    use HasFactory;

    protected $table = 'trayectoria_cuatrimestral';
    protected $primaryKey = 'idtc';
    protected $fillable = [
        'alumno_id','unidad','materia_id','actitud','responsabilidad','colaborativo','relaciones_i','creatividad','conocimiento','manejo_inf','marco_tc','desempeno','practicas','estudios_caso','proyecto','ejercicios','ensayo','calificacion','calificacion_acta'
    ];

}
