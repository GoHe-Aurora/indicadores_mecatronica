<?php

namespace App\Http\Controllers\Tutorias;

use File;
use DateTime;
use App\Http\Controllers\Controller;
use App\Models\Tutoria;
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

class TutoriasController extends Controller
{
    /**
     * Vista para mostrar un listado de alumnos.
     */
    public function index()
    {
        
        $array = array();
        $tipo = auth()->user()->idtu_tipos_usuarios;
        if($tipo==1){
          $condition = '';
        }else{
          $idu = auth()->user()->idu;  
          $condition = "WHERE t.maestro_id=$idu";   
        }
        $tutorias = DB::select("SELECT t.idt,u.nombre,u.app,u.apm,t.tipo,g.nombre grupo,CONCAT(a.nombre,' ',a.app,' ',a.apm) alumno,t.fecha,t.archivo,t.archivo_nombre FROM users u INNER JOIN tutorias t ON u.idu = t.maestro_id LEFT JOIN grupos g ON t.grupo_id=g.idgr LEFT JOIN alumnos a ON t.alumno_id=a.ida $condition;");
        function archivo($archivo,$name){
            $link = "<a href='./storage/evidencia_tutorias/".$archivo."' target='_blank'>".$name."</a>";
            return $link;
        }
        function btn($idt){
        
                $botones = "<a href=\"#eliminar-tutoria-\" class=\"btn btn-danger mt-1\" onclick=\"formSubmit('eliminar-tutoria-$idt')\"><i class='fas fa-power-off'></i></a>"
                         . "<a href= ". route('tutorias.edit', $idt ) ." class=\"btn btn-primary mt-1\"> <i class='fa fa-user-alt'></i> </a>";
                
            return $botones;
        }
        foreach ($tutorias as $tutoria){

            array_push($array, array(
                'idt'                 => $tutoria->idt,
                'nombre'              => $tutoria->nombre,
                'app'                 => $tutoria->app,
                'apm'                 => $tutoria->apm,
                'tipo'                => $tutoria->tipo==1 ? 'Individual ('.$tutoria->grupo.'-'.$tutoria->alumno.')' :  'Grupal ('.$tutoria->grupo.')',
                'fecha'               => $tutoria->fecha,
                'archivo'             => archivo($tutoria->archivo,$tutoria->archivo_nombre),
                'operaciones'         => btn($tutoria->idt),
            ));
        }
        $json = json_encode($array);
        return view("tutorias.index", compact("json","tutorias"));
    }

    /**
     * Vista que muestra un formulario para crear un usuario.
     */
    public function create()
    {
        $tipo = auth()->user()->idtu_tipos_usuarios;
        if($tipo==1){
          $condition = '';
        }else{
          $idu = auth()->user()->idu;  
          $condition = "AND idu=$idu";   
        }
        $maestros = DB::select("SELECT idu,nombre,app,apm FROM users WHERE (idtu_tipos_usuarios=2 OR idtu_tipos_usuarios=3) $condition;");
        $grupos = DB::select("SELECT idgr,nombre FROM grupos;"); 
        return view( 'tutorias.create', compact('maestros','grupos'));

    }

    /**
     * Guardar un usuario.
     */
    public function store(Request $request)
    {
        if($request->tipo==1){
            $validator = $request->validate([
            'maestro' => 'required',
            'tipo'    => 'required',
            'fecha'   => 'required',
            'grupo'   => 'required',
            'alumno'   => 'required'
        ]);
        }else if($request->tipo==2){
            $validator = $request->validate([
            'maestro' => 'required',
            'tipo'    => 'required',
            'fecha'   => 'required',
            'grupo'   => 'required'
        ]);
        }
        if(isset($request->archivo)){
        $file = $request->archivo;
        $name = time() . '.' . $file->getClientOriginalExtension();
        $originalName = $request->archivo->getClientOriginalName();
        Storage::disk('evidencia_tutorias')->put($name, File::get($file));
        Storage::disk('public')->put('evidencia_tutorias/' . $name, File::get($file));
        }
        Tutoria::create([
            'maestro_id'   => $request->maestro,
            'tipo'      => $request->tipo,
            'grupo_id'      => $request->grupo,
            'alumno_id'      => isset($request->alumno) ? $request->alumno : null,
            'fecha'      => date('Y-m-d H:i:s', strtotime($request->fecha)),
            'archivo_nombre'    => isset($request->archivo) ? $originalName : null,
            'archivo'    => isset($request->archivo) ? $name : null,
        ]);

        return redirect()->route('tutorias.index')->with('mensaje', 'Tutoría creada exitosamente');
    }

