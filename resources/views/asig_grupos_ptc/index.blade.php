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
            <form  action="{{ route('asig_grupos_ptc.index') }}" method="GET">
                @csrf
                @method('GET')
            <div class="row">
            <div class="col-sm-4">
                <label for="">Profesor PTC: <strong style="color: red;"></strong></label>
            </div>
            <div class="col-sm-4">
                <label for="">Grupo: <strong style="color: red;"></strong></label>
            </div>

            <div class="col-sm-4">
                
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <select class="form-control" name="ptc" id="ptc">
                    <option value="">Selecciona una opción</option>
                @foreach($ptcs as $ptc)
                            <option value="{{ $ptc->idu }}" >{{ $ptc->nombre }} {{ $ptc->app }} {{ $ptc->apm }}</option>
                        @endforeach
                </select>  
                <br>
                <button type="submit" class="btn btn-primary mt-1" id="button">Enviar</button>
                <a type="button" class="btn btn-primary mt-1" href="{{ url('/asig_grupos_ptc')}}" >Limpiar
             <!--button type="button" class="btn btn-primary mt-1" id="limpiar">Limpiar</button-->
                 <!--button id="btn_exportar_excel" type="button" class="btn btn-success mt-1">
                Exportar a EXCEL
            </button-->
                <a style="margin-left: 5px;" type="button" class="btn btn-success mt-1" href="{{route('asig_grupos_ptc.create')}}"><i class="fas fa-user-plus"></i></a>
            </div>
            <div class="col-sm-4">
                <select class="form-control" name="grupo" id="grupo">
                    <option value="">Selecciona una opción</option>
                @foreach($grupos as $grupo)
                            <option value="{{ $grupo->idgr }}" {{ $grupo->idgr==$grupo_id ? 'selected' : ''}}>{{ $grupo->nombre }}</option>
                        @endforeach
                </select>   

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
        	caption="Asignación de grupos a PTC'S"
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
                <zg-column index='grupo' header='Grupo'  type='text'></zg-column>
                <zg-column index='tutor' header='Tutor'  type='text'></zg-column>
                <zg-column align="center" filter ="disabled" index='operaciones' header='Operaciones' type='text'></zg-column>
                
        	<!--/zg-colgroup-->
    	</zing-grid>

	</div>
    @foreach ($grupos as $grupo)
    <form id="eliminar-grupoptc-{{ $grupo->idgr }}" class="ocultar" action="{{ route('asig_grupos_ptc.delete',$grupo->idgr) }}" method="GET">
        @csrf
        @method('DELETE')
    </form>
     @endforeach
     <script type="text/javascript">
        
     </script>

@endif
@endsection
