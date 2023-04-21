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
{{--@if (Auth()->user()->idtu_tipos_usuarios == 1 || Auth()->user()->idtu_tipos_usuarios == 2)--}}
    <div class="card">
            <div class="card-header bg-success text-light" style="text-align: center;">
                <h3>T U T O R Í A S</h3>
            </div>
            <div class="card-body">
                @foreach($tutoria as $t)
                    <form id="formulario-actualizar-alumno" action="{{ route('tutorias.update', $t->idt) }}" method="POST" enctype="multipart/form-data">
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
                <div class="form-group col-xs-3 col-md-6">
                   <label for="maestro">Profesor: <b class="text-danger">*</b></label>
                    <select class="form-select" id="maestro" name="maestro">
                        <option value="">Selección</option>
                        @foreach($maestros as $maestro)
                            <option value="{{ $maestro->idm }}" {{ (old('maestro') == $maestro->idm) || ($t->maestro_id == $maestro->idm) ? 'selected' : '' }}>{{$maestro->nombre}} {{$maestro->app}} {{$maestro->apm}}</option>
                        @endforeach
                    </select>
                    
                </div>
                 <div class="form-group col-xs-3 col-md-6">
                     <label for="tipo">Tipo: <b class="text-danger">*</b></label>
                    <select class="form-select" id="tipo" name="tipo" required>
                        <option value="">Selección</option>
                            <option value="1" {{ (old('tipo') == 1) || ($t->tipo == 1) ? 'selected' : '' }}>Individual</option>
                            <option value="2" {{ (old('tipo') == 2) || ($t->tipo == 2) ? 'selected' : '' }}>Grupal</option>
                    </select>
                    
                </div>
            </div>
            
            <div class="row">
                <div class="form-group col-xs-3 col-md-6">
                     <label for="fecha">Fecha: <b class="text-danger">*</b></label>
                   <input value="<?php echo date('Y-m-d\TH:i',strtotime($t->fecha)); ?>" class="form-control" type="datetime-local" id="fecha" name="fecha" >
                    
                </div>
                <div class="form-group col-xs-3 col-md-6">
                     <label for="fecha">Archivo: <b class="text-danger">*</b></label>
                     <input name="old" value="{{$t->archivo}}" type="hidden">
                     <input name="nombre_old" value="{{$t->archivo_nombre}}" type="hidden">
                     <a href='<?php echo "../../storage/evidencia_tutorias/".$t->archivo?>' target='_blank'><?php echo $t->archivo_nombre?></a>
                   <input class="form-control" type="file" id="archivo" name="archivo" >
                    
                </div>
            </div>
            <div class="row">
                
                <div class="form-group grupo col-xs-3 col-md-6">
                   <label for="grupo">Grupo: <b class="text-danger">*</b></label>
                    <select class="form-select" id="grupo" name="grupo">
                        <option value="">Selección</option>
                        @foreach($grupos as $grupo)
                            <option value="{{ $grupo->idgr }}" {{ (old('grupo') == $grupo->idgr) || ($t->grupo_id == $grupo->idgr) ? 'selected' : '' }}>{{$grupo->nombre}} </option>
                        @endforeach
                    </select>
                    
                </div>
                <input value="{{$t->alumno_id}}" type="hidden" class="selected_alumno">
                <div style="display: none;" class="form-group alumno col-xs-3 col-md-6">
                   <label for="alumno">Alumno: <b class="text-danger">*</b></label>
                    <select class="form-select" id="alumno" name="alumno">
                        <option value="">Selección</option>
                        
                    </select>
                    
                </div>
            </div>

                                <div class="form-group text-center">
                                    <button title="Actualizar" type="submit" id="submit" class="btn btn-primary"><i class="fas fa-user-check"></i></button>
                                    <a title="Guardar" href="{{ route('tutorias.index') }}" class="btn btn-danger"><i class="fas fa-ban"></i></a>
                                </div>
                    </form>
                 @endforeach

                 <script>
        $( document ).ready(function() {

            $('#tipo').change(function(){
                if($(this).val()==1){
                    $('.alumno').css('display','block');
                }else if($(this).val()==2){
                    $('.alumno').css('display','none');
                }
            })
            $('#grupo').change(function(){
                $("#alumno").find('option').not(':first').remove();
                if($(this).val()!=''){
                    studentsByGroup($(this).val());
                }   
            })
            if($('#tipo').val()==1){
                    $('.alumno').css('display','block');
                    studentsByGroup($('#grupo option:selected').val(),$('.selected_alumno').val());
                }else if($('#tipo').val()==2){
                    $('.alumno').css('display','none');
                }
             

            function studentsByGroup(idg,ida){
            $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
         });
            $.ajax({
               url:'/students/by_group',
               data:{'grupo_id':idg},
               type:'post',
               success:  function (response) {
                    $.each(response,function(i,val){
                        $('#alumno').append('<option '+(ida==val.ida ? 'selected' : '')+' value="'+val.ida+'">'+val.nombre+' '+val.app+' '+val.apm+'</option>');
                    })  
               },
               
             });
        }
        }); 

    </script>
        </div>
    </div>
{{--@endif--}}
@endsection
