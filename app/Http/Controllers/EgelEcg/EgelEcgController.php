<?php

namespace App\Http\Controllers\EgelEcg;

use File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TiposUsuarios;
use App\Models\EgelEcg;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class EgelEcgController extends Controller
{
    /**
     * Vista para mostrar un listado de alumnos.
     */
    public function index(Request $request)
    {
        $grupo_tsu_id = '';
        $grupo_ing_id = '';
        $condition = '';
        $condition2 = '';
        if($request->grupo_tsu){
            $grupo_tsu_id = $request->grupo_tsu;
            $condition = "WHERE g.idgr=$grupo_tsu_id";
        }
        if($request->grupo_ing){
            $grupo_ing_id = $request->grupo_ing;
            $condition2 = "WHERE g.idgr=$grupo_ing_id";
        }
        $array = array();
        $alumnos = DB::select("SELECT v.*,a.ida,a.nombre,a.app,a.apm,a.matricula,g.nombre gr_tsu,g2.nombre gr_ing FROM egel_ecg v INNER JOIN alumnos a ON v.alumno_id=a.ida INNER JOIN grupos g ON v.grupo_tsu=g.idgr LEFT JOIN grupos g2 ON v.grupo_ing=g2.idgr $condition $condition2;");
        $grupos_tsu = DB::select("SELECT idgr,nombre FROM grupos WHERE cuatrimestre_id<7;");
        $grupos_ing = DB::select("SELECT idgr,nombre FROM grupos WHERE cuatrimestre_id>=7;");
        function btn($idv){
           
                $botones = "<a href=\"#eliminar-egel_ecg\" class=\"btn btn-danger mt-1\" onclick=\"formSubmit('eliminar-egel_ecg-$idv')\"><i class='fas fa-power-off'></i></a>"
                         . "<a href= ". route('egel_ecg.edit', $idv ) ." class=\"btn btn-primary mt-1\"> <i class='fa fa-user-alt'></i> </a>";
                
            return $botones;
        }
        foreach ($alumnos as $alumno){

            array_push($array, array(
                'ide'                 => $alumno->ide,
                'nombre'              => $alumno->nombre,
                'app'                 => $alumno->app,
                'apm'                 => $alumno->apm,
                'grupo_tsu'           => $alumno->gr_tsu,
                'grupo_ing'           => $alumno->gr_ing,
                'promedio_tsu'        => $alumno->promedio_tsu,
                'promedio_ing'        => $alumno->promedio_ing,
                'operaciones'         => btn($alumno->ide)
            ));
        }
        $json = json_encode($array);
        return view("egel_ecg.index", compact("json","alumnos","grupos_tsu","grupos_ing","grupo_tsu_id","grupo_ing_id"));
    }

    /**
     * Vista que muestra un formulario para crear un usuario.
     */
    public function create()
    {
        $grupos_tsu = DB::select("SELECT idgr,nombre FROM grupos WHERE cuatrimestre_id<7;");
        $grupos_ing = DB::select("SELECT idgr,nombre FROM grupos WHERE cuatrimestre_id>=7;");

        return view( 'egel_ecg.create', compact('grupos_tsu','grupos_ing'));

    }

    /**
     * Guardar un usuario.
     */
    public function store(Request $request)
    {

        $validator = $request->validate([
            'alumno' => 'required',
            'promedio_tsu'  => 'required|numeric|min:8',
            'promedio_ing'  => 'required|numeric|min:8',
            'grupo_tsu' => 'required', 
        ]);
        
        ValoracionAE::create([
            'alumno_id' => $request->alumno,
            'promedio_tsu' => $request->promedio_tsu,
            'promedio_ing' => $request->promedio_ing,
            'grupo_tsu' => isset($request->grupo_tsu) ? $request->grupo_tsu : null,
            'grupo_ing' => isset($request->grupo_ing) ? $request->grupo_ing : null
        ]);
        return redirect()->route('egel_ecg.index')->with('mensaje', 'El registro se ha guardado exitosamente');
    }

    public function edit($idv)
    {
        $alumno = DB::select("SELECT v.*,a.ida,a.nombre,a.app,a.apm,a.matricula,g.nombre gr_tsu,g2.nombre gr_ing FROM egel_ecg v INNER JOIN alumnos a ON v.alumno_id=a.ida INNER JOIN grupos g ON v.grupo_tsu=g.idgr LEFT JOIN grupos g2 ON v.grupo_ing=g2.idgr WHERE v.ide=$idv;");

        $grupos_tsu = DB::select("SELECT idgr,nombre FROM grupos WHERE cuatrimestre_id<7;");
        $grupos_ing = DB::select("SELECT idgr,nombre FROM grupos WHERE cuatrimestre_id>=7;");
        
        return view('egel_ecg.edit', compact('alumno','grupos_tsu','grupos_ing'));
    }

    /**
     * Actualiza un usuario.
     */
    public function update(Request $request, $idv)
    {
        $validator = $request->validate([
            'alumno' => 'required',
            'promedio_tsu'  => 'required|numeric|min:8',
            'promedio_ing'  => 'required|numeric|min:8',
            'grupo_tsu' => 'required', 
        ]);
        ValoracionAE::where('ide',$idv)->update([
            'alumno_id' => $request->alumno,
            'promedio_tsu' => $request->promedio_tsu,
            'promedio_ing' => $request->promedio_ing,
            'grupo_tsu' => isset($request->grupo_tsu) ? $request->grupo_tsu : null,
            'grupo_ing' => isset($request->grupo_ing) ? $request->grupo_ing : null
        ]);
                
                return redirect()->route('egel_ecg.index')->with('mensaje', 'El registro se ha actualizado exitosamente');
            
    }

    public function delete($idv)
    {
        DB::delete("DELETE FROM egel_ecg WHERE ide=$idv;"); 
        return back()->with('mensaje', 'Registro eliminado exitosamente');
    }

}
