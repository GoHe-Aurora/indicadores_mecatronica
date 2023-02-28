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
        $tutorias = DB::select("SELECT t.idt,m.nombre,m.app,m.apm,t.tipo,t.fecha,t.archivo,t.archivo_nombre FROM maestros m INNER JOIN tutorias t ON m.idm = t.maestro_id;");
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
                'tipo'                => $tutoria->tipo==1 ? 'Individual' :  'Grupal',
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
        $maestros = DB::select("SELECT idm,nombre,app,apm FROM maestros;");
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
        $tutoria = DB::select("SELECT t.idt,t.maestro_id,t.tipo,t.fecha,t.archivo,t.archivo_nombre,t.grupo_id,t.alumno_id FROM maestros m INNER JOIN tutorias t ON m.idm = t.maestro_id WHERE t.idt=$idt;");  
        $maestros = DB::select("SELECT idm,nombre,app,apm FROM maestros;"); 
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
                Storage::disk('public')->put('evidencia_tutorias/' . $name, File::get($file)); 
            }
            
            Tutoria::where('idt',$idt)->update([
                'maestro_id'   => $request->maestro,
                'tipo'      => $request->tipo,
                'grupo_id'      => $request->grupo,
                'alumno_id'      => $request->tipo==1 ? $request->alumno : null,
                'fecha'      => date('Y-m-d H:i:s', strtotime($request->fecha)),
                'archivo_nombre'    => isset($request->archivo) ? $originalName : null,
                'archivo'    => isset($request->archivo) ? $name : null,
            ]);   
                return redirect()->route('tutorias.index')->with('mensaje', 'Tutoría actualizada exitosamente');
            
    }

    public function delete($idt)
    {
        $archivo = DB::select("SELECT archivo FROM tutorias WHERE idt=$idt;");
        Storage::disk('public')->delete('evidencia_tutorias/'.$archivo[0]->archivo);
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
