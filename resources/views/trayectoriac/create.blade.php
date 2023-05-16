@extends('layout.layout')
@section('content')
<style type="text/css">
    .select2-container--default .select2-selection--multiple .select2-selection__choice{
        background-color:#000;
    }
    .select2-container{
        width: 100% !important;
    }
</style>
@if (Auth()->user()->idtu_tipos_usuarios == 1 || Auth()->user()->idtu_tipos_usuarios == 2)
<div class="card">
    <div class="card-header bg-success text-light text-center">
        <h3>S I S T E M A &nbsp; D E &nbsp; C A L I F I C A C I O N E S &nbsp; C U A T R I M E S T R A L</h3>
    </div>
   
    <div class="card-body">
        <form id="formulario-crear-vae" action="{{ route('trayectoriac.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                 <div class="form-group grupo col-xs-3 col-md-6">
                   <label for="grupo">Grupo: <b class="text-danger">*</b></label>
                    <select class="form-select" id="grupo" name="grupo">
                        <option value="">Selección</option>
                        @foreach($grupos as $grupo)
                            <option value="{{ $grupo->idgr }}" {{ (old('grupo') == $grupo->idgr) ? 'selected' : '' }}>{{ $grupo->nombre }}</option>
                        @endforeach
                    </select>
                </div>    

                <div class="col-md-6 col-xs-3 pb-3">
                    <label for="alumno">Alumno: <b class="text-danger">*</b></label>
                    <select class="form-select" id="alumno" name="alumno">
                        <option value="">Selección</option>
                        
                    </select>
                    
                </div>
            </div>
            
            <div class="row">
                
                <div class="form-group col-xs-3 col-md-6">
                     <label for="materia">Materia: <b class="text-danger">*</b></label>
                    <select class="form-select" id="materia" name="materia">
                        <option value="">Selección</option>
                        @foreach($materias as $materia)
                            <option value="{{ $materia->idm }}" {{ (old('materia') == $materia->idm) ? 'selected' : '' }}>{{ $materia->nombre }} {{ $materia->descripcion }}</option>
                        @endforeach
                    </select>

                </div>
                <div class="form-group col-xs-3 col-md-6">
                     <label for="unidad">Unidad: <b class="text-danger">*</b></label>
                    <select class="form-select" id="unidad" name="unidad">
                        <option value="">Selección</option>
                        
                    </select>

                </div>
            </div>
            <div class="row">
                
                <div class="form-group col-xs-3 col-md-6">
                     <label for="actitud">Actitud: <b class="text-danger">*</b></label>
                     <div class="row">
                     <div class="col-md-6">   
                    <input type="number" class="form-control" id="responsabilidad" name="responsabilidad" value="{{ old('responsabilidad') }}" placeholder="Responsabilidad" required>
                     </div>
                     <div class="col-md-6">
                    <input type="number" class="form-control" id="colborativo" name="colborativo" value="{{ old('colborativo') }}" placeholder="Colaborativo" required> 
                </div>
                 </div><br>
                 <div class="row">
                     <div class="col-md-6">   
                    <input type="number" class="form-control" id="relaciones_i" name="relaciones_i" value="{{ old('relaciones_i') }}" placeholder="Relaciones Interpersonales" required>
                     </div>
                     <div class="col-md-6">
                    <input type="number" class="form-control" id="creatividad" name="creatividad" value="{{ old('creatividad') }}" placeholder="Creatividad" required> 
                </div>
                 </div>
                </div>
                <div class="form-group col-xs-3 col-md-6">
                     <label for="conocimiento">Conocimiento: <b class="text-danger">*</b></label>
                     <div class="row">
                     <div class="col-md-6">   
                    <input type="number" class="form-control" id="marcotc" name="marcotc" value="{{ old('marcotc') }}" placeholder="Marco teórico y conceptual" required>
                     </div>
                     <div class="col-md-6">
                    <input type="number" class="form-control" id="manejo_info" name="manejo_info" value="{{ old('manejo_info') }}" placeholder="Manejo de información" required> 
                </div>
                 </div>

                </div>
                
            </div>
            <div class="row">
            <div class="form-group col-xs-3 col-md-6">
                     <label for="desempeno">Desempeño: <b class="text-danger">*</b></label>
                    <div class="row">
                     <div class="col-md-6">   
                    <input type="number" class="form-control" id="practicas" name="practicas" value="{{ old('practicas') }}" placeholder="Prácticas" required>
                     </div>
                     <div class="col-md-6">
                    <input type="number" class="form-control" id="estudios_caso" name="estudios_caso" value="{{ old('estudios_caso') }}" placeholder="Estudios de caso" required> 
                </div>
                 </div>
                 <br>
                 <div class="row">
                     <div class="col-md-6">   
                    <input type="number" class="form-control" id="proyecto" name="proyecto" value="{{ old('proyecto') }}" placeholder="Proyecto" required>
                     </div>
                     <div class="col-md-6">
                    <input type="number" class="form-control" id="ejercicios" name="ejercicios" value="{{ old('ejercicios') }}" placeholder="Ejercicios" required> 
                </div>
                 </div><br>
                 <div class="row">
                     <div class="col-md-6">   
                    <input type="number" class="form-control" id="ensayo" name="ensayo" value="{{ old('ensayo') }}" placeholder="Ensayo" required>
                     </div>
                     <div class="col-md-6">
                    
                </div>
                 </div>
             </div>
             <div class="form-group col-xs-3 col-md-6">
                    <div class="row">
                    <div class="col-md-6"> 
                    <label for="calificacion">Calificación: <b class="text-danger">*</b></label>  
                    <input type="number" class="form-control" id="proyecto" name="proyecto" value="{{ old('proyecto') }}" placeholder="Sin redondear" required>
                    </div>
                    <div class="col-md-6">
                    <label for="calificacion">Calificación Acta: <b class="text-danger">*</b></label>  
                    <input type="number" class="form-control" id="ejercicios" name="ejercicios" value="{{ old('ejercicios') }}" placeholder="Redondeada" required> 
                </div>
                 </div>
             </div>
                </div>
            <div class="form-group text-center">
                <button title="Guardar" type="submit" id="submit" class="btn btn-success"><i class="fas fa-user-check"></i></button>
                <a title="Cancelar" href="{{ route('trayectoriac.index') }}" class="btn btn-danger"><i class="fas fa-ban"></i></a>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $( document ).ready(function() {

    $('#materia').change(function(){
                $("#unidad").find('option').not(':first').remove();
                if($(this).val()!=''){
                    unidades($(this).val());
                }   
            })
    $('#grupo').change(function(){
                $("#alumno").find('option').not(':first').remove();
                if($(this).val()!=''){
                    studentsByGroup($(this).val(),1);
                }   
            })
    $('#actitud,#conocimiento,#desempeno').keyup(function(e){
        var code = (e.which) ? e.which : e.keyCode;
        if(code>=48 && code<=57){
                if($('#actitud').val()!='' && $('#conocimiento').val()!='' && $('#desempeno').val()!=''){
                let suma = parseFloat($('#actitud').val())+parseFloat($('#conocimiento').val())+parseFloat($('#desempeno').val()); 
                let calif = suma/3;  
                $('#calificacion').val(calif.toFixed(2)); 
                } 
            }
            })
        function studentsByGroup(idg,opc){
            $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
         });
            $.ajax({
               url:'/students/by_group',
               data:{'grupo_id':idg,'opc':opc},
               type:'post',
               success:  function (response) {
                    $.each(response,function(i,val){
                        $('#alumno').append('<option value="'+val.ida+'">'+val.nombre+' '+val.app+' '+val.apm+'</option>');
                    })  
               },
               
             });
        }   
        function unidades(materia){
            $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
         });
            $.ajax({
               url:'/trayectoriac/unidades',
               data:{'materia':materia},
               type:'post',
               dataType:'json',
               success:  function (response) {
                    $.each(response,function(i,val){
                        $('#unidad').append('<option value="'+val+'">'+val+'</option>');
                    })        
               },
             });
        }  
});
</script>
@endif
@endsection
