<?php

namespace App\Http\Controllers\AsigGruposPTC;

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

class AsigGruposPTCController extends Controller
{
    /**
     * Vista para mostrar un listado de alumnos.
     */
    public function index(Request $request)
    {
        $condition1 = '';
        $condition2 = '';
        $grupo_id = '';
        if($request->grupo){
            $grupo_id = $request->grupo;
            $condition1 = "AND g.idgr=$request->grupo";
        }
        if($request->ptc){
            $condition2 = "AND g.user_id=$request->ptc";
        }
        $array = array();
        $grupos_ptc = DB::select("SELECT g.*,u.idu,CONCAT(u.nombre,' ',u.app,' ',u.apm) tutor FROM grupos g LEFT JOIN users u ON g.user_id=u.idu WHERE 1=1 $condition1 $condition2;");
        $grupos = DB::select("SELECT idgr, nombre FROM grupos;");
        $ptcs = DB::select("SELECT idu,nombre,app,apm FROM users WHERE idtu_tipos_usuarios=2;");
        function btn($idgr){
           
                $botones = "<a href=\"#eliminar-grupoptc\" class=\"btn btn-danger mt-1\" onclick=\"formSubmit('eliminar-grupoptc-$idgr')\"><i class='fas fa-power-off'></i></a>"
                         . "<a href= ". route('asig_grupos_ptc.edit', $idgr ) ." class=\"btn btn-primary mt-1\"> <i class='fa fa-user-alt'></i> </a>";
                
            return $botones;
        }
        foreach ($grupos_ptc as $grupo){

            array_push($array, array(
                'idgr'               => $grupo->idgr,
                'grupo'              => $grupo->nombre,
                'tutor'              => isset($grupo->tutor) ? $grupo->tutor : 'SIN ASIGNAR',
                'operaciones'        => btn($grupo->idgr)
            ));
        }
        $json = json_encode($array);
        return view("asig_grupos_ptc.index", compact("json","ptcs","grupos_ptc","grupos","grupo_id"));
    }

    /**
     * Vista que muestra un formulario para crear un usuario.
     */
    public function create()
    {
        $grupos = DB::select("SELECT idgr,nombre FROM grupos;");
        $ptcs = DB::select("SELECT idu,nombre,app,apm FROM users WHERE idtu_tipos_usuarios=2;");

        return view( 'asig_grupos_ptc.create', compact('grupos','ptcs'));

    }

    /**
     * Guardar un usuario.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'grupo' => 'required',
            'tutor'  => 'required',
        ]);
        DB::update("UPDATE grupos SET user_id=$request->tutor WHERE idgr=$request->grupo;"); 
        
        return redirect()->route('asig_grupos_ptc.index')->with('mensaje', 'El grupo se ha asignado exitosamente');
    }

    public function edit($idgr)
    {
        $grupos = DB::select("SELECT idgr,nombre FROM grupos;");
        $ptcs = DB::select("SELECT idu,nombre,app,apm FROM users WHERE idtu_tipos_usuarios=2;");
        $asignacion = DB::select("SELECT idgr,nombre,user_id FROM grupos WHERE idgr=$idgr;");
        
        return view('asig_grupos_ptc.edit', compact('asignacion','ptcs','grupos'));
    }
    /**
     * Actualiza un usuario.
     */
    public function update(Request $request, $idgr)
    {
        $validator = $request->validate([
            'grupo' => 'required',
            'tutor'  => 'required'
        ]);
        DB::update("UPDATE grupos SET user_id=$request->tutor WHERE idgr=$request->grupo;"); 
                
        return redirect()->route('asig_grupos_ptc.index')->with('mensaje', 'El registro se ha actualizado exitosamente');
            
    }

    public function delete($idgr)
    {   
        DB::update("UPDATE grupos SET user_id=NULL WHERE idgr=$idgr;"); 
        return back()->with('mensaje', 'Asignación eliminada exitosamente');
    }

}
