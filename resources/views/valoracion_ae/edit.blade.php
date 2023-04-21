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
                <h3>V A L O R A C I Ó N  &nbsp; A E</h3>
            </div>
            <div class="card-body">
                @foreach($alumno as $a)
                    <form id="formulario-actualizar-vae" action="{{ route('valoracion_ae.update', $a->idv) }}" method="POST" enctype="multipart/form-data">
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
                   <label for="grupo_tsu">Grupo TSU: <b class="text-danger">*</b></label>
                    <select class="form-select" id="grupo_tsu" name="grupo_tsu">
                        <option value="">Selección</option>
                        @foreach($grupos_tsu as $grupo)
                            <option value="{{ $grupo->idgr }}" {{ (old('grupo') == $grupo->idgr || $grupo->idgr==$a->grupo_tsu ) ? 'selected' : '' }}>{{ $grupo->nombre }}</option>
                        @endforeach
                    </select>
                </div>    
                <div class="form-group grupo col-xs-3 col-md-6">
                   <label for="grupo_ing">Grupo ING: <b class="text-danger">*</b></label>
                    <select class="form-select" id="grupo_ing" name="grupo_ing">
                        <option value="">Selección</option>
                        @foreach($grupos_ing as $grupo)
                            <option value="{{ $grupo->idgr }}" {{ (old('grupo') == $grupo->idgr || $grupo->idgr==$a->grupo_ing ) ? 'selected' : '' }}>{{ $grupo->nombre }}</option>
                        @endforeach
                    </select>
                </div>  
                
            </div>
            
            <div class="row">
                <div class="col-md-6 col-xs-3 pb-3">
                    <input type="hidden" class="alumno_id" value="{{ $a->ida }}">
                    <label for="alumno">Alumno: <b class="text-danger">*</b></label>
                    <select class="form-select" id="alumno" name="alumno">
                        <option value="">Selección</option>
                        
                    </select>
                    
                </div>
                <div class="form-group col-xs-3 col-md-6">
                     <label for="promedio_tsu">Promedio TSU: <b class="text-danger">*</b></label>
                    <input type="number" class="form-control" id="promedio_tsu" name="promedio_tsu" value="{{ $a->promedio_tsu }}" step="0.01" min="8" max="10" placeholder="Ingresa un promedio" required>

                </div>
            </div>
            <div class="row">
            <div class="form-group col-xs-3 col-md-6">
                     <label for="promedio_ing">Promedio ING: <b class="text-danger">*</b></label>
                    <input type="number" class="form-control" id="promedio_ing" name="promedio_ing" value="{{ $a->promedio_ing }}" step="0.01" min="8" max="10" placeholder="Ingresa un promedio" required>

                </div>
            </div>


                                <div class="form-group text-center">
                                    <button title="Actualizar" type="submit" id="submit" class="btn btn-primary"><i class="fas fa-user-check"></i></button>
                                    <a title="Guardar" href="{{ route('valoracion_ae.index') }}" class="btn btn-danger"><i class="fas fa-ban"></i></a>
                                </div>
                    </form>
                 @endforeach

                 <script>
        $( document ).ready(function() {
            if($('#grupo_tsu').val()!=''){
                studentsByGroup($('#grupo_tsu').val(),2,$('.alumno_id').val());
            }
            $('#grupo_tsu').change(function(){
                $("#alumno").find('option').not(':first').remove();
                if($(this).val()!=''){
                    studentsByGroup($(this).val(),2,$('.alumno_id').val());
                }   
            })
        function studentsByGroup(idg,opc,alumno_id){
            $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
         });
            $.ajax({
               url:'/students/by_group',
               data:{'grupo_id':idg,'opc':opc,'ida':alumno_id},
               type:'post',
               success:  function (response) {
                    $.each(response,function(i,val){
                        $('#alumno').append('<option '+(val.ida==alumno_id ? "selected" : "")+' value="'+val.ida+'">'+val.nombre+' '+val.app+' '+val.apm+'</option>');
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
