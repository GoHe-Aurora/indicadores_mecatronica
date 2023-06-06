@extends('layout.layout')
@section('content')
    @section('header')

        <script src='{{asset('src/js/zinggrid.min.js')}}'></script>
        <script src='{{asset('src/js/zinggrid-es.js')}}'></script>
        <script>
        if (es) ZingGrid.registerLanguage(es, 'custom');
        </script>
    @endsection
    @if (Auth()->user()->idtu_tipos_usuarios == 1 || Auth()->user()->idtu_tipos_usuarios == 2)
    <div class="card">
        <div class="card-header">
            @if (Session::has('mensaje'))
                <div class="alert alert-success">
                    <strong>{{ Session::get('mensaje') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                </div>
            @endif
            <!--div class="row">
                <div class="col-sm-11">
                    <h2 align="center">Listas</h2>
                    <a href="{{url('students/create')}}"><button class="btn btn-success"><i class="fas fa-user-alt"></i></button></a>
                </div>
            </div-->
            <form  action="{{ route('valoracion_ae.index') }}" method="GET">
                @csrf
                @method('GET')
            <div class="row">
            <div class="col-sm-3">
                <label for="">Grupos: <strong style="color: red;"></strong></label>
            </div>
            <div class="col-sm-3">
               <label for="">Materias: <strong style="color: red;"></strong></label>
            </div>
            <div class="col-sm-3">
               <label for="">Atributos: <strong style="color: red;"></strong></label>  
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <select class="form-control" name="grupo" id="grupo">
                    <option value="">Selecciona una opción</option>
                @foreach($grupos as $grupo)
                            <option value="{{ $grupo->idgr }}" {{ $grupo->idgr==$grupo_id ? 'selected' : '' }}>{{ $grupo->nombre }}</option>
                        @endforeach
                </select>  
                <br>
                <button type="submit" class="btn btn-primary mt-1" id="button">Enviar</button>
                <a type="button" class="btn btn-primary mt-1" href="{{ url('/valoracion_ae')}}" >Limpiar</a>
             <!--button type="button" class="btn btn-primary mt-1" id="limpiar">Limpiar</button-->
                 <!--button id="btn_exportar_excel" type="button" class="btn btn-success mt-1">
                Exportar a EXCEL
            </button-->
                <!--a style="margin-left: 5px;" type="button" class="btn btn-success mt-1" href="{{route('valoracion_ae.create')}}"><i class="fas fa-user-plus"></i></a-->
            </div>
            <div class="col-sm-3">
                <select class="form-control" name="materia" id="materia">
                    <option value="">Selecciona una opción</option>
                @foreach($materias as $materia)
                            <option value="{{ $materia->idm }}" {{ $materia->idm==$materia_id ? 'selected' : '' }}>{{ $materia->descripcion }} {{ $materia->nombre }}</option>
                        @endforeach
                </select>     

            </div>
            <div class="col-sm-3">
             <select class="form-control" name="atributo" id="atributo">
                 <option value="">Selecciona una opción</option>
             </select>  
         </div>
        </form>
        </div>
        </div>
    </div>

     <div class="card-body">
     
    	<zing-grid
        	lang="custom"
        	caption='Valoración de Aprovechamiento Escolar'
        	sort
        	search
        	pager
        	page-size='10'
        	page-size-options='10,15,20,25,30'
        	layout='row'
        	viewport-stop
        	theme='android'
        	id='zing-grid'
        	filter
            selector
            data="{{ $json }}">
        	<!--zg-colgroup-->
                <zg-column index='nombre' header='Nombre'  type='text'></zg-column>
                <zg-column index='app' header='Apellido Paterno'  type='text'></zg-column>
                <zg-column index='apm' header='Apellido Materno'  type='text'></zg-column>
                <zg-column index='lo_supera' header='Lo Supera'  type='text'></zg-column>
                <zg-column index='lo_logra' header='Lo Logra'  type='text'></zg-column>
                <zg-column index='lo_logra_parcialmente' header='Lo Logra Parcialmente'  type='text'></zg-column>
                <zg-column index='no_lo_logra' header='No Lo Logra'  type='text'></zg-column>
                <!--zg-column align="center" filter ="disabled" index='operaciones' header='Operaciones' type='text'></zg-column-->
                
        	<!--/zg-colgroup-->
    	</zing-grid>

	</div>
    {{--@foreach ($alumnos as $alumno)
    <form id="eliminar-vae-{{ $alumno->idv }}" class="ocultar" action="{{ route('valoracion_ae.delete',$alumno->idv) }}" method="GET">
        @csrf
        @method('DELETE')
    </form>
     @endforeach--}}
     <script type="text/javascript">
         $( document ).ready(function() {
       $('.ck').on('change',function() {
        if($(this).attr('value')==1){
            $(".ck[value='1']").prop('checked', true);
            $(".ck[value='2']").prop('checked', false);
            $(".ck[value='3']").prop('checked', false);
            $(".ck[value='4']").prop('checked', false); 
        }else if($(this).attr('value')==2){
            $(".ck[value='2']").prop('checked', true);
            $(".ck[value='1']").prop('checked', false);
            $(".ck[value='3']").prop('checked', false);
            $(".ck[value='4']").prop('checked', false); 
        }else if($(this).attr('value')==3){
            $(".ck[value='3']").prop('checked', true);
            $(".ck[value='1']").prop('checked', false);
            $(".ck[value='2']").prop('checked', false);
            $(".ck[value='4']").prop('checked', false); 
        }else if($(this).attr('value')==4){
            $(".ck[value='4']").prop('checked', true);
            $(".ck[value='1']").prop('checked', false);
            $(".ck[value='2']").prop('checked', false);
            $(".ck[value='3']").prop('checked', false); 
        }
           agrega_atributo($('#atributo').val(),$(this).data('alumno'),$(this).val());
        })
        if($('#materia').val()!=''){
            atributos($('#materia').val());
        }
        $('#materia').change(function(){
            atributos($(this).val());
        })
        function agrega_atributo(atributo,alumno,val){
            $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
         });
            $.ajax({
               url:'/valoracion_ae/agrega_atributo/',
               data:{'atributo':atributo,'alumno':alumno,'val':val},
               type:'post',
               dataType:'json',
               success:  function (response) {
                  alert(response);
               },
               
             });
        } 
    function atributos(materia){
            $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
         });
            $.ajax({
               url:'/valoracion_ae/atributos/'+materia,
               //data:{'materia':materia},
               type:'get',
               dataType:'json',
               success:  function (response) {
                $('#atributo').empty();
                    $.each(response,function(i,val){
            $('#atributo').append('<option value="'+val.idae+'">'+val.idae+' '+val.descripcion+'</option>');
                    })  
               },
               
             });
        }  
        }) 
     </script>

@endif
@endsection
