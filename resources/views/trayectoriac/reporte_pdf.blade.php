<!DOCTYPE html>
<html lang="es">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
<style type="text/css">
  td{
    border-color:black;
    border-width:2px;
  }
</style>

<div class="row">
<div class="col-md-6" style="text-align:center;">
  <!--img style="width: 100px;" src="./images/reporte_header.png"-->
<h3>UNIVERSIDAD TECNOLÓGICA DEL VALLLE DE TOLUCA</h3>
<p>DEPARTAMENTO DE SERVICIOS EDUCATIVOS</p>
<p>DIRECCIÓN DE CARRERA: MECATRÓNICA Y SISTEMAS PRODUCTIVOS</p>
<p>ALUMNOS INSCRITOS</p>
@php
 $month = date('m');
 $year = date('Y');
 switch ($month) {
    case '01':
    case '02':
    case '03':
    case '04':
        $periodo = 'ENERO-ABRIL';
        break;
    case '05':
    case '06':
    case '07':
    case '08':
        $periodo = 'MAYO-AGOSTO';
        break;  
    case '09':
    case '10':
    case '11':
    case '12':
        $periodo = 'SEPTIEMBRE-DICIEMBRE';
        break;  
    default:
        //code to be executed
}
 @endphp
<p>CUATRIMESTRE {{$periodo}} {{$year}}</p>
<p>GRUPO: {{$grupo[0]->nombre}}</p>
 
</div>
<div class="col-md-6"></div>
</div>
<table style="width : 100%;" border="1px" color="black">
  <tr>
    <td colspan="2"></td>
    <td>MATERIA: {{$materia[0]->nombre}} {{$materia[0]->descripcion}}</td>
    @for ($i = 1; $i <= $length; $i++)
    <td colspan="4">UNIDAD {{$i}}</td>
    @endfor
  </tr>
  <tr>

    <td>NO.</td>
    <td>NO DE CUENTA</td>
    <td>NOMBRE DEL ALUMNO</td>
    @for ($i = 1; $i <= $length; $i++)
    <td>ACTITUD</td>
    <td>CONOCIMIENTO</td>
    <td>DESEMPEÑO</td>
    <td>CALIFICACIÓN</td>
    @endfor
  </tr>
@foreach($alumnos as $key=>$alumno)   
  <tr>
    <td>{{$key+1}}</td>
    <td>{{$alumno->matricula}}</td>
    <td>{{$alumno->nombre}} {{$alumno->app}} {{$alumno->apm}}</td>
     @for ($i = 1; $i <= $length; $i++)
     @php 
     $actitud = 'actitud'.$i; 
     $conocimiento = 'conocimiento'.$i;
     $desempeno = 'desempeno'.$i;
     $calificacion = 'calificacion'.$i;
     @endphp
    <td>{{$alumno->$actitud}}</td>
    <td>{{$alumno->$conocimiento}}</td>
    <td>{{$alumno->$desempeno}}</td>
    <td>{{$alumno->$calificacion}}</td>
    @endfor
  </tr>
@endforeach  

</table>
</html>