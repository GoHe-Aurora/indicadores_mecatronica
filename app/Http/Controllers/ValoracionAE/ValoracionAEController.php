<?php

namespace App\Http\Controllers\ValoracionAE;

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

class ValoracionAEController extends Controller
{
    /**
     * Vista para mostrar un listado de alumnos.
     */
    public function index(Request $request)
    {
        $condition1 = '';
        $condition2 = '';
        $grupo_id = '';
        $materia_id = '';
        $atributo_id = -1;
        if($request->grupo){
            $grupo_id = $request->grupo;
            $condition1 = "AND a.grupo_id=$grupo_id";
        }
        if($request->materia){
            $materia_id = $request->materia;
            $condition2 = "AND ma.idm=$materia_id";
        }
        if($request->atributo){
            $atributo_id = $request->atributo;
        }
        $array = array();
        $alumnos = DB::select("SELECT a.ida idalu,a.nombre,a.app,a.apm,ma.lo_supera,ma.lo_logra,ma.lo_logra_parcialmente,ma.no_lo_logra,at.ida,at.lo_supera supera,at.lo_logra logra,at.lo_logra_parcialmente logra_parc,at.no_lo_logra no_logra FROM alumnos a LEFT JOIN alumnos_atributos_egreso_pe AT ON at.idalu=a.ida LEFT JOIN materias_atributos ma ON ma.ida=$atributo_id WHERE 1=1 $condition1 $condition2 GROUP BY a.ida;");
        $grupos = DB::select("SELECT idgr,nombre FROM grupos;");
        $materias = DB::select("SELECT * FROM materias;");
        function btn($idv){
           
                $botones = "<a href=\"#eliminar-vae\" class=\"btn btn-danger mt-1\" onclick=\"formSubmit('eliminar-vae-$idv')\"><i class='fas fa-power-off'></i></a>"
                         /*. "<a href= ". route('valoracion_ae.edit', $idv ) ." class=\"btn btn-primary mt-1\"> <i class='fa fa-user-alt'></i> </a>"*/;
                
            return $botones;
        }
         function ck($txt,$opc,$alumno,$val){
            $checked = $val!=null ? 'checked' : '';
            if($opc==1){
              $ck = '<input type="checkbox" data-alumno="'.$alumno.'" '.$checked.' data-id="" class="ck" name="academica" id="lo_supera" value="1">
<label for="academica">Lo Supera</label>';   
            }else if($opc==2){
              $ck = '<input form="myForm" type="checkbox" '.$checked.' data-alumno="'.$alumno.'" data-id="" class="ck" name="nivelacion" id="lo_logra"  value="2"> 
<label for="nivelacion">Lo Logra</label>';  
            }else if($opc==3){
              $ck = '<input form="myForm" type="checkbox" '.$checked.' data-alumno="'.$alumno.'" data-id="" class="ck" name="nivelacion" id="lo_logra_parcialmente"  value="3"> 
<label for="nivelacion">Lo Logra Parcialmente</label>';
            }else if($opc==4){
              $ck = '<input form="myForm" type="checkbox" '.$checked.' data-alumno="'.$alumno.'" data-id="" class="ck" name="nivelacion" id="no_lo_logra"  value="4"> 
<label for="nivelacion">No Lo Logra</label>';
            }
            if($txt!=''){
               return $txt.$ck; 
            }else{
               return $txt;  
            }
            
        }
        foreach ($alumnos as $alumno){
            array_push($array, array(
                'nombre'                   => $alumno->nombre,
                'app'                      => $alumno->app,
                'apm'                      => $alumno->apm,
                'lo_supera'                => ck($alumno->lo_supera,1,$alumno->idalu,$alumno->supera),
                'lo_logra'                 => ck($alumno->lo_logra,2,$alumno->idalu,$alumno->logra),
                'lo_logra_parcialmente'    => ck($alumno->lo_logra_parcialmente,3,$alumno->idalu,$alumno->logra_parc),
                'no_lo_logra'              => ck($alumno->no_lo_logra,4,$alumno->idalu,$alumno->no_logra),
                //'operaciones'              => btn($alumno->idat)
            ));
        }
        /*array_push($array, array(
                'idv'                 => 1,
                'nombre'              => 'Aurora Guadalupe',
                'app'                 => 'González',
                'apm'                 => 'Hernández',
                'puntuacion'        => ck(),
                'operaciones'         => btn(1)
            ));*/
        $json = json_encode($array);
        return view("valoracion_ae.index", compact("json","alumnos","grupos","materias","grupo_id","materia_id","atributo_id"));
    }

