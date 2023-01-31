@extends('layout.layout')
@section('content')
    @section('header')
        <script src='{{asset('src/js/zinggrid.min.js')}}'></script>
        <script src='{{asset('src/js/zinggrid-es.js')}}'></script>
        <script>
        if (es) ZingGrid.registerLanguage(es, 'custom');
        </script>
    @endsection
@if (Auth()->user()->idtu_tipos_usuarios == 1)
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
            <div class="row">
            <div class="col-sm-4">
                <label for="">Fecha orden: <strong style="color: red;">*</strong></label>
            </div>
            <div class="col-sm-4">
                <label for="">Fecha Inicio: <strong style="color: red;">*</strong></label>
            </div>

            <div class="col-sm-4">
                <label for="">Fecha Fin: <strong style="color: red;">*</strong></label>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <select class="form-control" name="fecha_orden" id="fecha_orden">
                    <option value="0">Todos los registros</option>
                    <option value="1">Fecha inicio</option>
                    <option value="2">Fecha fin</option>
                </select>
                <br>
                <button type="button" class="btn btn-primary mt-1" id="button">Enviar</button> <button type="button" class="btn btn-primary mt-1" id="limpiar">Limpiar</button> <button id="btn_exportar_excel" type="button" class="btn btn-success mt-1">
                Exportar a EXCEL
            </button>
            </div>
            <div class="col-sm-4">
                <input class="form-control" name="fechaIni" id="fechaIni" type="date" readonly="">

            </div>
            <div class="col-sm-4">
                <input class="form-control" name="fechaFin" id="fechaFin" type="date" readonly="">
            </div>
        </div>
        </div>
    </div>

     <div class="card-body">

    	<zing-grid
        	lang="custom"
        	caption='Lista de alumnos'
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
                <zg-column index='estatus' header='Estatus'  type='text'></zg-column>
        	<!--/zg-colgroup-->
    	</zing-grid>

	</div>
    {{--@foreach ($usuarios as $user)
    <form id="eliminar-usuario-{{ $user->idu }}" class="ocultar" action="{{ route('users.destroy', ['user' => $user->idu]) }}" method="POST">
        @csrf
        @method('DELETE')
    </form>
     @endforeach--}}

@endif
@endsection
