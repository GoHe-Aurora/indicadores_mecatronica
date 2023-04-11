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
            <form  action="{{ route('trayectoriac.index') }}" method="GET">
                @csrf
                @method('GET')
            <div class="row">
            <div class="col-sm-4">
                <label for="">Grupo: <strong style="color: red;">*</strong></label>
            </div>
            <div class="col-sm-4">
    
            </div>

            <div class="col-sm-4">
                
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <select class="form-control" name="grupo" id="grupo">
                    <option value="">Selecciona una opción</option>
                @foreach($grupos as $grupo)
                            <option value="{{ $grupo->idgr }}" {{-- $grupo->idgr==$grupo_id ? 'selected' : '' --}}>{{ $grupo->nombre }}</option>
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
                    

            </div>
            <div class="col-sm-4">
               
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
                {{--@foreach ($alumnos as $alumno)--}}
                 @for ($i = 0; $i < 3; $i++)
                <zg-column index='actitud' header='Actitud'  type='text'></zg-column>
                <zg-column index='conocimiento' header='Conocimiento'  type='text'></zg-column>
                <zg-column index='desempeno' header='Desempeño'  type='text'></zg-column>
                @endfor
                {{--@endforeach--}}
                <zg-column align="center" filter ="disabled" index='operaciones' header='Operaciones' type='text'></zg-column>
                
        	<!--/zg-colgroup-->
    	</zing-grid>

	</div>
    @foreach ($alumnos as $alumno)
    <form id="eliminar-tc-{{ $alumno->idtc }}" class="ocultar" action="{{-- route('trayectoriac.delete',$alumno->idtc) --}}" method="GET">
        @csrf
        @method('DELETE')
    </form>
     @endforeach
     <script type="text/javascript">
        
     </script>

@endif
@endsection