<?php

namespace App\Http\Controllers\TrayectoriaC;

use File;
use PDF;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TiposUsuarios;
use App\Models\TrayectoriaC;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Exports\TrayectoriaCExport;
use Maatwebsite\Excel\Facades\Excel;

class TrayectoriaCController extends Controller
{
    /**
     * Vista para mostrar un listado de alumnos.
     */
    public function index(Request $request)
    {
        $grupo_id = 1;
        $mat = 1;
        if($request->grupo){
            $grupo_id = $request->grupo;
        }
        if($request->materia){
            $mat = $request->materia;
        }
        $array = array();
        $ACTITUD= '';
        $CONOCIMIENTO= '';
        $DESEMPENO = '';
        $unidades = DB::select("SELECT unidades FROM materias WHERE idm=$mat;");
        $length = $unidades[0]->unidades;
        $c = DB::select("SELECT tc.idtc,tc.actitud,tc.conocimiento,tc.desempeno,tc.calificacion,tc.calificacion_acta,a.nombre,a.app,a.apm,tc.unidad FROM trayectoria_cuatrimestral tc INNER JOIN alumnos a ON tc.alumno_id=a.ida WHERE tc.materia_id=$mat AND a.grupo_id=$grupo_id GROUP BY a.ida;");
        if(isset($c[0])){
        for ($i = 1; $i <= $length; $i++) {
            if($i==$length){
                $coma = '';
            }else{
                $coma = ',';
            }
            $ACTITUD .= "MAX(CASE WHEN tc.unidad=".$i." THEN CONCAT('Responsabilidad: ',IF(tc.responsabilidad IS NULL,'n/a',tc.responsabilidad),'\n\nColaborativo: ',IF(tc.colaborativo IS NULL,'n/a',tc.colaborativo),'\n\nRelaciones Interpersonales: ',IF(tc.relaciones_i IS NULL,'n/a',tc.relaciones_i),'\n\nCreatividad: ',IF(tc.creatividad IS NULL,'n/a',tc.creatividad)) END) AS actitud".$i.$coma;
            $CONOCIMIENTO .= "MAX(CASE WHEN tc.unidad=".$i." THEN CONCAT('Manejo de Información: ',IF(tc.manejo_inf IS NULL,'n/a',tc.manejo_inf),'\n\nMarco Teórico y Conceptual : ',IF(tc.marco_tc IS NULL,'n/a',tc.marco_tc)) END) AS conocimiento".$i.$coma;
            $DESEMPENO .= "MAX(CASE WHEN tc.unidad=".$i." THEN CONCAT('Prácticas: ',IF(tc.practicas IS NULL,'n/a',tc.practicas),'\n\nEstudios de Caso: ',IF(tc.estudios_caso IS NULL,'n/a',tc.estudios_caso),'\n\nProyecto: ',IF(tc.proyecto IS NULL,'n/a',tc.proyecto),'\n\nEjercicios: ',IF(tc.ejercicios IS NULL,'n/a',tc.ejercicios),'\n\Ensayo: ',IF(tc.ensayo IS NULL,'n/a',tc.ensayo)) END) AS desempeno".$i.$coma;
        }
        }else{
            $ACTITUD = "tc.idtc";
            $CONOCIMIENTO = "tc.idtc";
            $DESEMPENO = "tc.idtc";
        }
        $alumnos = DB::select("SELECT tc.idtc,tc.alumno_id,tc.actitud,tc.conocimiento,tc.desempeno,tc.calificacion,tc.calificacion_acta,a.nombre,a.app,a.apm,tc.unidad,$ACTITUD,$CONOCIMIENTO,$DESEMPENO FROM trayectoria_cuatrimestral tc INNER JOIN alumnos a ON tc.alumno_id=a.ida WHERE tc.materia_id=$mat GROUP BY a.ida;");
        $grupos = DB::select("SELECT idgr,nombre,descripcion FROM grupos;");
        $materias = DB::select("SELECT idm,nombre,descripcion FROM materias WHERE unidades IS NOT NULL;");
        function btn($idtc){
           
                $botones = "<a href=\"#eliminar-tc\" class=\"btn btn-danger mt-1\" onclick=\"formSubmit('eliminar-tc-$idtc')\"><i class='fas fa-power-off'></i></a>"
                         . "<a href= ". route('trayectoriac.edit', $idtc ) ." class=\"btn btn-primary mt-1\"> <i class='fa fa-user-alt'></i> </a>";
                
            return $botones;
        }
        $arr = array();
        $array_final = array();
        $filas = array();
        foreach ($alumnos as $k=>$alumno){ 
            //$prueba = 'prueba';
          array_push($array, [
                'idtc'                => $alumno->idtc,
                'nombre'              => $alumno->nombre,
                'app'                 => $alumno->app,
                'apm'                 => $alumno->apm,
                'operaciones'         => btn($alumno->idtc),
                'calificacion'        => $alumno->calificacion, 
                'calificacion_acta'   => $alumno->calificacion_acta,

            ]);
        }
                   foreach ($alumnos as $k=>$a){
                    for ($i = 1; $i <= $length; $i++) {
                   $actitud = 'actitud'.$i;
                   $arr['actitud'.$i] = isset($a->$actitud)&&$a->$actitud !=null ? $a->$actitud : 'n/a';
                   $conocimiento = 'conocimiento'.$i;
                   $arr['conocimiento'.$i] = isset($a->$conocimiento)&&$a->$conocimiento!=null ? $a->$conocimiento : 'n/a';
                   $desempeno = 'desempeno'.$i;
                   $arr['desempeno'.$i] = isset($a->$desempeno)&&$a->$desempeno!=null ? $a->$desempeno : 'n/a';
                      $filas[$k] = array_merge($array[$k],$arr);
                  }
            }
        $json = json_encode($filas);
        return view("trayectoriac.index", compact("length","mat","grupo_id","alumnos","grupos","materias","json"));
    }

