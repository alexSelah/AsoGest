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
<link type="text/css" rel="stylesheet" href="{{ asset('movil/colors/white/white.css')}}"/>
<link type="text/css" rel="stylesheet" href="{{ asset('movil/css/swipebox.css')}}"/>
<link href='http://fonts.googleapis.com/css?family=Lato:300' rel='stylesheet' type='text/css'/>
<script type="text/javascript" src="{{ asset('movil/js/jquery-1.10.1.min.js')}}"></script>
<script src="{{ asset('movil/js/jquery.validate.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('movil/js/code.js')}}"></script>
</head>


<body>
<div id="wrapper">

    <div id="content">



        @if(Auth::user()->habilitado('user'))
            <div class="sliderbg_menu">
                <div class="logo"><a href="#"><img src="{{ asset('images/logoPeq.png')}}" alt="" title="" border="0" /></a></div>
                <nav id="menu">
                <ul>
                    <li><a href="{{ route('pag1') }}"><img src="{{ asset('movil/images/icons_dark/about.png')}}" alt="" title="" /><span>{{__('text.MVtuficha')}}</span></a></li>
                    <li><a href="{{ route('pag2') }}"><img src="{{ asset('movil/images/icons_dark/calendar.png')}}" alt="" title="" /><span>{{__('text.MVcalendario')}}</span></a></li>
                    <li><a href="{{ route('pag3') }}" ><img src="{{ asset('movil/images/icons_dark/light.png')}}" alt="" title="" /><span>{{__('text.MVnuevoevento')}}</span></a></li>
                    <li><a href="mailto:{{ __('text.emailInfo') }}"><img src="{{ asset('movil/images/icons_dark/contact.png')}}" alt="" title="" /><span>{{__('text.MVenviaremail')}}</span></a></li>
                    <li><a href="{{ route('pag5') }}"><img src="{{ asset('movil/images/icons_dark/travel.png')}}" alt="" title="" /><span>{{__('text.MVvocalias')}}</span></a></li>
                    <li><a href="{{ route('pag6') }}" ><img src="{{ asset('movil/images/icons_dark/docs.png')}}" alt="" title="" /><span>{{__('text.MVchatsydocs')}}</span></a></li>
                     @if(Auth::user()->hasRole(['admin','tesorero','secretario']))
                        <li><a href="{{ route('pag7') }}" ><img src="{{ asset('movil/images/icons_dark/pencil.png')}}" alt="" title="" /><span>{{__('text.MVapunterapido')}}</span></a></li>
                        <li><a href="{{ route('pag8') }}" ><img src="{{ asset('movil/images/icons_dark/clients.png')}}" alt="" title=""/><span>{{__('text.MVsocios')}}</span></a></li>
                        <li><a href="{{ route('pag9') }}"><img src="{{ asset('movil/images/icons_dark/stats.png')}}" alt="" title=""/><span>{{__('text.MVcuentas')}}</span></a></li>
                    @else
                        <li><a href="#" ><img src="{{ asset('movil/images/icons_dark/pencilLight.png')}}" alt="" title="" /><span style="color: LightGray !important;">{{__('text.MVapunterapido')}}</span></a></li>
                        <li><a href="#" ><img src="{{ asset('movil/images/icons_dark/clientsLight.png')}}" alt="" title=""/><span style="color: LightGray !important;">{{__('text.MVsocios')}}</span></a></li>
                        <li><a href="{{ route('pag9') }}"><img src="{{ asset('movil/images/icons_dark/stats.png')}}" alt="" title=""/><span>{{__('text.MVcuentas')}}</span></a></li>
                    @endif
                </ul>
                </nav>
            </div>
        {{-- EL USUARIO NO ESTÁ HABILITADO --}}
        @else
            <div class="sliderbg">
                <div class="logo"><a href="#"><img src="{{ asset('images/logoPeq.png')}}" alt="" title="" border="0" /></a></div>
                <h2 style="text-align:center"><strong>{{ __('text.usuarioNoHabilitado') }}</strong></h2>
                    <div class="portfolio_item" style="padding: 2px; border: 0px;">
                        <div class="portfolio_image">
                            <img src="{{ asset('movil/images/confused.png')}}" alt="" title="" border="0" />
                        </div>
                        <div class="portfolio_details">
                            <p>{{ __('text.usuarioNoHabilitadoDesc') }}</p>
                            <hr>
                            <a href="mailto:{{__('text.emailSecretario')}}" class="button_12 green green_borderbottom radius4">{{__('text.enviarEmailSecretario')}}</a>
                            <a href="{{ route('pag1') }}" class="button_12 blue blue_borderbottom radius4">{{ __('text.gestionFicha') }}</a>
                        </div>
                    </div>
            </div>
        @endif
       <div class="clear">
            <a class="button_11" href="{{ route('logout') }}"
               onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();" style="color: black !important;">
                &#128244; {{ __('text.logout') }}</i>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>


       </div>

    </div>
</div>
<script type="text/javascript" src="{{ asset('movil/js/jquery.tabify.js')}}"></script>
<script type="text/javascript" src="{{ asset('movil/js/jquery.swipebox.js')}}"></script>
<script type="text/javascript" src="{{ asset('movil/js/jquery.fitvids.js')}}"></script>
<script type="text/javascript" src="{{ asset('movil/js/twitter/jquery.tweet.js')}}" charset="utf-8"></script>
<script src="{{ asset('movil/js/email.js')}}"></script>
</body>
</html>
