<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />

<meta name="csrf-token" content="{{ csrf_token() }}">

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

<meta name="author" content="acamfue" />

<title>{{__('text.tituloMovil')}}</title>

<link type="text/css" rel="stylesheet" href="{{ asset('movil/css/style.css')}}"/>
<link href='http://fonts.googleapis.com/css?family=Lato:300' rel='stylesheet' type='text/css'/>

<link type="text/css" rel="stylesheet" href="{{ asset('movil/colors/main/main.css')}}"/>
<link type="text/css" rel="stylesheet" href="{{ asset('movil/css/swipebox.css')}}"/>
<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700" rel="stylesheet"> 
<script type="text/javascript" src="{{ asset('movil/js/jquery-1.10.1.min.js')}}"></script>
<script src="{{ asset('movil/js/jquery.validate.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('movil/js/code.js')}}"></script>

<!-- DataTables -->
<link rel="stylesheet" type="text/css" href="{{ asset('datatables/datatables.min.css') }}"/>
<script type="text/javascript" src="{{ asset('datatables/datatables.min.js') }}"></script>

<link rel="stylesheet" href="{{asset('datepicker/css/bootstrap-datepicker3.css')}}">
<link rel="stylesheet" href="{{asset('datepicker/css/bootstrap-datepicker3.standalone.css')}}">
<script src="{{asset('datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('datepicker/locales/bootstrap-datepicker.es.min.js')}}"></script>

<script type="text/javascript" src="{{ asset('movil/js/jquery.tabify.js')}}"></script>
<script type="text/javascript" src="{{ asset('movil/js/jquery.swipebox.js')}}"></script>
<script type="text/javascript" src="{{ asset('movil/js/twitter/jquery.tweet.js')}}" charset="utf-8"></script>
<script src="{{ asset('movil/js/email.js')}}"></script>

<link rel="stylesheet" type="text/css" href="{{ asset('css/mio.css') }}"/>

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
</head>
<body>
    
	<div id="wrapper">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg) 
            @if(Session::has('alert-'.$msg)) 
                <div class="alert alert-{{$msg}} container" role="alert">
                    {{ Session::get('alert-' . $msg) }}
                    {{ Session::forget('alert-' . $msg) }}
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
                <div class="modal-content col-md-4">
                    <div style="text-align: center">
                        <div class="modal-body text-center" style="align-content: center; margin: 0 auto;">
                            <h4>{{__('text.trabajando')}}</h4>
                            <img src="{{ asset('images/working/workingLaser.gif') }}" alt="Trabajando" style="width: 50%;"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <!--FIN Modal TRABAJANDO-->
</body>

</html>