    /**
     * Vista que muestra un formulario para crear un usuario.
     */
    public function create()
    {
        $grupos = DB::select("SELECT idgr,nombre FROM grupos;");
        $materias = DB::select("SELECT idm,nombre,descripcion FROM materias WHERE unidades IS NOT NULL;");
        return view( 'trayectoriac.create', compact('grupos','materias'));
    }
    /**
     * Guardar un usuario.
     */
    public function store(Request $request)
    {    
        $validator = $request->validate([
            'alumno' => 'required',
            'grupo'  => 'required',
            'materia' => 'required', 
            'unidad' => 'required', 
        ]);
        TrayectoriaC::create([
            'alumno_id' => $request->alumno,
            'materia_id' => $request->materia,
            'unidad' => $request->unidad,
            'actitud' => $request->actitud,
            'responsabilidad' => $request->responsabilidad,
            'colaborativo' => $request->colaborativo,
            'relaciones_i' => $request->relaciones_i,
            'creatividad' => $request->creatividad,
            'conocimiento' => $request->conocimiento,
            'manejo_inf' => $request->manejo_inf,
            'marco_tc' => $request->marco_tc,
            'desempeno' => $request->desempeno,
            'practicas' => $request->practicas,
            'estudios_caso' => $request->estudios_caso,
            'proyecto' => $request->proyecto,
            'ejercicios' => $request->ejercicios,
            'ensayo' => $request->ensayo,
            'calificacion' => $request->calificacion,
            'calificacion_acta' => $request->calificacion_acta
        ]);
         
        return redirect()->route('trayectoriac.index')->with('mensaje', 'El registro se ha guardado exitosamente');
    }

