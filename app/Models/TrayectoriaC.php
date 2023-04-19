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
        'alumno_id','unidad','materia_id','actitud','conocimiento','desempeno','calificacion'
    ];

}
