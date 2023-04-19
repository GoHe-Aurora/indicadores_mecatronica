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
@if (Auth()->user()->idtu_tipos_usuarios == 1)
<div class="card">
    <div class="card-header bg-success text-light text-center">
        <h3>A S I G N A C I Ó N  &nbsp;G R U P O S &nbsp;PTC</h3>
    </div>
   
    <div class="card-body">
        <form id="formulario-crear-vae" action="{{ route('asig_grupos_ptc.store') }}" method="POST" enctype="multipart/form-data">
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
                   <label for="grupo">Grupo: <b class="text-danger">*</b></label>
                    <select class="form-select" id="grupo" name="grupo">
                        <option value="">Selección</option>
                        @foreach($grupos as $grupo)
                            <option value="{{ $grupo->idgr }}" {{ (old('grupo') == $grupo->idgr) ? 'selected' : '' }}>{{ $grupo->nombre }}</option>
                        @endforeach
                    </select>
                </div>  
                <div class="form-group col-xs-3 col-md-6">
                   <label for="tutor">Tutor: <b class="text-danger">*</b></label>
                    <select class="form-select" id="tutor" name="tutor">
                        <option value="">Selección</option>
                        @foreach($ptcs as $ptc)
                            <option value="{{ $ptc->idu }}" {{ (old('tutor') == $ptc->idu) ? 'selected' : '' }}>{{ $ptc->nombre }} {{ $ptc->app }} {{ $ptc->apm }}</option>
                        @endforeach
                    </select>
                </div>    

            </div>

            <div class="form-group text-center">
                <button title="Guardar" type="submit" id="submit" class="btn btn-success"><i class="fas fa-user-check"></i></button>
                <a title="Cancelar" href="{{ route('asig_grupos_ptc.index') }}" class="btn btn-danger"><i class="fas fa-ban"></i></a>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $( document ).ready(function() {
        
});
</script>
@endif
@endsection
