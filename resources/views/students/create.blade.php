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
        <h3>A L U M N O S</h3>
    </div>
   
    <div class="card-body">
        <form id="formulario-crear-alumno" action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
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
                <div class="form-group col-md-6 col-xs-3">
                    <label for="">Nombre: <b class="text-danger">*</b></label>
                    <input placeholder="Ingresa el nombre" required type="text" class="form-control" id="nombre" value="{{ old('nombre') }}" name="nombre">
                    
                </div>

                <div class="col-md-6 col-xs-3 pb-3">
                    <label for="titulo">Apellido Paterno: <b class="text-danger">*</b></label>
                    <input type="text" class="form-control  @error('app') is-invalid @enderror" id="titulo" name="app" value="{{ old('app') }}" placeholder="Ingresa el apellido paterno" required>
                    
                </div>
            </div>
            <div class="row">
                <div class="form-group col-xs-3 col-md-6">
                     <label for="apm">Apellido Materno: <b class="text-danger">*</b></label>
                    <input type="text" class="form-control @error('apm') is-invalid @enderror" id="apm" name="apm" value="{{ old('apm') }}" placeholder="Ingresa el apellido materno" required>
                    
                </div>

                <div class="form-group col-xs-3 col-md-6">
                   <label for="matricula">Matrícula: <b class="text-danger">*</b></label>
                    <input type="number" placeholder="Ingresa una matrícula" class="form-control @error('matricula') is-invalid @enderror" id="matricula" name="matricula" value="{{ old('matricula') }}" placeholder="Ingresa una matrícula válida" required>
                    
                </div>
            </div>
            <div class="row">
                <div class="form-group col-xs-3 col-md-6">
                     <label for="genero">Género: <b class="text-danger">*</b></label>
                    <select class="form-select" id="genero" name="genero" required>
                        <option value="">Selección</option>
                            <option {{ (old('genero') == 0) ? 'selected' : '' }} value="0">Femenino</option>
                             <option {{ (old('genero') == 1) ? 'selected' : '' }} value="1">Masculino</option>
                    </select>
                    
                </div>

                <div class="form-group col-xs-3 col-md-6">
                     <label for="promedio">Promedio: <b class="text-danger">*</b></label>
                    <input type="number" class="form-control" id="promedio" name="promedio" value="{{ old('promedio') }}" step="0.01" min="0" max="10" placeholder="Ingresa un promedio" required>

                </div>
            </div>
            <div class="row">
                <div class="form-group col-xs-3 col-md-6">
                     <label for="estatus">Estatus: <b class="text-danger">*</b></label>
                    <select class="form-select" id="estatus" name="estatus" required>
                        <option value="">Selección</option>
                        @foreach($estatus as $e)
                            <option value="{{ $e->ide }}" {{ (old('estatus') == $e->ide) ? 'selected' : '' }}>{{ $e->nombre }}</option>
                        @endforeach
                    </select>
                    
                </div>
                <div style="display: none;" class="form-group grupo col-xs-3 col-md-6">
                   <label for="grupo">Grupo: <b class="text-danger">*</b></label>
                    <select class="form-select" id="grupo" name="grupo">
                        <option value="">Selección</option>
                        @foreach($grupos as $grupo)
                            <option value="{{ $grupo->idgr }}" {{ (old('grupo') == $grupo->idgr) ? 'selected' : '' }}>{{ $grupo->nombre }}</option>
                        @endforeach
                    </select>
                   
                </div>
                <div style="display: none;" class="form-group materiasr col-xs-3 col-md-6">
                     <label for="materiasr">Materias reprobadas: <b class="text-danger">*</b></label>
                    <select class="form-select" id="materiasr" multiple="multiple" name="materiasReprobadas[]" >
                     @foreach($materias as $materia)
                            <option value="{{ $materia->idm }}" {{ (old('materia') == $materia->idm) ? 'selected' : '' }}>{{ $materia->nombre }} - {{ $materia->descripcion }}</option>
                        @endforeach   
                    </select>
                    
                </div>
                <div style="display: none" class="form-group desercion col-xs-3 col-md-6">
                     <label for="desercion">Motivo: <b class="text-danger">*</b></label>
                    <select class="form-select" id="desercion" name="motivoDesercion">
                        <option value="">Selección</option>
                        @foreach($motivos_desercion as $motivo)
                            <option value="{{ $motivo->idem }}" {{ (old('motivoDesercion') == $motivo->idem) ? 'selected' : '' }}>{{ $motivo->motivo }}</option>
                        @endforeach
                    </select>
                    
                </div>
                <div style="display: none" class="form-group reingreso col-xs-3 col-md-6">
                     <label for="reingreso">Motivo: <b class="text-danger">*</b></label>
                    <select class="form-select" id="reingreso" name="motivoReingreso">
                        <option value="">Selección</option>
                        @foreach($motivos_reingreso as $motivo)
                            <option value="{{ $motivo->idem }}" {{ (old('motivoReingreso') == $motivo->idem) ? 'selected' : '' }}>{{ $motivo->motivo }}</option>
                        @endforeach
                    </select>
                    
                </div>

            </div>


            <div class="form-group text-center">
                <button title="Guardar" type="submit" id="submit" class="btn btn-success"><i class="fas fa-user-check"></i></button>
                <a title="Cancelar" href="{{ route('students.index') }}" class="btn btn-danger"><i class="fas fa-ban"></i></a>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $( document ).ready(function() {

        $('#materiasr').select2({
                placeholder: "Selección",
                allowClear: true
            });
    if($('#estatus').val()==1 || $('#estatus').val()==5){
            $('.grupo').css('display','block');
        }else{ 
            $('.grupo').css('display','none');
            
        }
        if($('#estatus').val()==3){
            $('.materiasr').css('display','block');
        }else{
            $('.materiasr').css('display','none');
        }
        if($('#estatus').val()==4){
            $('.desercion').css('display','block');
            
        }else{
            $('.desercion').css('display','none');
        }
        if($('#estatus').val()==5){
            $('.reingreso').css('display','block');
            
        }else{
            $('.reingreso').css('display','none');
        }    
    $('#estatus').change(function(){
        if($(this).val()==1 || $('#estatus').val()==5){
            $('.grupo').css('display','block');
        }else{ 
            $('.grupo').css('display','none');
            
        }
        if($(this).val()==3){
            $('.materiasr').css('display','block');
        }else{
            $('.materiasr').css('display','none');
        }
        if($(this).val()==4){
            $('.desercion').css('display','block');
            
        }else{
            $('.desercion').css('display','none');
        }
        if($(this).val()==5){
            $('.reingreso').css('display','block');
            
        }else{
            $('.reingreso').css('display','none');
        }
    })
});
</script>
@endif
@endsection
