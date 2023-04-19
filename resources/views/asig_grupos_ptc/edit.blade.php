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
            <div class="card-header bg-success text-light" style="text-align: center;">
                <h3>A S I G N A C I Ó N  &nbsp;G R U P O S &nbsp;PTC</h3>
            </div>
            <div class="card-body">
                @foreach($asignacion as $a)
                    <form id="formulario-actualizar-asig-gruposptc" action="{{ route('asig_grupos_ptc.update', $a->idgr) }}" method="POST" enctype="multipart/form-data">
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
                            <option value="{{ $grupo->idgr }}" {{ (old('grupo') == $grupo->idgr || $grupo->idgr==$a->idgr ) ? 'selected' : '' }}>{{ $grupo->nombre }}</option>
                        @endforeach
                    </select>
                </div>    

                <div class="col-md-6 col-xs-3 pb-3">
                    <label for="tutor">Tutor: <b class="text-danger">*</b></label>
                    <select class="form-select" id="tutor" name="tutor">
                        <option value="">Selección</option>
                        @foreach($ptcs as $ptc)
                            <option value="{{ $ptc->idu }}" {{ (old('tutor') == $ptc->idu || $ptc->idu==$a->user_id ) ? 'selected' : '' }}>{{ $ptc->app }} {{ $ptc->apm }} {{ $ptc->nombre }}</option>
                        @endforeach
                    </select>
                    
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
            if($('#grupo').val()!=''){
                studentsByGroup($('#grupo').val(),2,$('.alumno_id').val());
            }
            $('#grupo').change(function(){
                $("#alumno").find('option').not(':first').remove();
                if($(this).val()!=''){
                    studentsByGroup($(this).val(),1,);
                }   
            })
        function studentsByGroup(idg,opc,ida){
            $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
            $.ajax({
               url:'/students/by_group',
               data:{'grupo_id':idg,'opc':opc,'ida':ida},
               type:'post',
               success:  function (response) {
                    $.each(response,function(i,val){
                        $('#alumno').append('<option '+(ida==val.ida ? "selected" : "")+' value="'+val.ida+'">'+val.nombre+' '+val.app+' '+val.apm+'</option>');
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