    public function edit($idtc)
    {
        $tc = DB::select(" SELECT tc.*,a.grupo_id FROM trayectoria_cuatrimestral tc INNER JOIN alumnos a ON a.ida=tc.alumno_id WHERE tc.idtc=$idtc;");  
        $grupos = DB::select("SELECT idgr,nombre FROM grupos;");
        $materias = DB::select("SELECT idm,nombre,descripcion FROM materias WHERE unidades IS NOT NULL;");
        
        return view('trayectoriac.edit', compact('tc','grupos','materias'));
    }
    public function atributos(Request $request)
    {
      $atributos = DB::select("SELECT * FROM trayectoria_cuatrimestral WHERE unidad=$request->unidad AND alumno_id=$request->ida;");   
     return json_encode($atributos);
    }
    public function unidades(Request $request)
    {
        $array = array(); 
        $unidades = DB::select("SELECT unidades FROM materias WHERE idm=$request->materia;");
        $unidades_asig = DB::select("SELECT unidad FROM trayectoria_cuatrimestral WHERE materia_id=$request->materia;");
        if($request->opc==1){
        for ($i = 1; $i <= $unidades[0]->unidades; $i++) {
            if(!isset($unidades_asig[$i-1]) || $i==$request->unidad){
               array_push($array,$i);  
            }     
        }
        }else if($request->opc==2){
        for ($i = 1; $i <= $unidades[0]->unidades; $i++) {
               array_push($array,$i);
        }
        }
        return json_encode($array);
    }
    /**
     * Actualiza un usuario.
     */
    public function update(Request $request, $ida)
    {
        $validator = $request->validate([ 
            'alumno' => 'required',
            'materia' => 'required', 
            'unidad' => 'required'
        ]);
        $existe = DB::select("SELECT idtc FROM trayectoria_cuatrimestral WHERE alumno_id=$ida AND unidad=$request->unidad;");
        if(isset($existe[0]->idtc)){
       TrayectoriaC::where('idtc',$existe[0]->idtc)->update([
            'unidad' => $request->unidad,
            'actitud' => $request->actitud,
            'responsabilidad' => $request->responsabilidad,
            'colaborativo' => $request->colaborativo,
            'relaciones_i' => $request->relaciones_i,
            'creatividad' => $request->creatividad,
            'conocimiento' => $request->conocimiento,
            'manejo_inf' => $request->manejo_inf,
            'marco_tc' => $request->marco_tc,
            'desempeno' => $request->desempeno,
            'practicas' => $request->practicas,
            'estudios_caso' => $request->estudios_caso,
            'proyecto' => $request->proyecto,
            'ejercicios' => $request->ejercicios,
            'ensayo' => $request->ensayo,
            'calificacion' => $request->calificacion,
            'calificacion_acta' => $request->calificacion_acta
        ]); 
        $msj = 'actualizado';
        }else{
            TrayectoriaC::create([
            'alumno_id' => $request->alumno,
            'materia_id' => $request->materia,
            'unidad' => $request->unidad,
            'actitud' => $request->actitud,
            'responsabilidad' => $request->responsabilidad,
            'colaborativo' => $request->colaborativo,
            'relaciones_i' => $request->relaciones_i,
            'creatividad' => $request->creatividad,
            'conocimiento' => $request->conocimiento,
            'manejo_inf' => $request->manejo_inf,
            'marco_tc' => $request->marco_tc,
            'desempeno' => $request->desempeno,
            'practicas' => $request->practicas,
            'estudios_caso' => $request->estudios_caso,
            'proyecto' => $request->proyecto,
            'ejercicios' => $request->ejercicios,
            'ensayo' => $request->ensayo,
            'calificacion' => $request->calificacion,
            'calificacion_acta' => $request->calificacion_acta
        ]);
             $msj = 'creado';
       }   
        return redirect()->route('trayectoriac.index')->with('mensaje', 'El registro se ha '.$msj.' exitosamente');    
    }
    public function reporte($idm)
    {
        $array = array();
        $ACTITUD= '';
        $CONOCIMIENTO= '';
        $DESEMPENO = '';
        $CALIFICACION = '';
        $unidades = DB::select("SELECT unidades FROM materias WHERE idm=$idm;");
        $length = $unidades[0]->unidades;
        $c = DB::select("SELECT tc.idtc,tc.actitud,tc.conocimiento,tc.desempeno,tc.calificacion,a.nombre,a.app,a.apm,tc.unidad FROM trayectoria_cuatrimestral tc INNER JOIN alumnos a ON tc.alumno_id=a.ida WHERE tc.materia_id=$idm AND a.grupo_id=$idm;");
        if(isset($c[0])){
        for ($i = 1; $i <= $length; $i++) {
            if($i==$length){
                $coma = '';
            }else{
                $coma = ',';
            }
            $ACTITUD .= "SUM(CASE WHEN tc.unidad=".$i." THEN tc.actitud END) AS actitud".$i.$coma;
            $CONOCIMIENTO .= "SUM(CASE WHEN tc.unidad=".$i." THEN tc.conocimiento END) AS conocimiento".$i.$coma;
            $DESEMPENO .= "SUM(CASE WHEN tc.unidad=".$i." THEN tc.desempeno END) AS desempeno".$i.$coma;
            $CALIFICACION .= "SUM(CASE WHEN tc.unidad=".$i." THEN tc.calificacion END) AS calificacion".$i.$coma;
        }
        }else{
            $ACTITUD = "tc.idtc";
            $CONOCIMIENTO = "tc.idtc";
            $DESEMPENO = "tc.idtc";
            $CALIFICACION = "tc.idtc";
        }
     $materia = DB::select("SELECT nombre, descripcion FROM materias WHERE idm=$idm");
     $grupo = DB::select("SELECT nombre FROM grupos WHERE idgr=$idm");   
     $alumnos = DB::select("SELECT tc.idtc,a.nombre,a.app,a.apm,tc.unidad,a.matricula,$ACTITUD,$CONOCIMIENTO,$DESEMPENO,$CALIFICACION FROM trayectoria_cuatrimestral tc INNER JOIN alumnos a ON tc.alumno_id=a.ida WHERE tc.materia_id=$idm;");   

    return view('trayectoriac.reporte_pdf', compact('alumnos','length','materia','grupo'));
    }
    public function reporte_pdf(Request $request)
    {
    $array = array();
        $ACTITUD= '';
        $CONOCIMIENTO= '';
        $DESEMPENO = '';
        $CALIFICACION = '';
        $unidades = DB::select("SELECT unidades FROM materias WHERE idm=$request->materia;");
        $length = $unidades[0]->unidades;
        $c = DB::select("SELECT tc.idtc,tc.actitud,tc.conocimiento,tc.desempeno,tc.calificacion,a.nombre,a.app,a.apm,tc.unidad FROM trayectoria_cuatrimestral tc INNER JOIN alumnos a ON tc.alumno_id=a.ida WHERE tc.materia_id=$request->materia ;");
        if(isset($c[0])){
        for ($i = 1; $i <= $length; $i++) {
            if($i==$length){
                $coma = '';
            }else{
                $coma = ',';
            }
            $ACTITUD .= "SUM(CASE WHEN tc.unidad=".$i." THEN tc.actitud END) AS actitud".$i.$coma;
            $CONOCIMIENTO .= "SUM(CASE WHEN tc.unidad=".$i." THEN tc.conocimiento END) AS conocimiento".$i.$coma;
            $DESEMPENO .= "SUM(CASE WHEN tc.unidad=".$i." THEN tc.desempeno END) AS desempeno".$i.$coma;
             $CALIFICACION .= "SUM(CASE WHEN tc.unidad=".$i." THEN tc.calificacion END) AS calificacion".$i.$coma;
        }
        }else{
            $ACTITUD = "tc.idtc";
            $CONOCIMIENTO = "tc.idtc";
            $DESEMPENO = "tc.idtc";
            $CALIFICACION = "tc.idtc";
        }
     $materia = DB::select("SELECT nombre, descripcion FROM materias WHERE idm=$request->materia");
     $grupo = DB::select("SELECT nombre FROM grupos WHERE idgr=$request->grupo");   
     $alumnos = DB::select("SELECT tc.idtc,a.nombre,a.app,a.apm,tc.unidad,a.matricula,$ACTITUD,$CONOCIMIENTO,$DESEMPENO,$CALIFICACION FROM trayectoria_cuatrimestral tc INNER JOIN alumnos a ON tc.alumno_id=a.ida WHERE tc.materia_id=$request->materia;"); 
         
    return Excel::download(new TrayectoriaCExport($materia,$grupo,$alumnos,$length), 'products.xlsx');
    }
    public function delete($idtc)
    {
        $Q = DB::select("SELECT alumno_id FROM trayectoria_cuatrimestral WHERE idtc=$idtc");
        $ida = $Q[0]->alumno_id; 
        DB::delete("DELETE FROM trayectoria_cuatrimestral WHERE alumno_id=$ida;"); 
        return back()->with('mensaje', 'Registro eliminado exitosamente');
    }

}
