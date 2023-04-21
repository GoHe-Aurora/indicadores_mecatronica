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
            <form  action="{{ route('students.index') }}" method="GET">
                @csrf
                @method('GET')
            <div class="row">
            <div class="col-sm-3">
                <label for="">Generación Inicio: <strong style="color: red;">*</strong></label>
            </div>
            <div class="col-sm-3">
                <label for="">Generación Fin: <strong style="color: red;">*</strong></label>
            </div>

            <div class="col-sm-4">
                
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <select class="form-control" name="fechaIni" id="fechaIni">
                @foreach($years as $year)
                            <option value="{{ $year }}" {{ ($year == $fechaIni) ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                </select>  
                <br>
                <button type="submit" class="btn btn-primary mt-1" id="button">Enviar</button>
                <a type="button" class="btn btn-primary mt-1" href="{{ url('/students')}}" >Limpiar
             <!--button type="button" class="btn btn-primary mt-1" id="limpiar">Limpiar</button-->
                 <!--button id="btn_exportar_excel" type="button" class="btn btn-success mt-1">
                Exportar a EXCEL
            </button-->
                <a style="margin-left: 5px;" type="button" class="btn btn-success mt-1" href="{{route('students.create')}}"><i class="fas fa-user-plus"></i></a>
            </div>
            <div class="col-sm-3">
                 <select class="form-control" name="fechaFin" id="fechaFin">
                @foreach($years as $year)
                            <option value="{{ $year }}" {{ ($year == $fechaFin) ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                </select>      

            </div>
            <div class="col-sm-2">
               <input type="file" class="form-control file">
               <select class="form-control" name="grupo" id="grupo">
                <option value="">Seleccina una opción</option>
                @foreach($grupos as $grupo)
                            <option value="{{ $grupo->idgr }}">{{ $grupo->nombre }}</option>
                        @endforeach
                </select> 
               
         </div>
        <div class="col-sm-2">
            <a class="upload" href="#">Subir</a>
        </div>
         
        </form>
        </div>
        </div>
    </div>

     <div class="card-body">
     
    	<zing-grid
        	lang="custom"
        	caption='Lista de Alumnos'
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
                <zg-column index='matricula' header='Matrícula'  type='text'></zg-column>
                <zg-column index='genero' header='Género'  type='text'></zg-column>
                <zg-column index='grupo' header='Grupo'  type='text'></zg-column>
                <zg-column index='estatus' header='Estatus'  type='text'></zg-column>
                <zg-column index='motivo' header='Motivo'  type='text'></zg-column>
                <zg-column index='promedio' header='Promedio'  type='text'></zg-column>
                <zg-column index='activo' header='Activo' type='text'></zg-column>
                <zg-column align="center" filter ="disabled" index='operaciones' header='Operaciones' type='text'></zg-column>
                
        	<!--/zg-colgroup-->
    	</zing-grid>

	</div>
    @foreach ($alumnos as $alumno)
    <form id="activar-alumno-{{ $alumno->ida }}" class="ocultar" action="{{ route('students.activar',$alumno->ida) }}" method="GET">
        @csrf
        @method('DELETE')
    </form>
    <form id="desactivar-alumno-{{ $alumno->ida }}" class="ocultar" action="{{ route('students.desactivar',$alumno->ida) }}" method="GET">
        @csrf
        @method('DELETE')
    </form>
     @endforeach
     <script type="text/javascript">
        $('.upload').click(function() {
            if($('.file').prop("files")[0] && $('#grupo').val()!=''){
              upload($('.file').prop("files")[0],$('#grupo').val());  
          }else if(!$('.file').prop("files")[0]){
             alert('Debes de seleccionar un archivo !!');
          }else if($('#grupo').val()==''){
             alert('Debes de seleccionar un grupo !!');
          }
         })
        function upload(file,grupo){
            $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
         });
            var fd = new FormData();
            fd.append('file',file);
            fd.append('grupo',grupo);
            $.ajax({
               url:'/students/import',
               data:fd,
               type:'post',
               contentType: false,
               processData: false,
               dataType: 'json',
               success:  function (data) {
                    var url = "{{ route('students.index') }}";
                    window.location = url;
                    alert(data);
               },
               
             });
        }
     </script>

@endif
@endsection
