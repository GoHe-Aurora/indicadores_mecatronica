<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
//use App\Models\TiposUsuarios;

class GruposController extends Controller
{
    /**
     * Vista para mostrar un listado de recuersos.
     */
    public function index()
    {
        $alumnos = DB::select("SELECT a.nombre,a.app,a.apm,a.matricula,g.nombre grupo,if(a.estatus=1,'Activo','Inactivo') estatus FROM alumnos a INNER JOIN grupos g WHERE a.grupo_id=g.idgr;");
        $json = json_encode($alumnos);
        return view("students.index", compact("json"));
    }
}
