<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Socios de Portal') }}</title>
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('images/favicon/apple-icon-57x57.png')}}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('images/favicon/apple-icon-60x60.png')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('images/faviconapple-icon-72x72.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('images/favicon/apple-icon-76x76.png')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('images/favicon/apple-icon-114x114.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('images/favicon/apple-icon-120x120.png')}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('images/favicon/apple-icon-144x144.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('images/favicon/apple-icon-152x152.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-icon-180x180.png')}}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('images/favicon/android-icon-192x192.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('images/favicon/favicon-96x96.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{ asset('images/favicon/manifest.json')}}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('images/favicon/ms-icon-144x144.png')}}">
    <meta name="theme-color" content="#ffffff">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
    <script type="text/javascript" src="{{ asset('js/jquery-3.4.1.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('datatables/datatables.min.css') }}"/>
    <script type="text/javascript" src="{{ asset('datatables/datatables.min.js') }}"></script>

    {{-- <script type="text/javascript" src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/buttons.print.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/buttons.html5.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/pdfmake.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/print.min.js') }}"></script> --}}
    <script type="text/javascript" src="{{ asset('js/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/vfs_fonts.js') }}"></script>

    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap-colorselector.css')}}">
    <script type="text/javascript" src="{{ asset('js/bootstrap-colorselector.js') }}"></script>

    <!-- Select Picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-en_US.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-es_ES.min.js"></script>

    <!-- DatePicker -->
    <link rel="stylesheet" href="{{asset('datepicker/css/bootstrap-datepicker3.css')}}">
    <link rel="stylesheet" href="{{asset('datepicker/css/bootstrap-datepicker3.standalone.css')}}">
    <script src="{{asset('datepicker/js/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('datepicker/locales/bootstrap-datepicker.es.min.js')}}"></script>

       {{-- Para Rangos de Fechas --}}
    <script type="text/javascript" src="{{asset('js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/daterangepicker.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('css/daterangepicker.css')}}">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>

    <!-- CARGA DE ESTILOS CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/about.css') }}" rel="stylesheet">
    <!-- Detectamos navegador -->
    @if ($agent->is('Firefox'))
        <link rel="stylesheet" type="text/css" href="{{ asset('css/dataTables.bootstrap4.min.css') }}"/>
    @else
         <link rel="stylesheet" type="text/css" href="{{ asset('css/fixeddataTables.bootstrap4.min.css') }}"/>
    @endif
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('css/buttons.dataTables.min.css') }}"/> --}}
    <script type="text/javascript" src="{{ asset('js/buttons.flash.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/select2.min.css') }}"/>

    <link rel="stylesheet" type="text/css" href="{{ asset('css/mio.css') }}"/>



</head>
<body>
     <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container-fluid">

                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    {{-- <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button> --}}

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <div class="align-middle">
                            <img src="{{ asset('images/favicon/favicon-32x32.png') }}" />
                            &nbsp;&nbsp;
                            {{ __('text.Titulo') }}
                        </div>
                    </a>
                </div>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            {{-- @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif --}}
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre style="position: relative; padding-left: 50px;">
                                    @if(Auth::user()->foto == "")
                                        <img src="{{ asset('images/fotos/default2.jpg')}}" style="width: 32px; height: 32px; position: absolute; top: 0px; left: 10px; border-radius: 50%" />
                                    @else
                                    <img src="{{ asset('images/fotos/'.Auth::user()->foto) }}" style="width: 32px; height: 32px; position: absolute; top: 0px; left: 10px; border-radius: 50%" />
                                    @endif
                                    {{ Auth::user()->nombre }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('gestionficha') }}">&#129332; {{ __('text.tuFicha') }}</i></a>
                                    <a class="dropdown-item" href="{{ route('home') }}">&#128203; {{ __('text.dashboard') }}</i></a>
                                    @if(Auth::user()->hasAnyPermission(['Acceso_total', 'permiso_secretaria']))
                                        <a class="dropdown-item" href="{{ route('secretario') }}">&#128188; {{ __('text.secretaria') }}</i></a>
                                    @endif
                                    @if(Auth::user()->hasAnyPermission(['Acceso_total', 'permiso_tesoreria']))
                                        <a class="dropdown-item" href="{{ route('tesorero') }}">&#128178; {{ __('text.tesoreria') }}</i></a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        &#128244; {{ __('text.logout') }}</i>
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>

                </div>
            </div>
        </nav>

        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-'.$msg))
                <div class="alert alert-{{$msg}} container" role="alert">
                    {{ Session::get('alert-' . $msg) }}
                    {{ Session::forget('alert-' . $msg) }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        @endforeach
        @yield('content')
    </div>

    <!-- Modal TRABAJANDO-->
    <div id="trabajando_modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="container-fluid">
                <!-- Modal content-->
                <div class="modal-content col-md-16">
                    <div style="text-align: center">
                        <div class="modal-body text-center" style="align-content: center; margin: 0 auto;">
                            <h2>{{__('text.trabajando')}}</h2>
                            {{-- <img src="{{ asset('images/working/working'.rand(1,11).'.gif') }}" alt="Trabajando" style="float:center !important;"/> --}}
                            <img src="{{ asset('images/working/workingLaser.gif') }}" alt="Trabajando" style="width: 100%;"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <!--FIN Modal TRABAJANDO-->
</body>

<script type="text/javascript">
    $('#inputColorHito').colorselector();
    function esNumero(evt)
    {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
    function esLetra(evt)
    {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
            return true;
        return false;
    }

    (function ($, DataTable) {
        // Datatable global configuration
        $.extend(true, DataTable.defaults, {
            language: {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        });

    })(jQuery, jQuery.fn.dataTable);
</script>

</html>
