@extends('layout.layout')
@section('content')
{{--@if (Auth()->user()->idtu_tipos_usuarios == 1 || Auth()->user()->idtu_tipos_usuarios == 2)--}}

<div class="card">
    <div class="card-header bg-success text-light text-center">
        <h3>A S E S O R Í A S</h3>
    </div>
   
    <div class="card-body">
        <form id="formulario-crear-tutoria" action="{{ route('asesorias.store') }}" method="POST" enctype="multipart/form-data">
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
                <div class="form-group col-xs-3 col-md-6">
                   <label for="titulo">Título: <b class="text-danger">*</b></label>
                    <input type="text" class="form-control" name="titulo">
                    
                </div>
                 <div class="form-group col-xs-3 col-md-6">
                     <label for="descripcion">Descripción: <b class="text-danger">*</b></label>
                     <input type="text" class="form-control" name="descripcion">  
                    
                </div>
            </div>
            <div class="row">
                <div class="form-group col-xs-3 col-md-6">
                   <label for="maestro">Profesor: <b class="text-danger">*</b></label>
                    <select class="form-select" id="maestro" name="maestro">
                        <option value="">Selección</option>
                        @foreach($maestros as $maestro)
                            <option value="{{ $maestro->idm }}" {{ (old('maestro') == $maestro->idm) ? 'selected' : '' }}>{{$maestro->nombre}} {{$maestro->app}} {{$maestro->apm}}</option>
                        @endforeach
                    </select>
                    
                </div>
                 <div class="form-group col-xs-3 col-md-6">
                     <label for="tipo">Tipo: <b class="text-danger">*</b></label>
                    <select class="form-select" id="tipo" name="tipo" required>
                        <option value="">Selección</option>
                            <option value="1" {{ (old('tipo') == 1) ? 'selected' : '' }}>Académica</option>
                            <option value="2" {{ (old('tipo') == 2) ? 'selected' : '' }}>Nivelación</option>
                    </select>
                    
                </div>
            </div>
            
            <div class="row">
                <div class="form-group col-xs-3 col-md-6">
                     <label for="fecha">Fecha: <b class="text-danger">*</b></label>
                   <input class="form-control" type="datetime-local" id="fecha" name="fecha" >
                    
                </div>
                <div class="form-group col-xs-3 col-md-6">
                     <label for="fecha">Archivo: <b class="text-danger">*</b></label>
                   <input class="form-control" type="file" id="archivo" name="archivo" >
                    
                </div>
            </div>

            <div class="form-group text-center">
                <button title="Guardar" type="submit" id="submit" class="btn btn-success"><i class="fas fa-user-check"></i></button>
                <a title="Cancelar" href="{{ route('tutorias.index') }}" class="btn btn-danger"><i class="fas fa-ban"></i></a>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
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
        function studentsByGroup(idg){
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
                        $('#alumno').append('<option value="'+val.ida+'">'+val.nombre+' '+val.app+' '+val.apm+'</option>');
                    })  
               },
               
             });
        }    
    });
</script>
{{--@endif--}}
@endsection
