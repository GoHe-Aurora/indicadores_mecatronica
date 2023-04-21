@extends('layout.layout')
@section('content')
    @section('header')

        <script src='{{asset('src/js/zinggrid.min.js')}}'></script>
        <script src='{{asset('src/js/zinggrid-es.js')}}'></script>
        <script>
        if (es) ZingGrid.registerLanguage(es, 'custom');
        </script>
    @endsection
{{--@if (Auth()->user()->idtu_tipos_usuarios == 1 || Auth()->user()->idtu_tipos_usuarios == 2)--}}
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
            <form  action="{{ route('asesorias.index') }}" method="GET">
                @csrf
                @method('GET')
            <div class="row">
            <div class="col-sm-3">
                <label for="">Grupo: <strong style="color: red;">*</strong></label>
            </div>
            <div class="col-sm-3">
                <label for="">Fecha: <strong style="color: red;">*</strong></label>
            </div>

            <div class="col-sm-3">
                <label for="">Evidencia: </label>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <select class="form-control" name="grupo">
                    <option value="" >Selección</option>
                        @foreach($grupos as $grupo)
                            <option value="{{ $grupo->idgr }}" {{ ($grupo->idgr == $gr) ? 'selected' : '' }}>{{ $grupo->nombre }}</option>
                        @endforeach
                </select> 
                <br>
                <button type="submit" class="btn btn-primary mt-1" id="button">Enviar</button>
                <a type="button" class="btn btn-primary mt-1" href="{{ url('/asesorias')}}"> Limpiar</a>
             <!--button type="button" class="btn btn-primary mt-1" id="limpiar">Limpiar</button-->
                 <!--button id="btn_exportar_excel" type="button" class="btn btn-success mt-1">
                Exportar a EXCEL
            </button-->
                <!--a style="margin-left: 5px;" type="button" class="btn btn-success mt-1" href="{{route('asesorias.create')}}"><i class="fas fa-user-plus"></i></a-->
            </div>
            <div class="col-sm-3">
                <input required name="fecha" value="{{$fecha}}" class="form-control fecha" type="date">      

            </div>
            <div class="col-sm-3">
               <input type="file" class="form-control evidencia">
               
         </div>
         <div class="col-sm-3">
              <!--a type="button" class="btn btn-primary mt-1" href="{{ route('asesorias.update_evidencia')}}"> Guardar</a--> 
               <a class="upload_e" href="#">Subir Evidencia</a>
               <!--button type="submit" class="btn btn-primary upload_e">Subir</button-->
               <a data-id="{{isset($id[0]) ? $id[0]->idae : ''}}" class="download_e" style="margin-left: 10px;" href="{{isset($id[0]) ? './storage/evidencia_asesorias/'.$id[0]->evidencia : '#'}}" target="{{isset($id[0]) ? '_blank' : ''}}">Descargar Evidencia</a>
         </div>   
        </form>
        </div>
        </div>
    </div>

     <div class="card-body">
     
    	<zing-grid
        	lang="custom"
        	caption='Lista de Asesorías'
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
                <zg-column index='tipo' header='Tipo'  type='text'></zg-column>
                <zg-column index='observaciones' header='Observaciones'  type='text'></zg-column>
                <!--zg-column align="center" filter ="disabled" index='operaciones' header='Operaciones' type='text'></zg-column-->
                
        	<!--/zg-colgroup-->
    	</zing-grid>

	</div>
    {{--@foreach ($asesorias as $asesoria)
    <form id="eliminar-asesoria-{{ $asesoria->idas }}" class="ocultar" action="{{ route('asesorias.delete',$asesoria->idas) }}" method="GET">
        @csrf
        @method('DELETE')
    </form>
     @endforeach--}}
     <script type="text/javascript">
        $( document ).ready(function() {
         $('.obs').click(function(){
            $(this).focus();
         })
         $('.save').click(function() {
               obs($(this).prev().data('id'),$(this).prev().val()); 
         })
        $('.ck').on('change',function() {
            del = false;
            if($('.fecha').val()!=''){
                tipo = $(this).attr('value');
                if($(this).attr('value')==1 && ($(".ck[value='1']").prop('checked') || $(".ck[value='2']").prop('checked'))){
                    $(".ck[value='2']").prop('checked', false); 
                }else if($(this).attr('value')==2 && ($(".ck[value='1']").prop('checked') || $(".ck[value='2']").prop('checked'))){
                    $(".ck[value='1']").prop('checked', false); 
                }else{
                    tipo = '';
                } 
                ck(tipo,$(this).data('alumno'),$('.fecha').val());
            }else{
                alert('Debes de seleccionar una fecha !!');
                $(this).prop('checked', false); 
            }
            
        })
         $('.upload_e').click(function() {
            if($('.evidencia').prop("files")[0]){
              upload_e($('.evidencia').prop("files")[0],$('.fecha').val());  
          }else{
             alert('Debes de seleccionar un archivo !!');
          }
            
         })
        function ck(tipo,idal,fecha,th){
            $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
         });
            $.ajax({
               url:'/asesorias/update_asesoria',
               data:{'tipo':tipo,'idal':idal,fecha:fecha},
               type:'post',
               dataType: 'json',
               success:  function (data) {
                   alert(data.response);
               },
               
             });
        }
        function obs(id,obs){
            $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
         });
            $.ajax({
               url:'/asesorias/update_obs',
               data:{'id':id,'obs':obs},
               type:'post',
               success:  function (response) {
                   alert(response);   
               },
               
             });
        }
        function upload_e(file,fecha){
            $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
         });
            var fd = new FormData();
            fd.append('file',file);
            //fd.append('_token',CSRF_TOKEN);
            fd.append('fecha',fecha);
            $.ajax({
               url:'/asesorias/update_evidencia',
               data:fd,
               type:'post',
               contentType: false,
               processData: false,
               dataType: 'json',
               success:  function (data) {
                   $('.download_e').attr('target','_blank');
                   $('.download_e').attr('href','./storage/evidencia_asesorias/'+data.nfile)
                   alert(data.response);
               },
               
             });
        }

    });
     </script>

{{--@endif--}}
@endsection
