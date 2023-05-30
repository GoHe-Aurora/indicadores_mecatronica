@extends('layout.layout')

@section('header')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.25/af-2.3.7/b-1.7.1/b-colvis-1.7.1/b-html5-1.7.1/b-print-1.7.1/cr-1.5.4/date-1.1.0/fc-3.3.3/fh-3.1.9/kt-2.6.2/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.4/sb-1.1.0/sp-1.3.0/sl-1.3.3/datatables.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/c3/0.7.20/c3.min.css" integrity="sha512-cznfNokevSG7QPA5dZepud8taylLdvgr0lDqw/FEZIhluFsSwyvS81CMnRdrNSKwbsmc43LtRd2/WMQV+Z85AQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /-->
</head>

@endsection

@section('content')

<div class="form-row"> 
    <div class="form-group col-12">
        <div class="box">
            <div class="box-body">
                <div class="card-deck mb-4">
                     @if (Auth()->user()->idtu_tipos_usuarios == 1)
                    <div data-route="#" class="card col-lg-4 col-sm-12"
                        style="background-color: #0664c4; cursor: pointer;"
                        id=""
                    >
                        <div class="card-body text-white">
                            <div class="row text-center">
                                <div class="col-lg-4 col-sm-12 mt-4">
                                    <i class="far fa-address-book" style="font-size: 80px"></i>
                                </div>
                                <div class="col-lg-8 col-sm-12">
                                    <div class="mt-3">
                                        <h5>
                                           INDICADORES CACEI 
                                        </h5>
                                    </div>
                                    <div class="mt-3">
                                        <h2>{{----}}</h2>
                                    </div>
                                    <h2></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if (Auth()->user()->idtu_tipos_usuarios == 1)
                    <div data-route="#" class="card col-lg-4 col-sm-12"
                        style="background-color: #00b29a; cursor: pointer;"
                        id=""
                    >
                        <div class="card-body text-white">
                            <div class="row text-center">
                                <div class="col-lg-4 col-sm-12 mt-4">
                                    <i class="far fa-address-book" style="font-size: 80px"></i>
                                </div>
                                <div class="col-lg-8 col-sm-12">
                                    <div class="mt-3">
                                        <h5>
                                           INDICADORES SGI 
                                        </h5>
                                    </div>
                                    <div class="mt-3">
                                        <h2>{{----}}</h2>
                                    </div>
                                    <h2></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if (Auth()->user()->idtu_tipos_usuarios == 1 || Auth()->user()->idtu_tipos_usuarios == 2)
                    <div data-route="#" class="card col-lg-4 col-sm-12"
                        style="background-color: #0664c4; cursor: pointer;"
                        id=""
                    >
                        <div class="card-body alumnos text-white">
                            <div class="row text-center">
                                <div class="col-lg-4 col-sm-12 mt-4">
                                    <i class="fa fa-users" style="font-size: 80px"></i>
                                </div>
                                <div class="col-lg-8 col-sm-12">
                                    <div class="mt-3">
                                        <h5>Alumnos</h5>
                                    </div>
                                    <div class="mt-3">
                                        <h2>{{----}}</h2>
                                    </div>
                                    <h2></h2>
                                </div>
                            </div>
                        </div>
                    </div>

 @endif

                </div>
              
               <div class="card-deck mb-4">
                @if (Auth()->user()->idtu_tipos_usuarios == 1 || Auth()->user()->idtu_tipos_usuarios == 2)
                <div data-route="#" class="card col-lg-4 col-sm-12"
                        style="background-color: #0664c4; cursor: pointer;"
                        id=""
                    >
                        <div class="card-body tutorias text-white">
                            <div class="row text-center">
                                <div class="col-lg-4 col-sm-12 mt-4">
                                    <i class="fa fa-users" style="font-size: 80px"></i>
                                </div>
                                <div class="col-lg-8 col-sm-12">
                                    <div class="mt-3">
                                        <h5>TUTORÍAS</h5>
                                    </div>
                                    <div class="mt-3">
                                        <h2>{{----}}</h2>
                                    </div>
                                    <h2></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div data-route="#" class="card col-lg-4 col-sm-12"
                        style="background-color: #00b29a; cursor: pointer;"
                        id=""
                    >
                        <div class="card-body asesorias text-white">
                            <div class="row text-center">
                                <div class="col-lg-4 col-sm-12 mt-4">
                                    <i class="fa fa-book" style="font-size: 80px"></i>
                                </div>
                                <div class="col-lg-8 col-sm-12">
                                    <div class="mt-3">
                                        <h5>
                                           ASESORÍAS
                                        </h5>
                                    </div>
                                    <div class="mt-3">
                                        <h2>{{----}}</h2>
                                    </div>
                                    <h2></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if (Auth()->user()->idtu_tipos_usuarios == 1 || Auth()->user()->idtu_tipos_usuarios == 2)
                    <div data-route="#" class="card col-lg-4 col-sm-12"
                        style="background-color: #00b29a; cursor: pointer;"
                        id=""
                    >
                        <div class="card-body egel_ecg text-white">
                            <div class="row text-center">
                                <div class="col-lg-4 col-sm-12 mt-4">
                                    <i class="fa fa-check" style="font-size: 80px"></i>
                                </div>
                                <div class="col-lg-8 col-sm-12">
                                    <div class="mt-3">
                                        <h5>
                                            RESULTADOS DE EGEL Y ECG
                                        </h5>
                                    </div>
                                    <div class="mt-3">
                                        <h2>{{----}}</h2>
                                    </div>
                                    <h2></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                
                </div>
                <div class="card-deck mb-4">
                @if (Auth()->user()->idtu_tipos_usuarios == 1 || Auth()->user()->idtu_tipos_usuarios == 2)
                <div data-route="#" class="card col-lg-4 col-sm-12"
                        style="background-color: #00b29a; cursor: pointer;"
                        id=""
                    >
                        <div class="card-body trayectoriac text-white">
                            <div class="row text-center">
                                <div class="col-lg-4 col-sm-12 mt-4">
                                    <i class="fa fa-school" style="font-size: 80px"></i>
                                </div>
                                <div class="col-lg-8 col-sm-12">
                                    <div class="mt-3">
                                        <h5>
                                          SISTEMA DE CALIFICACIONES CUATRIMESTRAL
                                        </h5>
                                    </div>
                                    <div class="mt-3">
                                        <h2>{{----}}</h2>
                                    </div>
                                    <h2></h2>
                                </div>
                            </div>
                        </div>
                    </div>           

 @endif
 @if (Auth()->user()->idtu_tipos_usuarios == 1 || Auth()->user()->idtu_tipos_usuarios == 2)
                <div data-route="#" class="card col-lg-4 col-sm-12"
                        style="background-color: #00b29a; cursor: pointer;"
                        id=""
                    >
                        <div class="card-body valoracionae text-white">
                            <div class="row text-center">
                                <div class="col-lg-4 col-sm-12 mt-4">
                                    <i class="fa fa-school" style="font-size: 80px"></i>
                                </div>
                                <div class="col-lg-8 col-sm-12">
                                    <div class="mt-3">
                                        <h5>
                                          VALORACION AE
                                        </h5>
                                    </div>
                                    <div class="mt-3">
                                        <h2>{{----}}</h2>
                                    </div>
                                    <h2></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                

 @endif
             </div>
             </div>  

            </div>
        </div>
        <div class="table-responsive"  id="tabla">
        </div>
    </div>


