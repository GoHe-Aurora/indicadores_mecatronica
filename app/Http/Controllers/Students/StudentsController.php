<?php

namespace App\Http\Controllers\Students;

use File;
use App\Http\Controllers\Controller;
use App\Models\Student;
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

class StudentsController extends Controller
{
    /**
     * Vista para mostrar un listado de alumnos.
     */
    public function index(Request $request)
    {
        $query = '';
        $fechaIni = '';
        $fechaFin = '';
        $order = '';
        if($request->fechaIni){
             $query = "WHERE SUBSTRING(a.matricula, 3, 2) BETWEEN SUBSTRING($request->fechaIni, 3, 2) AND SUBSTRING($request->fechaFin, 3, 2)";
             $fechaIni = $request->fechaIni;
             $fechaFin = $request->fechaFin;
             $order = "ORDER BY SUBSTRING(a.matricula, 3, 2) DESC";
        }
        $array = array();
        $alumnos = DB::select("SELECT a.ida,a.nombre,a.app,a.apm,a.matricula,g.nombre grupo,e.nombre estatus,a.promedio_general promedio,a.activo,CASE
    WHEN a.estatus_id = 1 THEN CONCAT(c.cuatrimestre,'° Cuatrimestre')
    WHEN a.estatus_id = 3 THEN GROUP_CONCAT(CONCAT(m.nombre,' - ',m.descripcion) SEPARATOR ' | ')
    WHEN a.estatus_id = 4 THEN em.motivo 
    WHEN a.estatus_id = 5 THEN em.motivo
    ELSE '' END motivo FROM alumnos a LEFT JOIN grupos g ON a.grupo_id=g.idgr INNER JOIN estatus e ON a.estatus_id=e.ide LEFT JOIN cuatrimestres c ON g.cuatrimestre_id=c.idc LEFT JOIN estatus_motivos em ON em.idem=a.motivo LEFT JOIN materias_reprobadas mr ON a.ida=mr.alumno_id LEFT JOIN materias m ON m.idm=mr.materia_id $query GROUP BY a.ida $order;");
        function btn($ida,$activo){
            if($activo==1){
                $botones = "<a href=\"#desactivar-alumno-\" class=\"btn btn-danger mt-1\" onclick=\"formSubmit('desactivar-alumno-$ida')\"><i class='fas fa-power-off'></i></a>"
                         . "<a href= ". route('students.edit', $ida ) ." class=\"btn btn-primary mt-1\"> <i class='fa fa-user-alt'></i> </a>". "<a href= ". route('students.show', $ida ) ." class=\"btn btn-secondary mt-1\"> <i class='fa fa-eye'></i> </a>";
            }else{
                $botones = "<a href=\"#activar-alumno-\"  class=\"btn btn-success mt-1\" onclick=\"formSubmit('activar-alumno-$ida')\"><i class='fas fa-lightbulb'></i></a>";
            } 
                
            
            return $botones;
        }
        $years = range(2000, date("Y"));
        foreach ($alumnos as $alumno){

            array_push($array, array(
                'ida'                 => $alumno->ida,
                'nombre'              => $alumno->nombre,
                'app'                 => $alumno->app,
                'apm'                 => $alumno->apm,
                'matricula'           => $alumno->matricula,
                'grupo'               => $alumno->grupo,
                'estatus'             => $alumno->estatus,
                'motivo'              => $alumno->motivo,
                'promedio'            => $alumno->promedio,
                'activo'              => $alumno->activo==1 ? 'ACTIVO' : 'DESHABILITADO',
                'operaciones'         => btn($alumno->ida,$alumno->activo)
            ));
        }
        $json = json_encode($array);
        return view("students.index", compact("json","alumnos","years","fechaIni","fechaFin"));
    }

    /**
     * Vista que muestra un formulario para crear un usuario.
     */
    public function create()
    {
        $estatus = DB::select("SELECT ide,nombre FROM estatus;");
        $grupos = DB::select("SELECT idgr,nombre FROM grupos;");
        $materias = DB::select("SELECT idm,nombre,descripcion FROM materias;");
        $motivos_desercion = DB::select("SELECT idem,motivo FROM estatus_motivos WHERE estatus_id=4;");
        $motivos_reingreso = DB::select("SELECT idem,motivo FROM estatus_motivos WHERE estatus_id=5;");
        return view( 'students.create', compact('estatus','grupos','materias','motivos_desercion','motivos_reingreso'));

    }

    /**
     * Guardar un usuario.
     */
    public function store(Request $request)
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
        $student_id = Student::create([
            'nombre'   => $request->nombre,
            'app'      => $request->app,
            'apm'      => $request->apm,
            'matricula'    => $request->matricula,
            'grupo_id' => $request->estatus==1 ? $request->grupo : null,
            'promedio_general' => $request->promedio,
            'estatus_id' => $request->estatus,
            'motivo' => $motivo
        ])->ida;
         
        if($request->estatus==3){
            foreach ($request->materiasReprobadas as $mr){
           MateriasReprobadas::create([
            'materia_id'   => $mr,
            'alumno_id'      => $student_id    
        ]);   
           }
        }
        return redirect()->route('students.index')->with('mensaje', 'El alumno se ha creado exitosamente');
    }

