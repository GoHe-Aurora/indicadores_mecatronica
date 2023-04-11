<?php

namespace App\Http\Controllers\ValoracionAE;

use File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TiposUsuarios;
use App\Models\ValoracionAE;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ValoracionAEController extends Controller
{
    /**
     * Vista para mostrar un listado de alumnos.
     */
    public function index(Request $request)
    {
        $grupo_id = '';
        $condition = '';
        if($request->grupo){
            $grupo_id = $request->grupo;
            $condition = "WHERE g.idgr=$grupo_id";
        }
        $array = array();
        $alumnos = DB::select("SELECT v.*,a.nombre,a.app,a.apm,a.matricula,g.nombre grupo,g.idgr FROM valoracion_ae v INNER JOIN alumnos a ON v.alumno_id=a.ida INNER JOIN grupos g ON a.grupo_id=g.idgr $condition;");
        $grupos_tsu = DB::select("SELECT idgr,nombre FROM grupos WHERE cuatrimestre_id<7;");
        $grupos_ing = DB::select("SELECT idgr,nombre FROM grupos WHERE cuatrimestre_id>=7;");
        function btn($idv){
           
                $botones = "<a href=\"#eliminar-vae\" class=\"btn btn-danger mt-1\" onclick=\"formSubmit('eliminar-vae-$idv')\"><i class='fas fa-power-off'></i></a>"
                         . "<a href= ". route('valoracion_ae.edit', $idv ) ." class=\"btn btn-primary mt-1\"> <i class='fa fa-user-alt'></i> </a>";
                
            return $botones;
        }
        foreach ($alumnos as $alumno){

            array_push($array, array(
                'idv'                 => $alumno->idv,
                'nombre'              => $alumno->nombre,
                'app'                 => $alumno->app,
                'apm'                 => $alumno->apm,
                'grupo_tsu'           => $alumno->grupo_tsu,
                'grupo_ing'           => $alumno->grupo_ing,
                'promedio_tsu'        => $alumno->promedio_tsu,
                'promedio_ing'        => $alumno->promedio_ing,
                'operaciones'         => btn($alumno->idv)
            ));
        }
        $json = json_encode($array);
        return view("valoracion_ae.index", compact("json","alumnos","grupos_tsu","grupos_ing","grupo_id"));
    }

    /**
     * Vista que muestra un formulario para crear un usuario.
     */
    public function create()
    {
        $grupos = DB::select("SELECT idgr,nombre FROM grupos;");

        return view( 'valoracion_ae.create', compact('grupos'));

    }

    /**
     * Guardar un usuario.
     */
    public function store(Request $request)
    {

        $validator = $request->validate([
            'alumno' => 'required',
            'promedio_tsu'  => 'required|numeric',
            'promedio_ing'  => 'required|numeric',
            'grupo' => 'required', 
        ]);
        
        ValoracionAE::create([
            'alumno_id' => $request->alumno,
            'promedio_tsu' => $request->promedio_tsu,
            'promedio_ing' => $request->promedio_ing,
            'grupo_tsu' => isset($request->grupo_tsu) ? $request->grupo_tsu : null,
            'grupo_ing' => isset($request->grupo_ing) ? $request->grupo_ing : null
        ]);
        return redirect()->route('valoracion_ae.index')->with('mensaje', 'El registro se ha guardado exitosamente');
    }

    public function edit($idv)
    {
        $alumno = DB::select("SELECT v.*,a.ida,a.nombre,a.app,a.apm,a.matricula,g.nombre grupo,g.idgr FROM valoracion_ae v INNER JOIN alumnos a ON v.alumno_id=a.ida INNER JOIN grupos g ON a.grupo_id=g.idgr WHERE v.idv=$idv;");  
        $grupos = DB::select("SELECT idgr,nombre FROM grupos;");
        
        return view('valoracion_ae.edit', compact('alumno','grupos'));
    }

    /**
     * Actualiza un usuario.
     */
    public function update(Request $request, $idv)
    {
        $validator = $request->validate([
            'alumno' => 'required',
            'promedio'  => 'required|numeric',
            'grupo' => 'required', 
        ]);
        ValoracionAE::where('idv',$idv)->update([
            'alumno_id' => $request->alumno,
            'promedio' => $request->promedio,
            'grupo_id' => $request->grupo,
        ]);
                
                return redirect()->route('valoracion_ae.index')->with('mensaje', 'El registro se ha actualizado exitosamente');
            
    }

    public function delete($idv)
    {
        DB::delete("DELETE FROM valoracion_ae WHERE idv=$idv;"); 
        return back()->with('mensaje', 'Registro eliminado exitosamente');
    }

}
