<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Portal de Socios</title>
        <link rel="apple-touch-icon" sizes="57x57" href="images/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="images/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/images/faviconapple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="images/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="images/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="images/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="images/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="images/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="images/favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="images/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
        <link rel="manifest" href="images/favicon/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="images/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        @if ($agent->isPhone() || $agent->isMobile())
            <div class="flex-center position-ref full-height" style="width: 100% !important; justify-content: center; margin: auto">
                @if (Route::has('login'))
                    <div class="top-right links">
                        @if (Auth::check())
                            <a href="{{ route('home') }}">{{ __('text.loggedMovil') }}</a>
                        @else
                            <a href="{{ url('/login') }}">{{ __('text.login') }}</a>
                            {{-- <a href="{{ url('/register') }}">{{ __('text.register') }}</a> --}}
                        @endif
                    </div>
                @endif
                <div class="content">
                    <div class="row">
                                <img class="img-fluid" src="{{ asset('images/welcomeMovil.png') }}" style="width: 100%; justify-content: center; margin: auto"/>
                    </div>
                    <br>
                    <div class="row ">
                            <div class="links">
                                <a href="{{ route('webPortal') }}">{{ __('text.contacta') }}</a>
                            </div>
                    </div>
                    <br>
                    <div class="row ">
                            <div class="links">
                                <a href="{{ route('tour') }}">{{ __('text.testing') }}</a>
                            </div>
                    </div>
                    <br/>
                </div>
        @else
            <div class="flex-center position-ref full-height">
                @if (Route::has('login'))
                    <div class="top-right links">
                        @if (Auth::check())
                            <a href="{{ url('/home') }}">{{ __('text.logged') }}</a>
                        @else
                            <a href="{{ url('/login') }}">{{ __('text.login') }}</a>
                            {{-- <a href="{{ url('/register') }}">{{ __('text.register') }}</a> --}}
                        @endif
                    </div>
                @endif
                <div class="content">
                    <div class="row">
                            <div class="title m-b-md">
                                <img class="img-fluid" src="{{ asset('images/welcome.png') }}" />
                            </div>
                        <div class="title m-b-md">
                        <div class="links">
                            <a href="{{ route('webPortal') }}">{{ __('text.contacta') }}</a>
                            <a href="{{ route('tour') }}">{{ __('text.testing') }}</a>
                        </div>
                    </div>
                    <br/>
                </div>
             @endif
            </div>
        </div>
        <div>
            <div style="text-align: left !important;"><a href="{{url('about')}}" style="text-decoration:none !important; font: cursive;">&Pi;</a></div>
        </div>
    </body>
</html>