    /**
     * Vista para mostrar un solo usuario.
     */
    public function show($idu)
    {
        $alumno = 1;
        return view('students.show', compact('alumno'));
            
    }

    /**
     * Vista que muestra un formulario para editar un usuario.
     */
    public function activar($id)
    {
        DB::update("UPDATE alumnos SET activo=1 WHERE ida=$id;");
        
        return back()->with('mensaje', 'El alumno se ha habilitado exitosamente');

    }
    public function by_group(Request $request)
    {
        $idgr = $request->grupo_id;
        $alumnos = DB::select("SELECT ida,nombre,app,apm FROM alumnos WHERE grupo_id=$idgr;");
        return $alumnos;

    }
    public function edit($ida)
    {
        $alumno = DB::select("SELECT a.ida,a.nombre,a.app,a.apm,a.matricula,g.nombre grupo,e.nombre estatus,a.estatus_id,a.grupo_id,a.promedio_general promedio,a.activo,CASE
    WHEN a.estatus_id = 1 THEN CONCAT(c.cuatrimestre,'° Cuatrimestre')
    WHEN a.estatus_id = 3 THEN GROUP_CONCAT(m.nombre SEPARATOR ' | ')
    WHEN a.estatus_id = 4 THEN em.motivo 
    WHEN a.estatus_id = 5 THEN em.motivo
    ELSE '' END motivo FROM alumnos a LEFT JOIN grupos g ON a.grupo_id=g.idgr INNER JOIN estatus e ON a.estatus_id=e.ide LEFT JOIN cuatrimestres c ON g.cuatrimestre_id=c.idc LEFT JOIN estatus_motivos em ON em.idem=a.motivo LEFT JOIN materias_reprobadas mr ON a.ida=mr.alumno_id LEFT JOIN materias m ON m.idm=mr.materia_id WHERE a.ida=$ida GROUP BY a.ida");  
        $estatus = DB::select("SELECT ide,nombre FROM estatus;");
        $grupos = DB::select("SELECT idgr,nombre FROM grupos;");
        $materias = DB::select("SELECT m.idm,m.nombre,mr.materia_id materiasrep FROM alumnos a LEFT JOIN materias_reprobadas mr ON a.ida=mr.alumno_id AND a.ida=$ida RIGHT JOIN materias m ON m.idm=mr.materia_id;");
        $motivos_desercion = DB::select("SELECT em.idem,em.motivo,a.motivo m FROM estatus_motivos em LEFT JOIN alumnos a ON a.motivo=em.idem AND a.ida=$ida WHERE em.estatus_id=4;");
        $motivos_reingreso = DB::select("SELECT em.idem,em.motivo,a.motivo m FROM estatus_motivos em LEFT JOIN alumnos a ON a.motivo=em.idem AND a.ida=$ida WHERE em.estatus_id=5;");
        return view( 'students.edit', compact('alumno','estatus','grupos','materias','motivos_desercion','motivos_reingreso'));
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
