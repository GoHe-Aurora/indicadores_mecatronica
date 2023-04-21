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
        $fecha = now()->format('Y-m-d');
        $gr = '';
        $array = array();
        if($request->fecha){
            $fecha = $request->fecha;
        }     
        if($request->grupo){
            $gr = $request->grupo;
            $condicion2 = "WHERE a.grupo_id=$gr";
        }
        $asesorias = DB::select("SELECT a.ida,a.nombre,a.app,a.apm,asa.observaciones,asa.tipo,SUBSTRING_INDEX(asa.fecha,' ',1) fecha,asa.idasal FROM alumnos a LEFT JOIN asesorias_alumnos asa ON a.ida=asa.idal AND SUBSTRING_INDEX(asa.fecha,' ',1)='$fecha' $condicion2;");
        $grupos = DB::select("SELECT idgr,nombre FROM grupos;");
        $id = DB::select("SELECT idae,evidencia FROM asesorias_evidencia WHERE SUBSTRING_INDEX(fecha,'-',2)=SUBSTRING_INDEX('$fecha','-',2)");
        function btn($idasal){
                    $botones = '';
                    /*$botones = "<a href=\"#eliminar-asesoria-\" class=\"btn btn-danger mt-1\" onclick=\"formSubmit('eliminar-asesoria-$idasal')\"><i class='fas fa-power-off'></i></a>";
                    "<a href= ". route('asesorias.edit', $idasal ) ." class=\"btn btn-primary mt-1\"> <i class='fa fa-user-alt'></i> </a>"; */
            return $botones;
        }
        function obs($obs,$id){ 
                
                $input = '<input type="text" value="'.$obs.'" data-id="'.$id.'" class="form-control obs"/><button data-id="'.$id.'" style="margin-top:5px;" class="btn btn-success save">Guardar</button>';
                
                   
            return $input;
        }
        
        function ck($c,$id,$idal){
            $ck = '<input type="checkbox" data-alumno="'.$idal.'" data-id="'.$id.'" '.($c==1 ? 'checked' : '').' class="ck" name="academica" id="academica" value="1">
<label for="academica">Académica</label>
<input form="myForm" type="checkbox" data-alumno="'.$idal.'" data-id="'.$id.'" '.($c==2 ? 'checked' : '').' class="ck" name="nivelacion" id="nivelacion"  value="2"> 
<label for="nivelacion">Nivelación</label>';
            return $ck;
        }
        foreach ($asesorias as $asesoria){

            array_push($array, array(
                'idas'                => $asesoria->ida,
                'nombre'              => $asesoria->nombre,
                'app'                 => $asesoria->app,
                'apm'                 => $asesoria->apm,
                'observaciones'       => obs($asesoria->observaciones,$asesoria->idasal),
                'tipo'                => ck($asesoria->tipo,$asesoria->idasal,$asesoria->ida),
                'operaciones'         => btn($asesoria->idasal)
            ));
        }
        $json = json_encode($array);
        return view("asesorias.index", compact("json","asesorias","grupos","fecha","gr","id"));
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
        if(!empty($request->id)){
           DB::update("UPDATE asesorias_alumnos SET tipo=$request->tipo WHERE idasal=$request->id;"); 
        }else{
           DB::insert("INSERT INTO asesorias_alumnos (idal,tipo,fecha) VALUES($request->idal,$request->tipo,'$request->fecha');");
        }
        return  json_encode('El alumno se ha actualizado exitosamente');         
    }
    public function update_obs(Request $request)
    { 
        DB::update("UPDATE asesorias_alumnos SET observaciones='$request->obs' WHERE idasal=$request->id;");
        return json_encode('El alumno se ha actualizado exitosamente');       
    }
    public function update_asesoria(Request $request)
    {
        $name = '';
        $R = DB::select("SELECT idasal FROM asesorias_alumnos WHERE idal=$request->idal AND fecha='$request->fecha';");
        if(!empty($request->tipo)){
       if(isset($R[0])){
        $Q = DB::select("SELECT evidencia FROM asesorias_evidencia WHERE fecha='$request->fecha';");
        $idasal = $R[0]->idasal;
        DB::update("UPDATE asesorias_alumnos SET idal=$request->idal,tipo=$request->tipo,fecha='$request->fecha' WHERE idasal=$idasal");
         $t= 'actualizado';  
        }else{
        DB::insert("INSERT INTO asesorias_alumnos(idal,tipo,fecha) VALUES($request->idal,$request->tipo,'$request->fecha')");
        $id = DB::getPdo()->lastInsertId();;
        }
        }else if(isset($R[0])){
           
           $idasal = $R[0]->idasal;
           DB::delete("DELETE FROM asesorias_alumnos WHERE idasal=$idasal;");
           
        }      
         $t= 'agregado';
       return json_encode(array('response'=>'La asesoria se ha '.$t.' exitosamente'));       
    }
    public function update_evidencia(Request $request)
    {
        if(isset($request->file)){
        $Q = DB::select("SELECT evidencia FROM asesorias_evidencia WHERE fecha='$request->fecha';");
           if(isset($Q[0])){
              Storage::disk('public')->delete('evidencia_asesorias/'.$Q[0]->evidencia);
              Storage::disk('evidencia_asesorias')->delete($Q[0]->evidencia);
           }
        $file = $request->file;
        $name = time() . '.' . $file->getClientOriginalExtension();
        //$originalName = $request->archivo->getClientOriginalName();
        Storage::disk('evidencia_asesorias')->put($name, File::get($file));
        Storage::disk('public')->put('evidencia_asesorias/' . $name, File::get($file));
        if(isset($Q[0])){
           DB::update("UPDATE asesorias_evidencia SET evidencia='$name' WHERE fecha='$request->fecha'"); 
            $t= 'actualizado';
        }else{
            DB::insert("INSERT INTO asesorias_evidencia(evidencia,fecha) VALUES('$name','$request->fecha')"); 
            $t= 'agregado';
        }
        }   
        return json_encode(array('response'=>'La evidencia se ha '.$t.' exitosamente','nfile'=>$name));
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