    /**
     * Vista para mostrar un solo usuario.
     */
    public function show($idt)
    {
        $tutoria = 1;
        return view('tutorias.show', compact('tutoria'));
            
    }
    
    public function edit($idt)
    {
        $tutoria = DB::select("SELECT t.idt,t.maestro_id,t.tipo,t.fecha,t.archivo,t.archivo_nombre,t.grupo_id,t.alumno_id FROM users u INNER JOIN tutorias t ON u.idu = t.maestro_id AND (u.idtu_tipos_usuarios=2 OR u.idtu_tipos_usuarios=3) WHERE t.idt=$idt;");   
        $maestros = DB::select("SELECT idu,nombre,app,apm FROM users WHERE idtu_tipos_usuarios=2 OR idtu_tipos_usuarios=3;"); 
        $grupos = DB::select("SELECT idgr,nombre FROM grupos;");  
            return view( 'tutorias.edit', compact('tutoria','maestros','grupos'));
    }

    /**
     * Actualiza un usuario.
     */
    public function update(Request $request, $idt)
    {
        if($request->tipo==1){
            $validator = $request->validate([
            'maestro' => 'required',
            'tipo'    => 'required',
            'fecha'   => 'required',
            'grupo'   => 'required',
            'alumno'   => 'required'
        ]);
        }else if($request->tipo==2){
            $validator = $request->validate([
            'maestro' => 'required',
            'tipo'    => 'required',
            'fecha'   => 'required',
            'grupo'   => 'required'
        ]);
        }    
            if(isset($request->archivo)){
                $file = $request->archivo;
                $name = time() . '.' . $file->getClientOriginalExtension();
                $originalName = $request->archivo->getClientOriginalName(); 
                Storage::disk('public')->delete('evidencia_tutorias/'.$request->old);
                Storage::disk('evidencia_tutorias')->delete($request->old);
                Storage::disk('evidencia_tutorias')->put($name, File::get($file));
                Storage::disk('public')->put('evidencia_tutorias/' . $name, File::get($file)); 
            }
            
            Tutoria::where('idt',$idt)->update([
                'maestro_id'   => $request->maestro,
                'tipo'      => $request->tipo,
                'grupo_id'      => $request->grupo,
                'alumno_id'      => $request->tipo==1 ? $request->alumno : null,
                'fecha'      => date('Y-m-d H:i:s', strtotime($request->fecha)),
                'archivo_nombre'    => isset($request->archivo) ? $originalName : $request->nombre_old,
                'archivo'    => isset($request->archivo) ? $name : $request->old,
            ]);   
                return redirect()->route('tutorias.index')->with('mensaje', 'Tutoría actualizada exitosamente');
            
    }

    public function delete($idt)
    {
        $archivo = DB::select("SELECT archivo FROM tutorias WHERE idt=$idt;");
        Storage::disk('public')->delete('evidencia_tutorias/'.$archivo[0]->archivo);
        Storage::disk('evidencia_tutorias')->delete($archivo[0]->archivo);
        DB::delete("DELETE FROM tutorias WHERE idt=$idt;"); 
        return back()->with('mensaje', 'Tutoría eliminada exitosamente');
    }
    /**
     * Elimina una tutoria.
     */
    public function destroy($idt)
    {
        return back()->with('mensaje', 'Tutoría eliminada exitosamente');
    }
}
