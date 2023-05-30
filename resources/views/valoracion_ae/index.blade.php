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
                            <option value="{{ $grupo->idgr }}" {{-- $grupo->idgr==$grupo_tsu_id ? 'selected' : '' --}}>{{ $grupo->nombre }}</option>
                        @endforeach
                </select>  
                <br>
                <button type="submit" class="btn btn-primary mt-1" id="button">Enviar</button>
                <a type="button" class="btn btn-primary mt-1" href="{{ url('/valoracion_ae')}}" >Limpiar
             <!--button type="button" class="btn btn-primary mt-1" id="limpiar">Limpiar</button-->
                 <!--button id="btn_exportar_excel" type="button" class="btn btn-success mt-1">
                Exportar a EXCEL
            </button-->
                <a style="margin-left: 5px;" type="button" class="btn btn-success mt-1" href="{{route('valoracion_ae.create')}}"><i class="fas fa-user-plus"></i></a>
            </div>
            <div class="col-sm-3">
                <select class="form-control" name="materia" id="materia">
                    <option value="">Selecciona una opción</option>
                @foreach($materias as $materia)
                            <option value="{{ $materia->idm }}" {{-- $grupo->idgr==$grupo_tsu_id ? 'selected' : '' --}}>{{ $materia->descripcion }} {{ $materia->nombre }}</option>
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
                <zg-column index='puntuacion' header='Puntuación'  type='text'></zg-column>
                <zg-column align="center" filter ="disabled" index='operaciones' header='Operaciones' type='text'></zg-column>
                
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
        
     </script>

@endif
@endsection
