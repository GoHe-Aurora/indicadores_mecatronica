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
               <h3>S I S T E M A &nbsp; D E &nbsp; C A L I F I C A C I O N E S &nbsp; C U A T R I M E S T R A L</h3>
            </div>
            <div class="card-body">
                @foreach($tc as $t)
                    <form id="formulario-actualizar-tc" action="{{ route('trayectoriac.update', $t->alumno_id) }}" method="POST" enctype="multipart/form-data">
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
                    <select class="form-select" id="grupo" name="grupo" disabled>
                        <option value="">Selección</option>
                        @foreach($grupos as $grupo)
                            <option value="{{ $grupo->idgr }}" {{ (old('grupo') == $grupo->idgr || $grupo->idgr==$t->grupo_id) ? 'selected' : '' }}>{{ $grupo->nombre }}</option>
                        @endforeach
                    </select>
                </div>    

                <div class="col-md-6 col-xs-3 pb-3">
                    <label for="alumno">Alumno: <b class="text-danger">*</b></label>
                    <input id="alu" name="alumno" value="{{$t->alumno_id}}" type="hidden">
                    <select class="form-select" id="alumno" disabled>
                        <option value="">Selección</option>
                        
                    </select>
                    
                </div>
            </div>
            
            <div class="row">
                
                <div class="form-group col-xs-3 col-md-6">
                     <label for="materia">Materia: <b class="text-danger">*</b></label>
                     <input name="materia" value="{{$t->materia_id}}" type="hidden">
                    <select class="form-select" id="materia" disabled>
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
                   <div class="row">
                     <div class="col-md-6">   
                    <input type="number" class="form-control" id="responsabilidad" name="responsabilidad" value="{{ $t->responsabilidad }}" min="1" max="10" placeholder="Responsabilidad">
                     </div>
                     <div class="col-md-6">
                    <input type="number" class="form-control" id="colaborativo" name="colaborativo" value="{{ $t->colaborativo }}" min="1" max="10" placeholder="Colaborativo"> 
                </div>
                 </div><br>
                 <div class="row">
                     <div class="col-md-6">   
                    <input type="number" class="form-control" id="relaciones_i" name="relaciones_i" value="{{ $t->relaciones_i }}" min="1" max="10" placeholder="Relaciones Interpersonales">
                     </div>
                     <div class="col-md-6">
                    <input type="number" class="form-control" id="creatividad" name="creatividad" value="{{ $t->creatividad }}" min="1" max="10" placeholder="Creatividad"> 
                </div>
                 </div>

                </div>
                <div class="form-group col-xs-3 col-md-6">
                     <label for="conocimiento">Conocimiento: <b class="text-danger">*</b></label>
                   <div class="row">
                     <div class="col-md-6">   
                    <input type="number" class="form-control" id="marco_tc" name="marco_tc" value="{{ $t->marco_tc }}" min="1" max="10" placeholder="Marco teórico y conceptual">
                     </div>
                     <div class="col-md-6">
                    <input type="number" class="form-control" id="manejo_inf" name="manejo_inf" value="{{ $t->manejo_inf }}" min="1" max="10" placeholder="Manejo de información"> 
                </div>
                 </div>

                </div>
                
            </div>
            <div class="row">
            <div class="form-group col-xs-3 col-md-6">
                     <label for="desempeno">Desempeño: <b class="text-danger">*</b></label>
                    <div class="row">
                     <div class="col-md-6">   
                    <input type="number" class="form-control" id="practicas" name="practicas" value="{{ $t->practicas }}" min="1" max="10" placeholder="Prácticas">
                     </div>
                     <div class="col-md-6">
                    <input type="number" class="form-control" id="estudios_caso" name="estudios_caso" value="{{ $t->estudios_caso }}" min="1" max="10" placeholder="Estudios de caso"> 
                </div>
                 </div>
                 <br>
                 <div class="row">
                     <div class="col-md-6">   
                    <input type="number" class="form-control" id="proyecto" name="proyecto" value="{{ $t->proyecto }}" min="1" max="10" placeholder="Proyecto">
                     </div>
                     <div class="col-md-6">
                    <input type="number" class="form-control" id="ejercicios" name="ejercicios" value="{{ $t->ejercicios }}" min="1" max="10" placeholder="Ejercicios"> 
                </div>
                 </div><br>
                 <div class="row">
                     <div class="col-md-6">   
                    <input type="number" class="form-control" id="ensayo" name="ensayo" value="{{ $t->ensayo }}" min="1" max="10" placeholder="Ensayo">
                     </div>
                     <div class="col-md-6">
                    
                </div>
                 </div>
             </div>
             <div class="form-group col-xs-3 col-md-6">
                    <div class="row">
                    <div class="col-md-6"> 
                    <label for="calificacion">Calificación: <b class="text-danger">*</b></label>  
                    <input type="number" class="form-control" id="calificacion" name="calificacion" value="{{ $t->calificacion }}" step="0.01" placeholder="Sin redondear" required readonly>
                    </div>
                    <div class="col-md-6">
                    <label for="calificacion">Calificación Acta: <b class="text-danger">*</b></label>  
                    <input type="number" class="form-control" id="calificacion_acta" name="calificacion_acta" value="{{ $t->calificacion_acta }}" placeholder="Redondeada" required readonly> 
                    <input type="hidden" id="actitud" name="actitud">
                    <input type="hidden" id="conocimiento" name="conocimiento">
                    <input type="hidden" id="desempeno" name="desempeno">
                </div>
                 </div>
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
            
            $('#unidad').change(function(){
               atributos($(this).val(),$('#alumno').val());
            })
            if($('#materia').val()!=''){
                unidades($('#materia').val(),$('.unidad').val(),2);
            }
            $('#materia').change(function(){
                $("#unidad").find('option').not(':first').remove();
                if($(this).val()!=''){
                    unidades($(this).val(),2);
                }   
            })
    studentsByGroup($('#grupo').val(),2,$('#alu').val());        
    $('#grupo').change(function(){
                $("#alumno").find('option').not(':first').remove();
                if($(this).val()!=''){
                    studentsByGroup($(this).val(),1);
                }   
            })
    $('#responsabilidad,#colaborativo,#relaciones_i,#creatividad,#marco_tc,#manejo_inf,#practicas,#estudios_caso,#proyecto,#ejercicios,#ensayo').keyup(function(e){
                a=0;
                c=0;
                d=0;
                n=0;
                if($('#responsabilidad').val()!=''){a++;} 
                if($('#colaborativo').val()!=''){a++;}
                if($('#relaciones_i').val()!=''){a++;}
                if($('#creatividad').val()!=''){a++;}
                if($('#marco_tc').val()!=''){c++;} 
                if($('#manejo_inf').val()!=''){c++;}
                if($('#practicas').val()!=''){d++;} 
                if($('#estudios_caso').val()!=''){d++;}
                if($('#proyecto').val()!=''){d++;} 
                if($('#ejercicios').val()!=''){d++;}
                if($('#ensayo').val()!=''){d++;}
                re = $('#responsabilidad').val()!='' ? parseFloat($('#responsabilidad').val()) : 0;
                co = $('#colaborativo').val()!='' ? parseFloat($('#colaborativo').val()) : 0;
                ri = $('#relaciones_i').val()!='' ? parseFloat($('#relaciones_i').val()) : 0;
                cr = $('#creatividad').val()!='' ? parseFloat($('#creatividad').val()) : 0;
                let actitud = a!=0 ? (re+co+ri+cr)/a : 0; 
                mt = $('#marco_tc').val()!='' ? parseFloat($('#marco_tc').val()) : 0;
                mi = $('#manejo_inf').val()!='' ? parseFloat($('#manejo_inf').val()) : 0;
                let conocimiento = c!=0 ? (mt+mi)/c : 0;
                pr = $('#practicas').val()!='' ? parseFloat($('#practicas').val()) : 0;
                ec = $('#estudios_caso').val()!='' ? parseFloat($('#estudios_caso').val()) : 0;
                pro = $('#proyecto').val()!='' ? parseFloat($('#proyecto').val()) : 0;
                ej = $('#ejercicios').val()!='' ? parseFloat($('#ejercicios').val()) : 0;
                en = $('#ensayo').val()!='' ? parseFloat($('#ensayo').val()) : 0;
                let desempeno = d!=0 ? (pr+ec+pro+ej+en)/d : 0;
                a1 = (actitud*20)/10;
                c1 = (conocimiento*30)/10;
                d1 = (desempeno*50)/10;
                if(a1!=0){n++;}
                if(c1!=0){n++;}
                if(d1!=0){n++;}
                $('#actitud').val(actitud);
                $('#conocimiento').val(conocimiento);
                $('#desempeno').val(desempeno);
                let porcentaje = (a1+c1+d1);
                let calif = (porcentaje*10)/100;
                $('#calificacion').val(calif.toFixed(2)); 
                $('#calificacion_acta').val(Math.round(calif)); 
            })
    function atributos(unidad,alumno_id){
            $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
         });
            $.ajax({
               url:'/trayectoriac/atributos',
               data:{'unidad':unidad,'ida':alumno_id},
               type:'post',
               dataType: "json",
               success:  function (response) {
                    $('#responsabilidad').val(response[0]?response[0].responsabilidad : ''); 
                    $('#colaborativo').val(response[0]?response[0].colaborativo : '');
                    $('#relaciones_i').val(response[0]?response[0].relaciones_i : '');
                    $('#creatividad').val(response[0]?response[0].creatividad : '');
                    $('#manejo_inf').val(response[0]?response[0].manejo_inf : '');
                    $('#marco_tc').val(response[0]?response[0].marco_tc : '');
                    $('#practicas').val(response[0]?response[0].practicas : '');
                    $('#estudios_caso').val(response[0]?response[0].estudios_caso : '');
                    $('#proyecto').val(response[0]?response[0].proyecto : '');
                    $('#ejercicios').val(response[0]?response[0].ejercicios : '');
                    $('#ensayo').val(response[0]?response[0].ensayo : '');
                    $('#calificacion').val(response[0]?response[0].calificacion : '');
                    $('#calificacion_acta').val(response[0]?response[0].calificacion_acta : '');
               },
               
             });
        }   
        function studentsByGroup(idg,opc,alumno){
            $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
         });
            $.ajax({
               url:'/students/by_group',
               data:{'grupo_id':idg,'opc':opc,'ida':alumno},
               type:'post',
               success:  function (response) {
                    $.each(response,function(i,val){
                        $('#alumno').append('<option '+(alumno==val.ida ? 'selected' : '')+' value="'+val.ida+'">'+val.nombre+' '+val.app+' '+val.apm+'</option>');
                    })  
               },
               
             });
        }   
        function unidades(materia,unidad,opc){
            $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
         });
            $.ajax({
               url:'/trayectoriac/unidades',
               data:{'materia':materia,'unidad':unidad,'opc':opc},
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
