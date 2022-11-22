@extends('layouts.app')

@section('content')

<style type="text/css">
    .responsiveCal {
        position: relative; padding-bottom: 100%; height: 0; overflow: hidden;
    }

    .responsiveCal iframe {
        position: absolute; top:0; left: 0; right:0; left:0; width: 100%; height: 100%;
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header"><strong>{{ __('text.dashboard') }}&nbsp;{{__('text.de')}}&nbsp;{{Auth::user()->nombre}}</strong></div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        {{-- @if(Auth::user()->hasRole('admin'))
                            <div>Acceso como administrador</div>
                        @else
                            <div>Acceso usuario</div>
                        @endif --}}

    {{--                     @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif --}}
    {{--                     {{ __('text.bienvenidoUS',['username'=>$username]) }}
     --}}

                        @if(Auth::user()->habilitado('user'))

{{-- ******* GESTION DE FICHA ***********************************************************************************--}}
                            <div class="card-deck">
                                <div class="card border-primary mb-3">
                                    <img class="card-img-top" src="images/youSocio.gif" alt="Gestion de Ficha">
                                    <div class="card-body">
                                        <h3 class="card-title">{{ __('text.gestionFicha') }}</h3>
                                        <p class="card-text" style="text-align: justify;">{{ __('text.descGestionFicha') }}</p>
                                        <a class="btn btn-primary" href="{{ route('gestionficha') }}">{{ __('text.btnGestionFicha') }}</a>
                                    </div>
                                </div>
{{-- ******* ACCESOS RÁPIDOS ***********************************************************************************--}}
                                <div class="card border-danger mb-3 text-center">
                                    <div class="card-header">
                                        {{ __('text.botonesRapidos') }}
                                    </div>
                                    <div class="card-body">
                                        <div class="row" style="margin-top:2%">
                                            <a type="button" class="btn btn-primary btn-block" href="{{ route('crearEventoRapido') }}">{{ __('text.reservar') }}</a>
                                        </div>
                                        <div class="row" style="margin-top:2%">
                                            <a type="button" class="btn btn-success btn-block" href="{{ route('verCalendario') }}" target="_blank">{{ __('text.verCalendario') }}</a>
                                        </div>
                                        <div class="row" style="margin-top:2%">
                                            <a type="button" class="btn btn-warning btn-block" href="mailto:{{ __('text.emailInfo') }}" target="_blank">{{ __('text.correoAInfo') }}</a>
                                        </div>
                                         @if(Auth::user()->hasRole(['admin','tesorero']))
                                            <div class="row" style="margin-top:2%">
                                                <a type="button" class="btn btn-danger btn-block" href="{{ route('apunteRapido') }}">{{ __('text.apunteRapido') }}</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
{{-- ******* CALENDARIO ***********************************************************************************--}}
                                <div class="card border-success mb-3 text-center">
                                    <div class="card-header">
                                        {{ __('text.proximosEventosCal') }}
                                    </div>
                                    <div class="responsiveCal">
                                        <?php echo $cal1 ?>
                                    </div>
                                </div>

                            </div>


                            <br>
{{-- ******* VOCALIAS ***********************************************************************************--}}
                            <div class="card-deck">
                                <div class="card text-center col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" style="padding: 0%">
                                    <div class="card-header">
                                        <strong>{{__('text.seccionVicalias')}}</strong>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">{{__('text.descVocalias')}}</p>
                                    </div>
                                    <div class="card-footer text-muted">
                                        {{__('text.footerVocalias')}}&nbsp;&nbsp; &#10148;
                                    </div>
                                </div>
                                <div class="card col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" style="border: 0px !important">
                                    <div id="accordion">
                                        @foreach ($vocalias as $vocalia)
                                            <div class="card">
                                                <div class="card-header" id="header<?php echo $vocalia['nombre'] ?>">
                                                  <h5 class="mb-0">
                                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapse<?php echo $vocalia['nombre'] ?>" aria-expanded="false" aria-controls="collapse<?php echo $vocalia['nombre'] ?>">
                                                        <?php echo $vocalia['nombre'] ?>
                                                    </button>
                                                    <span style="float: right;"><a type="button" class="btn btn-primary btn-sm" onclick="irAVocalia(<?php echo $vocalia['id'] ?>)">&#10148;</a></span>
                                                  </h5>
                                                </div>

                                                <div id="collapse<?php echo $vocalia['nombre'] ?>" class="collapse hidden" aria-labelledby="<?php echo $vocalia['nombre'] ?>" data-parent="#accordion" >
                                                  <div class="row no-gutters">
                                                        <div class="col" style="width: 100%">
                                                          <img src="images/<?php echo $vocalia['imagen'] ?>" class="card-img" alt="<?php echo $vocalia['imagen'] ?>">
                                                        </div>
                                                        <div class="col" style="width: 100%">
                                                            <div class="card-body">
                                                                <h5 class="card-title">{{__('text.tituloTextoVocalia')}} <?php echo $vocalia['nombre'] ?></h5>
                                                                <p class="card-text"><?php echo $vocalia['descripcion'] ?></p>
                                                                <a type="button" class="btn btn-primary btn-sm" onclick="irAVocalia(<?php echo $vocalia['id'] ?>)">{{__('text.btnVocalia')}}</a>
                                                            </div>
                                                        </div>
                                                      </div>
                                                </div>
                                              </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <br>
{{-- *******DOCUMENTOS ***********************************************************************************--}}
                            <div class="card-deck">
                                <div class="card border-primary mb-3">
                                    <img class="card-img-top" src="images/documentos.png" alt="Ver Documentos">
                                    <div class="card-body">
                                        <h3 class="card-title">{{ __('text.documentos') }}</h3>
                                        <p class="card-text" style="text-align: justify;">{{ __('text.descDocumentos') }}</p>
                                            <a class="btn btn-warning" href="{{ route('documentos') }}">{{ __('text.btnDocumentos') }}</a>
                                    </div>
                                </div>
{{-- *******COSAS DE LA ASOCIACION ***********************************************************************************--}}
                                <div class="card border-primary mb-3">
                                    <img class="card-img-top" src="images/informes.jpg" alt="Ver Informes">
                                    <div class="card-body">
                                        <h3 class="card-title">{{ __('text.informes') }}</h3>
                                        <p class="card-text" style="text-align: justify;">{{ __('text.descInformes') }}</p>
                                            <a class="btn btn-success" href="{{ route('asociacion') }}">{{ __('text.btnInformes') }}</a>
                                    </div>
                                </div>

