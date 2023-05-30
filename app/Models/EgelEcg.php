<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EgelEcg extends Model
{
    use HasFactory;

    protected $table = 'egel_ecg';
    protected $primaryKey = 'ide';
    protected $fillable = [
        'alumno_id','promedio_tsu','promedio_ing','grupo_tsu','grupo_ing'
    ];

}
