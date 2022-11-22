<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{__('text.tituloMovil')}}</title>
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
<div id="container-fluid">

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
			<a class="navbar-brand" href="{{ url('home') }}">
				<img src="{{ asset('movil/images/icons_dark/home.png') }}" width="30" height="30" class="d-inline-block align-top" alt="" title="" />
			</a>

			<button class="navbar-toggler my-2 my-lg-0" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarTogglerDemo01">
			<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
    			  <li class="nav-item active">
    			    <a class="nav-link" href="{{ route('pag1') }}">{{__('text.MVtuficha')}}</a>
    			  </li>
                  @if(Auth::user()->habilitado('user'))
        			  <li class="nav-item">
        			    <a class="nav-link" href="{{ route('pag2') }}">{{__('text.MVcalendario')}}</a>
        			  </li>
        			  <li class="nav-item">
        			    <a class="nav-link" href="{{ route('pag3') }}">{{__('text.MVnuevoevento')}}</a>
        			  </li>
        			  <li class="nav-item">
        			    <a class="nav-link" href="mailto:{{ __('text.emailInfo') }}">{{__('text.MVenviaremail')}}</a>
        			  </li>
        			  <li class="nav-item">
        			    <a class="nav-link" href="{{ route('pag5') }}">{{__('text.MVvocalias')}}</a>
        			  </li>
        			  <li class="nav-item">
        			    <a class="nav-link" href="{{ route('pag6') }}">{{__('text.MVdocumentos')}}</a>
        			  </li>
        			  @if(Auth::user()->hasRole(['admin','secretario','tesorero']))
        				<li class="nav-item">
        					<a class="nav-link" href="{{ route('pag7') }}">{{__('text.MVapunterapido')}}</a>
        				</li>
        				<li class="nav-item">
        					<a class="nav-link" href="{{ route('pag8') }}">{{__('text.MVsocios')}}</a>
        				</li>
        				<li class="nav-item">
        					<a class="nav-link" href="{{ route('pag9') }}">{{__('text.MVcuentas')}}</a>
        				</li>
        			  @endif
                    @endif
    			</ul>

			</div>
		</nav>


        </div>
        {{-- COMIENZO DEL CUERPO --}}
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

        {{-- FIN DEL CUERPO --}}
</div>
</body>

<script type="text/javascript">
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

    $(document).ready(function(){
        $('.datepicker').datepicker({
           todayBtn: 'linked',
           language: 'es',
           autoclose: true,
           todayHighlight: true,
           format: 'd/m/yyyy'
        });

        $(".reveal").on('click',function() {
            var $pwd = $(".pwd");
            if ($pwd.attr('type') === 'password') {
                $pwd.attr('type', 'text');
            } else {
                $pwd.attr('type', 'password');
            }
        });
    });

</script>

</html>