@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.25/af-2.3.7/b-1.7.1/b-colvis-1.7.1/b-html5-1.7.1/b-print-1.7.1/cr-1.5.4/date-1.1.0/fc-3.3.3/fh-3.1.9/kt-2.6.2/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.4/sb-1.1.0/sp-1.3.0/sl-1.3.3/datatables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/5.0.0/d3.min.js" integrity="sha512-55FY9DHtfMBE2epZhXrWn78so/ZT5/GCLim66+L83U5LghiYwVBAEris4/13Iab9S8C9ShJp3LQL/2raiaO+0w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.7.20/c3.min.js" integrity="sha512-+IpCthlNahOuERYUSnKFjzjdKXIbJ/7Dd6xvUp+7bEw0Jp2dg6tluyxLs+zq9BMzZgrLv8886T4cBSqnKiVgUw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        const user_id = '{{ $user }}';
        $('.alumnos').click(function(){
            location.href = "{{route('students.index')}}";
        })
        $('.tutorias').click(function(){
            location.href = "{{route('tutorias.index')}}";
        })
        $('.asesorias').click(function(){
            location.href = "{{route('asesorias.index')}}";
        })
         $('.egel_ecg').click(function(){
            location.href = "{{route('egel_ecg.index')}}";
        })
        $('.trayectoriac').click(function(){
            location.href = "{{route('trayectoriac.index')}}";
        })
        $('.valoracionae').click(function(){
            location.href = "{{route('valoracion_ae.index')}}";
        })
    </script>
    <script  type="module" src="/js/panel/panel.js"></script>
@endsection
@endsection
