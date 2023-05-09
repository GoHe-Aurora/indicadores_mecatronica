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
        $CALIFICACION = '';
        $unidades = DB::select("SELECT unidades FROM materias WHERE idm=$mat;");
        $length = $unidades[0]->unidades;
        $c = DB::select("SELECT tc.idtc,tc.actitud,tc.conocimiento,tc.desempeno,tc.calificacion,a.nombre,a.app,a.apm,tc.unidad FROM trayectoria_cuatrimestral tc INNER JOIN alumnos a ON tc.alumno_id=a.ida WHERE tc.materia_id=$mat AND a.grupo_id=$grupo_id;");
        if(isset($c[0])){
        for ($i = 1; $i <= $length; $i++) {
            if($i==$length){
                $coma = '';
            }else{
                $coma = ',';
            }
            $ACTITUD .= "SUM(CASE WHEN tc.unidad=".$i." THEN tc.actitud END) AS actitud".$i.$coma;
        }
        for ($i = 1; $i <= $length; $i++) {
            if($i==$length){
                $coma = '';
            }else{
                $coma = ',';
            }
            $CONOCIMIENTO .= "SUM(CASE WHEN tc.unidad=".$i." THEN tc.conocimiento END) AS conocimiento".$i.$coma;
        }
        for ($i = 1; $i <= $length; $i++) {
            if($i==$length){
                $coma = '';
            }else{
                $coma = ',';
            }
            $DESEMPENO .= "SUM(CASE WHEN tc.unidad=".$i." THEN tc.desempeno END) AS desempeno".$i.$coma;
        }
        for ($i = 1; $i <= $length; $i++) {
            if($i==$length){
                $coma = '';
            }else{
                $coma = ',';
            }
            $CALIFICACION .= "SUM(CASE WHEN tc.unidad=".$i." THEN tc.calificacion END) AS calificacion".$i.$coma;
        }
        }else{
            $ACTITUD = "tc.idtc";
            $CONOCIMIENTO = "tc.idtc";
            $DESEMPENO = "tc.idtc";
            $CALIFICACION = "tc.idtc";
        }
        $alumnos = DB::select("SELECT tc.idtc,tc.actitud,tc.conocimiento,tc.desempeno,tc.calificacion,a.nombre,a.app,a.apm,tc.unidad,$ACTITUD,$CONOCIMIENTO,$DESEMPENO,$CALIFICACION FROM trayectoria_cuatrimestral tc INNER JOIN alumnos a ON tc.alumno_id=a.ida WHERE tc.materia_id=$mat;");
        $grupos = DB::select("SELECT idgr,nombre,descripcion FROM grupos;");
        $materias = DB::select("SELECT idm,nombre,descripcion FROM materias WHERE unidades IS NOT NULL;");
        function btn($idtc){
           
                $botones = "<a href=\"#eliminar-tc\" class=\"btn btn-danger mt-1\" onclick=\"formSubmit('eliminar-tc-$idtc')\"><i class='fas fa-power-off'></i></a>"
                         . "<a href= ". route('trayectoriac.edit', $idtc ) ." class=\"btn btn-primary mt-1\"> <i class='fa fa-user-alt'></i> </a>";
                
            return $botones;
        }
        foreach ($alumnos as $alumno){
             
            array_push($array, [
                'idtc'                => $alumno->idtc,
                'nombre'              => $alumno->nombre,
                'app'                 => $alumno->app,
                'apm'                 => $alumno->apm,
                'operaciones'         => btn($alumno->idtc)
            ]);
          
        }
        $arr = array();
        if(isset($array[0])){
        for ($i = 1; $i <= $length; $i++) {
            $actitud = 'actitud'.$i;
            $arr['actitud'.$i] = $alumnos[0]->$actitud!=null ?  $alumnos[0]->$actitud : 'SIN ASIGNAR';
            $conocimiento = 'actitud'.$i;
            $arr['conocimiento'.$i] = $alumnos[0]->$conocimiento!=null ? $alumnos[0]->$conocimiento : 'SIN ASIGNAR';;
            $desempeno = 'desempeno'.$i;
            $arr['desempeno'.$i] = $alumnos[0]->$desempeno!=null ? $alumnos[0]->$desempeno : 'SIN ASIGNAR';
            $calificacion = 'calificacion'.$i;
            $arr['calificacion'.$i] = $alumnos[0]->$calificacion!=null ? $alumnos[0]->$calificacion : 'SIN ASIGNAR';
        }
        }
        if(isset($array[0])){
           $arr = array_merge($array[0],$arr);
        }
        //$json = json_encode(array($arr));
        $json = '[{"idtc":1,"nombre":"Karen","app":"Torres","apm":"P\u00e9rez","operaciones":"<\/i><\/a> <\/i> <\/a>","actitud1":"Responsabilidad 8 \n Colaborativo 9 \n Relaciones Interpersonales 10 \n Creatividad 7","conocimiento1":"Marco Teórico y Conceptual 8 \nManejo de Información 10","desempeno1":"Practicas 9 \n Estudios de Caso 10 \n Proyecto 7 \n Ejerccios 8 \n Ensayo 10","calificacion1":8,"actitud2":6,"conocimiento2":6,"desempeno2":6,"calificacion2":6,"actitud3":"SIN ASIGNAR","conocimiento3":"SIN ASIGNAR","desempeno3":"SIN ASIGNAR","calificacion3":"SIN ASIGNAR","actitud4":"SIN ASIGNAR","conocimiento4":"SIN ASIGNAR","desempeno4":"SIN ASIGNAR","calificacion4":"SIN ASIGNAR"}]';
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
            'actitud' => 'required|numeric', 
            'conocimiento' => 'required|numeric', 
            'desempeno' => 'required|numeric', 
            //'calificacion' => 'required|numeric',
        ]);
        $c = ($request->actitud+$request->conocimiento+$request->desempeno)/3;
        $calif = number_format((float)$c, 2, '.', '');
        TrayectoriaC::create([
            'alumno_id' => $request->alumno,
            'materia_id' => $request->materia,
            'unidad' => $request->unidad,
            'actitud' => $request->actitud,
            'conocimiento' => $request->conocimiento,
            'desempeno' => $request->desempeno,
            'calificacion' => $calif
        ]);
         
        return redirect()->route('trayectoriac.index')->with('mensaje', 'El registro se ha guardado exitosamente');
    }

    public function edit($idtc)
    {
        $tc = DB::select("SELECT * FROM trayectoria_cuatrimestral WHERE idtc=$idtc;");  
        $grupos = DB::select("SELECT idgr,nombre FROM grupos;");
        $materias = DB::select("SELECT idm,nombre,descripcion FROM materias WHERE unidades IS NOT NULL;");
        
        return view('trayectoriac.edit', compact('tc','grupos','materias'));
    }
    public function unidades(Request $request)
    {
        $array = array(); 
        $unidades = DB::select("SELECT unidades FROM materias WHERE idm=$request->materia;");
        $unidades_asig = DB::select("SELECT unidad FROM trayectoria_cuatrimestral WHERE materia_id=$request->materia;");
        for ($i = 1; $i <= $unidades[0]->unidades; $i++) {
            if(!isset($unidades_asig[$i-1]) || $i==$request->unidad){
               array_push($array,$i);  
            }     
        }
        return json_encode($array);
    }
    /**
     * Actualiza un usuario.
     */
    public function update(Request $request, $idtc)
    {
        $validator = $request->validate([
            'alumno' => 'required',
            'grupo'  => 'required',
            'materia' => 'required', 
            'unidad' => 'required', 
            'actitud' => 'required|numeric', 
            'conocimiento' => 'required|numeric', 
            'desempeno' => 'required|numeric', 
            //'calificacion' => 'required|numeric',
        ]);
        $c = ($request->actitud+$request->conocimiento+$request->desempeno)/3;
        $calif = number_format((float)$c, 2, '.', '');
        TrayectoriaC::where('idtc',$idtc)->update([
            'alumno_id' => $request->alumno,
            'materia_id' => $request->materia,
            'unidad' => $request->unidad,
            'actitud' => $request->actitud,
            'conocimiento' => $request->conocimiento,
            'desempeno' => $request->desempeno,
            'calificacion' => $calif
        ]);
                
                return redirect()->route('trayectoriac.index')->with('mensaje', 'El registro se ha actualizado exitosamente');
            
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
        }
        for ($i = 1; $i <= $length; $i++) {
            if($i==$length){
                $coma = '';
            }else{
                $coma = ',';
            }
            $CONOCIMIENTO .= "SUM(CASE WHEN tc.unidad=".$i." THEN tc.conocimiento END) AS conocimiento".$i.$coma;
        }
        for ($i = 1; $i <= $length; $i++) {
            if($i==$length){
                $coma = '';
            }else{
                $coma = ',';
            }
            $DESEMPENO .= "SUM(CASE WHEN tc.unidad=".$i." THEN tc.desempeno END) AS desempeno".$i.$coma;
        }
        for ($i = 1; $i <= $length; $i++) {
            if($i==$length){
                $coma = '';
            }else{
                $coma = ',';
            }
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
        }
        for ($i = 1; $i <= $length; $i++) {
            if($i==$length){
                $coma = '';
            }else{
                $coma = ',';
            }
            $CONOCIMIENTO .= "SUM(CASE WHEN tc.unidad=".$i." THEN tc.conocimiento END) AS conocimiento".$i.$coma;
        }
        for ($i = 1; $i <= $length; $i++) {
            if($i==$length){
                $coma = '';
            }else{
                $coma = ',';
            }
            $DESEMPENO .= "SUM(CASE WHEN tc.unidad=".$i." THEN tc.desempeno END) AS desempeno".$i.$coma;
        }
        for ($i = 1; $i <= $length; $i++) {
            if($i==$length){
                $coma = '';
            }else{
                $coma = ',';
            }
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
        DB::delete("DELETE FROM trayectoria_cuatrimestral WHERE idtc=$idtc;"); 
        return back()->with('mensaje', 'Registro eliminado exitosamente');
    }

}
