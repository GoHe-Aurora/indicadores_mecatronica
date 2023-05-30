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
        <h3>R E S U L T A D O S &nbsp; D E &nbsp; E G E L &nbsp; Y &nbsp; E C G</h3>
    </div>
   
    <div class="card-body">
        <form id="formulario-crear-egel_ecg" action="{{ route('egel_ecg.store') }}" method="POST" enctype="multipart/form-data">
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
                   <label for="grupo_tsu">Grupo TSU: <b class="text-danger">*</b></label>
                    <select class="form-select" id="grupo_tsu" name="grupo_tsu">
                        <option value="">Selección</option>
                        @foreach($grupos_tsu as $grupo)
                            <option value="{{ $grupo->idgr }}" {{ (old('grupo') == $grupo->idgr) ? 'selected' : '' }}>{{ $grupo->nombre }}</option>
                        @endforeach
                    </select>
                </div> 
                 <div class="form-group col-xs-3 col-md-6">
                   <label for="grupo_ing">Grupo ING: <b class="text-danger">*</b></label>
                    <select class="form-select" id="grupo_ing" name="grupo_ing">
                        <option value="">Selección</option>
                        @foreach($grupos_ing as $grupo)
                            <option value="{{ $grupo->idgr }}" {{ (old('grupo') == $grupo->idgr) ? 'selected' : '' }}>{{ $grupo->nombre }}</option>
                        @endforeach
                    </select>
                </div>     

            </div>
            
            <div class="row">
                <div class="col-md-6 col-xs-3 pb-3">
                    <label for="alumno">Alumno: <b class="text-danger">*</b></label>
                    <select class="form-select" id="alumno" name="alumno">
                        <option value="">Selección</option>
                        
                    </select>
                    
                </div>
                <div class="form-group col-xs-3 col-md-6">
                     <label for="promedio_ing">Promedio TSU: <b class="text-danger">*</b></label>
                    <input type="number" class="form-control" id="promedio_tsu" name="promedio_tsu" value="{{ old('promedio_tsu') }}" step="0.01" min="0" max="10" placeholder="Ingresa un promedio" required>

                </div>
            </div>
            <div class="row">
                <div class="form-group col-xs-3 col-md-6">
                     <label for="promedio_tsu">Promedio ING: <b class="text-danger">*</b></label>
                    <input type="number" class="form-control" id="promedio_ing" name="promedio_ing" value="{{ old('promedio_ing') }}" step="0.01" min="0" max="10" placeholder="Ingresa un promedio" required>

                </div>
            </div>

            <div class="form-group text-center">
                <button title="Guardar" type="submit" id="submit" class="btn btn-success"><i class="fas fa-user-check"></i></button>
                <a title="Cancelar" href="{{ route('egel_ecg.index') }}" class="btn btn-danger"><i class="fas fa-ban"></i></a>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $( document ).ready(function() {
    $('#grupo_tsu').change(function(){
                $("#alumno").find('option').not(':first').remove();
                if($(this).val()!=''){
                    studentsByGroup($(this).val(),1);
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
});
</script>
@endif
@endsection