{{-- ******* ADMINISTRACIÓN ***********************************************************************************--}}
                                <div class="card text-white bg-dark" style="border: 0px !important">
                                    <h4 style="text-align: center;" class="card-header">{{ __('text.administracion') }} </h4>
                                    <div id="accordion2">
                                            <div class="card-header" id="{{ __('text.secretaria') }}">
                                              <h5 class="mb-0">
                                                <button class="btn btn-link" data-toggle="collapse" data-target="#secretariaCard" aria-expanded="false" aria-controls="secretariaCard">
                                                    {{ __('text.secretaria') }}
                                                </button>
                                                <span style="float: right;"><a class="btn btn-info btn-sm" href="{{ route('secretario') }}">&#10148;</a></span>
                                              </h5>
                                            </div>
                                            <div class="card border-secondary mb-3 collapse hidden" id="secretariaCard" data-parent="#accordion2">
                                                <img class="card-img-top" src="images/secretario.jpg" alt="Secretaría" style="width: 75% !importand;">
                                                <div class="card-body">
                                                    <h3 class="card-title" style="text-align: justify; color: black">{{ __('text.secretaria') }}</h3>
                                                    <p class="card-text" style="text-align: justify; color: black">{{ __('text.descsecretaria') }}</p>
                                                    <a class="btn btn-primary" href="{{ route('secretario') }}">{{ __('text.btnsecretaria') }}</a>
                                                </div>
                                            </div>

                                            <div class="card-header" id="{{ __('text.secretaria') }}">
                                              <h5 class="mb-0">
                                                <button class="btn btn-link" data-toggle="collapse" data-target="#tesoreroCard" aria-expanded="false" aria-controls="tesoreroCard">
                                                    {{ __('text.tesoreria') }}
                                                </button>
                                                <span style="float: right;"><a class="btn btn-info btn-sm" href="{{ route('tesorero') }}">&#10148;</a></span>
                                              </h5>
                                            </div>
                                            <div class="card border-dark mb-3 collapse hidden" id="tesoreroCard" data-parent="#accordion2">
                                                <img class="card-img-top" src="images/tesorero.jpg" alt="Tesorería" style="width: 75% !importand;">
                                                <div class="card-body">
                                                    <h3 class="card-title" style="text-align: justify; color: black">{{ __('text.tesoreria') }}</h3>
                                                    <p class="card-text" style="text-align: justify; color: black">{{ __('text.descTesoreria') }}</p>
                                                    <a class="btn btn-primary" href="{{ route('tesorero') }}">{{ __('text.btnTesoreria') }}</a>
                                                </div>
                                            </div>

                                            <div class="card-header" id="{{ __('text.secretaria') }}">
                                              <h5 class="mb-0">
                                                <button class="btn btn-link" data-toggle="collapse" data-target="#configuracionCard" aria-expanded="false" aria-controls="configuracionCard">
                                                    {{ __('text.configuracion') }}
                                                </button>
                                                <span style="float: right;"><a class="btn btn-info btn-sm" href="{{ route('configura') }}">&#10148;</a></span>
                                              </h5>
                                            </div>
                                            <div class="card border-primary mb-3 collapse hidden" id="configuracionCard" data-parent="#accordion2">
                                                <img class="card-img-top" src="images/configuracion.png" alt="Configuración" style="width: 75% !importand;">
                                                <div class="card-body">
                                                    <h3 class="card-title" style="text-align: justify; color: black">{{ __('text.configuracion') }}</h3>
                                                    <p class="card-text" style="text-align: justify; color: black">{{ __('text.descConfiguracion') }}</p>
                                                    <a class="btn btn-primary" href="{{ route('configura') }}">{{ __('text.btnConfiguracion') }}</a>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>

{{-- ******* FIN TARJETAS ***********************************************************************************--}}


                        {{-- EL USUARIO NO ESTÁ HABILITADO --}}
                        @else
                            <div class="row">
                            {{-- Informes --}}
                                <div class="col">
                                    <div class="card">
                                       <div class="jumbotron">
                                            <h1>{{ __('text.usuarioNoHabilitado') }}</h1>
                                            <p>{{ __('text.usuarioNoHabilitadoDesc') }}</p>
                                            <br>
                                            <a href="mailto:{{ __('text.emailInfo') }}" class="btn btn-outline-success btn-lg">
                                                &#128386;&nbsp;{{__('text.enviarEmailSecretario')}}
                                            </a>
                                            <br>
                                            <div class="card-body">
                                                <h3 class="card-title">{{ __('text.gestionFicha') }}</h3>
                                                <a class="btn btn-primary" href="{{ route('gestionficha') }}">{{ __('text.btnGestionFicha') }}</a>
                                            </div>
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
    </div>
</div>


<script type="text/javascript">
    function irAVocalia ($num){
        var $ruta = "{{url('/vocalia')}}/"+$num ;
        window.location.href = $ruta;
    }

</script>
@endsection

