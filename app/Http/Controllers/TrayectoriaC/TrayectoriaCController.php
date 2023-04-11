<?php

namespace App\Http\Controllers\TrayectoriaC;

use File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TiposUsuarios;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class TrayectoriaCController extends Controller
{
    /**
     * Vista para mostrar un listado de alumnos.
     */
    public function index(Request $request)
    {
        $grupo_id = '';
        if($request->grupo){
            $grupo_id = $request->grupo;
        }
        $array = array();
        $alumnos = DB::select("SELECT tc.idtc,tc.actitud,tc.conocimiento,tc.desempeno,tc.calificacion,a.nombre,a.app,a.apm,mu.unidad FROM trayectoria_cuatrimestral tc INNER JOIN alumnos a ON tc.alumno_id=a.ida INNER JOIN materias_unidad mu ON tc.unidad_id=mu.idmu WHERE mu.materia_id=1 AND a.grupo_id=1;");
        $grupos = DB::select("SELECT idgr,nombre,descripcion FROM grupos;");
        $materias = DB::select("SELECT idm,nombre FROM materias;");
        function btn($idtc){
           
                $botones = "<a href=\"#eliminar-tc\" class=\"btn btn-danger mt-1\" onclick=\"formSubmit('eliminar-tc-$idtc')\"><i class='fas fa-power-off'></i></a>"
                         . "<a href= ". route('trayectoriac.edit', $idtc ) ." class=\"btn btn-primary mt-1\"> <i class='fa fa-user-alt'></i> </a>";
                
            return $botones;
        }
        foreach ($alumnos as $alumno){

            array_push($array, array(
                'idtc'                => $alumno->idtc,
                'nombre'              => $alumno->nombre,
                'app'                 => $alumno->app,
                'apm'                 => $alumno->apm,
                'unidad'              => $alumno->unidad,
                'actitud'             => $alumno->actitud,
                'conocimiento'        => $alumno->conocimiento,
                'desempeno'           => $alumno->desempeno,
                'operaciones'         => btn($alumno->idtc)
            ));
        }
        $json = json_encode($array);
        return view("trayectoriac.index", compact("json","alumnos","grupos","materias"));
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
            'promedio'  => 'required|numeric',
            'grupo' => 'required', 
        ]);
        
        ValoracionAE::create([
            'alumno_id' => $request->alumno,
            'promedio' => $request->promedio,
            'grupo_id' => $request->grupo,
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
