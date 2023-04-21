<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AlumnosImport implements ToModel, WithHeadingRow
{
	protected $grupo;

    function __construct($grupo) {
        $this->grupo = $grupo;
    }
	
    public function model(array $row)
    {

       
        return new Student(
                [
                    'nombre'     => $row['nombre'],
                    'app'    => $row['app'],
                    'apm' => $row['apm'],
                    'matricula' => $row['matricula'],
                    'genero' => $row['genero'],
                    'grupo_id' => $this->grupo,
                    'promedio_general' => $row['promedio_general'],
                    'estatus_id' => 1
                ]
            );    
   }
  
   }
