<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValoracionAE extends Model
{
    use HasFactory;

    protected $table = 'valoracion_ae';
    protected $primaryKey = 'idv';
    protected $fillable = [
        'alumno_id','promedio_tsu','promedio_ing','grupo_tsu','grupo_ing'
    ];

}
