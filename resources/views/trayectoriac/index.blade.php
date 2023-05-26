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
            <input type="hidden" class="unidad" value="{{$length}}">
            <form  action="{{ route('trayectoriac.index') }}" method="GET">
                @csrf
                @method('GET')
            <div class="row">
            <div class="col-sm-4">
                <label for="">Grupo: <strong style="color: red;"></strong></label>
            </div>
            <div class="col-sm-4">
                <label for="">Materia: <strong style="color: red;">*</strong></label>
            </div>

            <div class="col-sm-4">
                
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <select class="form-control" name="grupo" id="grupo">
                    <option value="">Selecciona una opción</option>
                @foreach($grupos as $grupo)
                            <option value="{{ $grupo->idgr }}" {{ $grupo->idgr==$grupo_id ? 'selected' : '' }}>{{ $grupo->nombre }}</option>
                        @endforeach
                </select>  
                <br>
                <button type="submit" class="btn btn-primary mt-1" id="button">Enviar</button>
                <a type="button" class="btn btn-primary mt-1" href="{{ url('/trayectoriac')}}" >Limpiar
             <!--button type="button" class="btn btn-primary mt-1" id="limpiar">Limpiar</button-->
                 <!--button id="btn_exportar_excel" type="button" class="btn btn-success mt-1">
                Exportar a EXCEL
            </button-->
                <a style="margin-left: 5px;" type="button" class="btn btn-success mt-1" href="{{route('trayectoriac.create')}}"><i class="fas fa-user-plus"></i></a>
            </div>
            <div class="col-sm-4">
                 <select class="form-control" name="materia" id="materia">
                    <option value="">Selecciona una opción</option>
                @foreach($materias as $materia)
                    <option value="{{ $materia->idm }}" {{ $materia->idm==$mat ? 'selected' : '' }}>{{ $materia->nombre }} {{ $materia->descripcion }}</option>
                @endforeach
                </select>   
            </div>
            <div class="col-sm-4">
               <a class="reporte" href="#">Reporte</a>
         </div>
        </form>
        </div>
        </div>
    </div>

     <div class="card-body">
    	<zing-grid
        	lang="custom"
        	caption='Sistema de Calificaciones Cuatrimestral'
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
                <zg-column index='nombre' header='Nombre' type='text'></zg-column>
                <zg-column index='app' header='Apellido Paterno' type='text'></zg-column>
                <zg-column index='apm' header='Apellido Materno' type='text'></zg-column>
                <script>
                 for (var i = 1; i <= $('.unidad').val(); i++) {
                $('zg-column:last').after("<zg-column index='' header='Unidad "+i+"' type='text'></zg-column><zg-column index='actitud"+i+"' header='Actitud' type='text'></zg-column><zg-column index='conocimiento"+i+"' header='Conocimiento' type='text'></zg-column><zg-column index='desempeno"+i+"' header='Desempeño' type='text'></zg-column>");
                if(i==$('.unidad').val()){
                   $('zg-column:last').after("<zg-column index='calificacion' header='Calificación' type='text'></zg-column><zg-column index='calificacion_acta' header='Calificación Acta' type='text'></zg-column>"); 
                } 
                
                }  
                </script>
                
                
                <zg-column align="center" filter ="disabled" index='operaciones' header='Operaciones' type='text'></zg-column>
        	
    	</zing-grid>

	</div>
    @foreach ($alumnos as $alumno)
    <form id="eliminar-tc-{{ $alumno->idtc }}" class="ocultar" action="{{ route('trayectoriac.delete',$alumno->idtc) }}" method="GET">
        @csrf
        @method('DELETE')
    </form>
     @endforeach
     <script type="text/javascript">
        $('.reporte').click(function(e){
            e.preventDefault();
            if($('#grupo').val()!='' && $('#materia').val()!=''){
               reporte_pdf($('#grupo').val(), $('#materia').val());
            }else if($('#grupo').val()==''){
               alert("Selecciona un grupo");
            }else if($('#materia').val()==''){
               alert("Selecciona una materia");
            }
        })
        function reporte_pdf(grupo,materia){
            $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
         });
            $.ajax({
               url:'/trayectoriac/reporte_pdf',
               data:{'grupo':grupo,'materia':materia},
               type:'post',
               xhrFields: {
                responseType: 'blob'
            },
            success: function(response){
               var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "Trayectoria_Cuatrimestral_"+materia+"_"+grupo+".xlsx";
                link.click();
            },
            error: function(blob){
                //console.log(blob);
            }
               
             });
        }
     </script>

@endif
@endsection
