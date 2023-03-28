<?php

namespace App\Http\Controllers\Asesorias;

use File;
use App\Http\Controllers\Controller;
use App\Models\Asesoria;
use App\Models\materiasReprobadas;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TiposUsuarios;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AsesoriasController extends Controller
{
    /**
     * Vista para mostrar un listado de alumnos.
     */
    public function index(Request $request)
    { 
        $condicion1 = '';
        $condicion2 = '';
        $fecha = '';
        $gr = '';
        $array = array();
        if($request->fecha){
            $fecha = $request->fecha;
            $condicion1 = "AND SUBSTRING_INDEX(asa.fecha,' ',1)='$fecha'";
        }     
        if($request->grupo){
            $gr = $request->grupo;
            $condicion2 = "WHERE a.grupo_id=$gr";
        }
        $asesorias = DB::select("SELECT a.ida,a.nombre,a.app,a.apm,asa.observaciones,asa.tipo,SUBSTRING_INDEX(asa.fecha,' ',1) fecha,asa.idasal FROM alumnos a LEFT JOIN asesorias_alumnos asa ON a.ida=asa.idal $condicion1 $condicion2;");
        $grupos = DB::select("SELECT idgr,nombre FROM grupos;");

        function btn($idasal,$fecha){
                    $botones = '';
                if($fecha!=''){
                    $botones = "<a href=\"#eliminar-asesoria-\" class=\"btn btn-danger mt-1\" onclick=\"formSubmit('eliminar-asesoria-$idasal')\"><i class='fas fa-power-off'></i></a>";
                    "<a href= ". route('asesorias.edit', $idasal ) ." class=\"btn btn-primary mt-1\"> <i class='fa fa-user-alt'></i> </a>"; 
                }
                      
            return $botones;
        }
        function obs($obs,$id,$fecha){
                $disabled = '';
                if($fecha==''){
                   $obs = '';
                   
                }
                if($id==''){
                    $disabled = 'disabled';
                }
                $input = '<input type="text" value="'.$obs.'" data-id="'.$id.'" class="form-control obs"/><button '.$disabled.' data-id="'.$id.'" style="margin-top:5px;" class="btn btn-success save">Guardar</button>';
                
                   
            return $input;
        }
        
        function ck($c,$id){
            $ck = '<input type="checkbox"  data-id="'.$id.'" '.($c==1 ? 'checked' : '').' class="ck" name="academica" id="academica" value="1">
<label for="academica">Académica</label>
<input form="myForm" type="checkbox" data-id="'.$id.'" '.($c==2 ? 'checked' : '').' class="ck" name="nivelacion" id="nivelacion"  value="2"> 
<label for="nivelacion">Nivelación</label>';
            return $ck;
        }
        foreach ($asesorias as $asesoria){

            array_push($array, array(
                'idas'                => $asesoria->ida,
                'nombre'              => $asesoria->nombre,
                'app'                 => $asesoria->app,
                'apm'                 => $asesoria->apm,
                'observaciones'       => obs($asesoria->observaciones,isset($asesoria->idasal) ? $asesoria->idasal : '',isset($request->fecha) ? 1 : ''),
                'tipo'                => ck(isset($request->fecha) ? $asesoria->tipo : '',isset($asesoria->idasal) ? $asesoria->idasal : ''),
                'operaciones'         => btn(isset($asesoria->idasal) ? $asesoria->idasal : '',isset($request->fecha) ? 1 : '')
            ));
        }
        $json = json_encode($array);
        return view("asesorias.index", compact("json","asesorias","grupos","fecha","gr"));
    }

    /**
     * Vista que muestra un formulario para crear un usuario.
     */
    public function create()
    {
        $maestros = DB::select("SELECT idm,nombre,app,apm FROM maestros;");
        $grupos = DB::select("SELECT idgr,nombre FROM grupos;");
        return view( 'asesorias.create', compact('maestros','grupos'));

    }

    /**
     * Guardar un usuario.
     */
    public function store(Request $request)
    {

        $validator = $request->validate([

            'maestro' => 'required',
            'tipo'    => 'required',
            'fecha'   => 'required',
            
        ]);
         if(isset($request->archivo)){
        $file = $request->archivo;
        $name = time() . '.' . $file->getClientOriginalExtension();
        //$originalName = $request->archivo->getClientOriginalName();
        Storage::disk('evidencia_asesorias')->put($name, File::get($file));
        Storage::disk('public')->put('evidencia_asesorias/' . $name, File::get($file));
        }
        Asesoria::create([
            'titulo'   => isset($request->titulo) ? $request->titulo : null,
            'descripcion'      => isset($request->descripcion) ? $request->descripcion : null,
            'maestro_id'      => $request->maestro,
            'fecha'    => $request->fecha,
            'archivo' => isset($request->archivo) ? $name : null
        ]);
         
        return redirect()->route('asesorias.index')->with('mensaje', 'Asesoría creada exitosamente');
    }

    /**
     * Vista para mostrar un solo usuario.
     */
    public function update_tipo(Request $request)
    { 
        $id = $request->id;
        $tipo = $request->tipo;
    
            DB::update("UPDATE asesorias_alumnos SET tipo=$tipo WHERE idasal=$id;");
        
        return  json_encode('El alumno se ha actualizado exitosamente');
            
    }
    public function update_obs(Request $request)
    { 
        $id = $request->id; 
        $obs = $request->obs;
        DB::update("UPDATE asesorias_alumnos SET observaciones='$obs' WHERE idasal=$id;");
        return  json_encode('El alumno se ha actualizado exitosamente');
            
    }
    

    /**
     * Vista que muestra un formulario para editar un usuario.
     */
    public function activar($id)
    {
        DB::update("UPDATE alumnos SET activo=1 WHERE ida=$id;");
        
        return back()->with('mensaje', 'El alumno se ha habilitado exitosamente');

    }
    public function edit($ida)
    {
        $asesoria = ("SELECT a.ida,a.nombre,a.app,a.apm,asa.observaciones,asa.tipo,SUBSTRING_INDEX(ase.fecha,' ',1) fecha FROM alumnos a INNER JOIN asesorias_alumnos asa ON a.ida=asa.idal INNER JOIN asesorias ase ON ase.idas=asa.idas WHERE ase.idas=$ida;");  
        return view( 'asesorias.edit', compact('asesoria'));
    }

    /**
     * Actualiza un usuario.
     */
    public function update(Request $request, $ida)
    {
        
        $arraymr = array();

        if($request->estatus==1){
            $campo  = 'grupo';   
            }else if($request->estatus==2){
            $campo  = 'estatus';
            }else if($request->estatus==3){
            $campo  = 'materiasReprobadas';
            }else if($request->estatus==4){
            $campo  = 'motivoDesercion';
            }else if($request->estatus==5){
            $campo  = 'motivoReingreso';
            }

        $validator = $request->validate([
            'nombre' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
            'app'    => 'required|regex:/^[\pL\s\-]+$/u|max:255',
            'apm'   => 'required|regex:/^[\pL\s\-]+$/u|max:255',
            'matricula' => 'numeric|required|min:111111111|max:99999999999',
            'estatus'  => 'required',
            'promedio'  => 'required|numeric',
            $campo => 'required',
            
        ]);
        
        if($request->estatus==4){
            $motivo = $request->motivoDesercion;
        }else if($request->estatus==5){
            $motivo = $request->motivoReingreso;
        }else{
            $motivo = null;
        }
        Student::where('ida',$ida)->update([
            'nombre'   => $request->nombre,
            'app'      => $request->app,
            'apm'      => $request->apm,
            'matricula'    => $request->matricula,
            'grupo_id' => $request->estatus==1 ? $request->grupo : null,
            'promedio_general' => $request->promedio,
            'estatus_id' => $request->estatus,
            'motivo' => $motivo
        ]);
         
        if($request->estatus==3){
            DB::delete("DELETE FROM materias_reprobadas WHERE alumno_id=$ida;");
            foreach ($request->materiasReprobadas as $mr){
           MateriasReprobadas::create([
            'materia_id'   => $mr,
            'alumno_id'      => $ida    
        ]);   
           }
        }

                
                return redirect()->route('students.index')->with('mensaje', 'El alumno se ha actualizado exitosamente');
            
    }

    public function desactivar($id)
    {
        DB::update("UPDATE alumnos SET activo=0 WHERE ida=$id;");
        return back()->with('mensaje', 'El alumno se ha deshabilitado exitosamente');
    }

    /**
     * Elimina un usuario.
     */
    public function destroy($ida)
    {
       
        return back()->with('mensaje', 'El alumno se ha eliminado exitosamente');
    }
}
