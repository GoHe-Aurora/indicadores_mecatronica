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
            <div class="card-header bg-success text-light" style="text-align: center;">
                <h3>T R A Y E C T O R I A   &nbsp;C U A T R I M E S T R A L</h3>
            </div>
            <div class="card-body">
                @foreach($tc as $t)
                    <form id="formulario-actualizar-tc" action="{{ route('trayectoriac.update', $t->idtc) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
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
                            <option value="{{ $materia->idm }}" {{ (old('materia') == $materia->idm || $t->materia_id==$materia->idm) ? 'selected' : '' }}>{{ $materia->nombre }} {{ $materia->descripcion }}</option>
                        @endforeach
                    </select>

                </div>
                <input type="hidden" class="unidad" value="{{$t->unidad}}">
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
                    <input type="number" class="form-control" id="actitud" name="actitud" value="{{ $t->actitud }}" placeholder="Ingresa la calificación" required> 

                </div>
                <div class="form-group col-xs-3 col-md-6">
                     <label for="conocimiento">Conocimiento: <b class="text-danger">*</b></label>
                    <input type="number" class="form-control" id="conocimiento" name="conocimiento" value="{{ $t->conocimiento }}" placeholder="Ingresa la calificación" required>

                </div>
                
            </div>
            <div class="row">
            <div class="form-group col-xs-3 col-md-6">
                     <label for="desempeno">Desempeño: <b class="text-danger">*</b></label>
                    <input type="number" class="form-control" id="desempeno" name="desempeno" value="{{ $t->desempeno }}" placeholder="Ingresa la calificación" required>
             </div>
             <div class="form-group col-xs-3 col-md-6">
                     <label for="calificacion">Calificación: <b class="text-danger">*</b></label>
                    <input type="number" class="form-control" id="calificacion" name="calificacion" value="{{ $t->calificacion }}" required disabled>
             </div>
                </div>


                                <div class="form-group text-center">
                                    <button title="Actualizar" type="submit" id="submit" class="btn btn-primary"><i class="fas fa-user-check"></i></button>
                                    <a title="Guardar" href="{{ route('trayectoriac.index') }}" class="btn btn-danger"><i class="fas fa-ban"></i></a>
                                </div>
                    </form>
                 @endforeach

                 <script>
        $( document ).ready(function() {
            if($('#materia').val()!=''){
                unidades($('#materia').val(),$('.unidad').val());
            }
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
        function unidades(materia,unidad){
            $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
         });
            $.ajax({
               url:'/trayectoriac/unidades',
               data:{'materia':materia,'unidad':unidad},
               type:'post',
               dataType:'json',
               success:  function (response) {
                    $.each(response,function(i,val){
                        $('#unidad').append('<option '+(val==unidad ? 'selected' : '')+' value="'+val+'">'+val+'</option>');
                    })        
               },
             });
        }   
        }); 

    </script>
        </div>
    </div>
            @endif
@endsection
