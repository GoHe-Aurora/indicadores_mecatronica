<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutoria extends Model
{
    use HasFactory;

    protected $table = 'tutorias';
    protected $primaryKey = 'idt';
    protected $fillable = [
        'maestro_id','tipo','grupo_id','alumno_id','fecha','archivo_nombre','archivo'
    ];

}
