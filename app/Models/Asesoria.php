<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asesoria extends Model
{
    use HasFactory;

    protected $table = 'asesorias';
    protected $primaryKey = 'idas';
    protected $fillable = [
        'titulo','descripcion','maestro_id','fecha','archivo'
    ];

}
