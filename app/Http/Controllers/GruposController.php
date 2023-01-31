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
        $alumnos = DB::select("SELECT nombre,app,apm,matricula,if(estatus=1,'Activo','Inactivo') estatus FROM alumnos;");
        $json = json_encode($alumnos);
        return view("students.index", compact("json"));
    }
}