    /**
     * Vista que muestra un formulario para crear un usuario.
     */
    public function create()
    {
        $grupos_tsu = DB::select("SELECT idgr,nombre FROM grupos WHERE cuatrimestre_id<7;");
        $grupos_ing = DB::select("SELECT idgr,nombre FROM grupos WHERE cuatrimestre_id>=7;");

        return view( 'valoracion_ae.create', compact('grupos_tsu','grupos_ing'));

    }
    public function atributos($materia)
    {
    $atributos = DB::select("SELECT at.idae,at.descripcion FROM atributos_egreso_pe AT INNER JOIN materias_atributos ma ON ma.ida=at.idae WHERE ma.idm=$materia;");
    return json_encode($atributos);
    }  
    public function agrega_atributo(Request $request)
    {
    $R = DB::select("SELECT idalu FROM alumnos_atributos_egreso_pe WHERE idalu=$request->alumno;");
    if(isset($R[0]->idalu)){
        if($request->val==1){
           DB::select("UPDATE alumnos_atributos_egreso_pe SET lo_supera=1,lo_logra=NULL,lo_logra_parcialmente=NULL,no_lo_logra=NULL WHERE idalu=$request->alumno");  
        }else if($request->val==2){
           DB::select("UPDATE alumnos_atributos_egreso_pe SET lo_supera=NULL,lo_logra=1,lo_logra_parcialmente=NULL,no_lo_logra=NULL WHERE idalu=$request->alumno");  
        }else if($request->val==3){
           DB::select("UPDATE alumnos_atributos_egreso_pe SET lo_supera=NULL,lo_logra=NULL,lo_logra_parcialmente=1,no_lo_logra=NULL WHERE idalu=$request->alumno");  
        }else if($request->val==4){
           DB::select("UPDATE alumnos_atributos_egreso_pe SET lo_supera=NULL,lo_logra=1,lo_logra_parcialmente=NULL,no_lo_logra=1 WHERE idalu=$request->alumno");  
        }
         
    }else{
        if($request->val==1){
           DB::select("INSERT INTO alumnos_atributos_egreso_pe (ida,idalu,lo_supera) VALUES($request->atributo,$request->alumno,1)");  
        }else if($request->val==2){
           DB::select("INSERT INTO alumnos_atributos_egreso_pe (ida,idalu,lo_logra) VALUES($request->atributo,$request->alumno,1)");  
        }else if($request->val==3){
           DB::select("INSERT INTO alumnos_atributos_egreso_pe (ida,idalu,lo_logra_parcialmente) VALUES($request->atributo,$request->alumno,1)");  
        }else if($request->val==4){
           DB::select("INSERT INTO alumnos_atributos_egreso_pe (ida,idalu,no_lo_logra) VALUES($request->atributo,$request->alumno,1)");  
        }
    }
    return json_encode("Registro agregado correctamente.");
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
        return redirect()->route('valoracion_ae.index')->with('mensaje', 'El registro se ha guardado exitosamente');
    }

    public function edit($idv)
    {
        $alumno = DB::select("SELECT v.*,a.ida,a.nombre,a.app,a.apm,a.matricula,g.nombre gr_tsu,g2.nombre gr_ing FROM valoracion_ae v INNER JOIN alumnos a ON v.alumno_id=a.ida INNER JOIN grupos g ON v.grupo_tsu=g.idgr LEFT JOIN grupos g2 ON v.grupo_ing=g2.idgr WHERE v.idv=$idv;");

        $grupos_tsu = DB::select("SELECT idgr,nombre FROM grupos WHERE cuatrimestre_id<7;");
        $grupos_ing = DB::select("SELECT idgr,nombre FROM grupos WHERE cuatrimestre_id>=7;");
        
        return view('valoracion_ae.edit', compact('alumno','grupos_tsu','grupos_ing'));
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
        ValoracionAE::where('idv',$idv)->update([
            'alumno_id' => $request->alumno,
            'promedio_tsu' => $request->promedio_tsu,
            'promedio_ing' => $request->promedio_ing,
            'grupo_tsu' => isset($request->grupo_tsu) ? $request->grupo_tsu : null,
            'grupo_ing' => isset($request->grupo_ing) ? $request->grupo_ing : null
        ]);
                
                return redirect()->route('valoracion_ae.index')->with('mensaje', 'El registro se ha actualizado exitosamente');
            
    }

    public function delete($idv)
    {
        DB::delete("DELETE FROM valoracion_ae WHERE idv=$idv;"); 
        return back()->with('mensaje', 'Registro eliminado exitosamente');
    }

}
