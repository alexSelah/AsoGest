@extends('layouts.app')

@section('content')

<meta http-equiv="Content-Security-Policy" content="default-src 'none'; img-src 'self'; script-src 'self' apis.google.com; style-src 'self';">
<meta http-equiv="Content-Security-Policy" content="default-src 'self' apis.google.com">
<meta http-equiv="Content-Security-Policy" content="script-src 'self' 'unsafe-eval' https://*.google.com; object-src 'self' 'unsafe-eval'">
<meta http-equiv="Content-Security-Policy" content="script-src 'self' 'unsafe-eval' apis.google.com;">

<link href='https://fonts.googleapis.com/css?family=Redressed' rel='stylesheet'>
<link rel="stylesheet" type="text/css" href="{{ asset('css/summernote.min.css') }}"/>

<style type="text/css">
	html{
    scroll-behavior: smooth;
  }
  .row-striped:nth-of-type(odd){
    background-color: #efefef;
    border-left: 4px #000000 solid;
  }

  .row-striped:nth-of-type(even){
    background-color: #ffffff;
    border-left: 4px #efefef solid;
  }

  .row-striped {
      padding: 15px 0;
  }
</style>

<div class="container">
    <input type="text" name="idVocalia_actual" id="idVocalia_actual" value="$vocaliaActual['id']" style="display: none !important;">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs mr-auto" id="myTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><strong>{{__('text.vocaliaDe')}} {{$vocaliaActual['nombre']}}</strong></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="panel-tab" data-toggle="tab" href="#panel" role="tab" aria-controls="panel" aria-selected="false">{{__('text.panelVocal')}}</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="reservas-tab" data-toggle="tab" href="#reservas" role="tab" aria-controls="reservas" aria-selected="false">{{__('text.reservasala')}}</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="propuestas-tab" data-toggle="tab" href="#propuestas" role="tab" aria-controls="propuestas" aria-selected="false">{{__('text.propuestascompra')}}</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="votaciones-tab" data-toggle="tab" href="#votaciones" role="tab" aria-controls="votaciones" aria-selected="false">{{__('text.votaciones')}}</a>
      </li>
      @if (Auth::user()->hasAnyPermission(['Acceso_total','permiso_vocalia_'.$vocaliaActual['nombre']]))
        <li class="nav-item">
          <a class="nav-link" id="email-tab" data-toggle="tab" href="#email" role="tab" aria-controls="email" aria-selected="false">{{__('text.enviaremail')}}</a>
        </li>
      @endif
      <li class="nav-item" style="margin-left: auto">
          <a href="{{ route('home') }}"><input type="button" class="btn btn-outline-secondary btn-sm" value="{{ __('text.back')}}" data-toggle="modal" data-target="#trabajando_modal"/></a>
      </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
    {{--INICIO--}}
        <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
          <br>
            <div class="card-deck">
              <div class="card" style="width:400px">
                <img class="card-img-top" src="{{ asset('images/'.$vocaliaActual['imagen']) }}" alt="Foto Vocalía">
                <div class="card-body">
                  <h5 class="card-title">{{__('text.vocaliaDe')}} {{$vocaliaActual['nombre']}}</h5>
                  <p class="card-text">{{$vocaliaActual['descripcion']}}</p>
                </div>
                <div class="card-footer text-muted">
                  <strong>{{__('text.presupuestoActual')}}:</strong> {{$vocaliaActual['presupuesto']}} {{__('text.simbDin')}}
                  <br><hr>
                  <fieldset class="border p-2">
                    <legend class="w-auto" style="font-size: 100%">{{__('text.vocInteres')}}</legend>
                    <div class="progress" style="height:20px !important;">
                        <div class="progress-bar progress-bar-striped bg-primary" style="width: {{number_format($socIntPercent,0)}}%; " role="progressbar" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-placement="top" title="{{__('text.a')}} {{count($sociosInteresados)}} {{__('text.de')}} {{count($socios)}} {{__('text.gustVoc')}}">
                            <span style="font-size:15px;"><strong>{{number_format($socIntPercent,2)}} %</strong></span>
                        </div>
                    </div>
                  </fieldset>
                </div>
              </div>
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">{{__('text.elVocal')}}</h5>
                  <p class="card-text">{{$vocal['nombre']}}</p>
                  <a href="mailto:{{$vocal['email']}}" class="btn btn-primary">{{__('text.enviaremail')}}</a>
                </div>
                <img class="card-img-bottom" src="{{ asset('images/fotos/'.$vocal['foto']) }}" alt="Foto Vocal">
              </div>

              <div class="card">
                  <h5 class="card-header">{{__('text.otrasvocalias')}}</h5>
                  <div class="card-body">
                    <div id="accordion">
                        @foreach ($vocalias as $vocalia)
                            <div class="card">
                                <div class="card-header" id="header<?php echo $vocalia['nombre'] ?>">
                                  <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapse<?php echo $vocalia['nombre'] ?>" aria-expanded="false" aria-controls="collapse<?php echo $vocalia['nombre'] ?>">
                                        <?php echo $vocalia['nombre'] ?>
                                    </button>
                                  </h5>
                                </div>
                                <div id="collapse<?php echo $vocalia['nombre'] ?>" class="collapse hidden" aria-labelledby="<?php echo $vocalia['nombre'] ?>" data-parent="#accordion" >
                                  <div class="row no-gutters">
                                      <div class="col" style="width: 100%">
                                        <img src="{{ asset('images/'.$vocalia['imagen']) }}" class="card-img" alt="<?php echo $vocalia['imagen'] ?>">
                                      </div>
                                      <div class="col" style="width: 100%">
                                          <div class="card-body">
                                              <h5 class="card-title">{{__('text.tituloTextoVocalia')}} <?php echo $vocalia['nombre'] ?></h5>
                                              {{-- <p class="card-text"><?php echo $vocalia['descripcion'] ?></p> --}}
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
            </div>
        </div> {{--FIN INICIO--}}

    {{--PANEL DE NOTIFICACIONES--}}
        <div class="tab-pane" id="panel" role="tabpanel" aria-labelledby="panel-tab">
            <br>
            @if (Auth::user()->hasAnyPermission(['Acceso_total', 'permiso_vocalia_'.$vocaliaActual['nombre']]))
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#editaPanel_modal" style="color:white; background-color: green">{{__('text.cambiaTxtPanel')}}</button>
            @endif
            <blockquote class="blockquote text-center">
                <p class="mb-0"><h1 style="font-family:Redressed, sans-serif !important;"><u>{{ __('text.HedPanelVocal')}}</u></h1></p>
                <footer class="blockquote-footer">{{ __('text.panelVocalEscrx')}} &nbsp;<cite title="Source Title">{{$vocal['nombre']}}</cite></footer>
            </blockquote>
            <div class="alert alert-warning" role="alert" style="color: #000000">
                <div id="contenidoTablon" data-field-id="{{base64_decode($vocaliaActual['tablon'])}})" ></div>
               {!! base64_decode($vocaliaActual['tablon']) !!}
            </div>

        </div>
    {{--FIN PANEL NOTIFICACIONES--}}


    {{--reservas--}}
        <div class="tab-pane" id="reservas" role="tabpanel" aria-labelledby="reservas-tab">
          <br>
          <div class="row">
            <div class="col-6 col-xs-12">
              <div class="row justify-content-md-center">
                <div class="form-group row">
                    <button type="button" class="btn btn-info noSala" data-toggle="modal" data-target="#nuevaReserva_modal" style="color:white; background-color: green">{{__('text.reservar')}}</button>
                    &nbsp;&nbsp;
                    <button type="button" class="btn btn-danger siSala" data-toggle="modal" data-target="#nuevaReserva_modal" style="color:white; background-color: red">{{__('text.reservarSalaRol')}}</button>
                </div>
              </div>
              <div class="row">
                <br>
                <h2 class="card-title">{{__('text.proximosEventos')}}</h2>
                {{__('text.colorEventosCalendario')}} &nbsp;<label style="color:{{$colores[$vocaliaActual->color]}}; background-color: {{$colores[$vocaliaActual->color]}}; width: 50%">&#9648;</label>
                <br>
              </div>
              <div class="col-12">
                      @if ($proximosEventos !=null)
                        @foreach($proximosEventos as $evento)
                          <div class="row row-striped">
                            <div class="col-2 text-right">
                              <h2><span class="badge badge-secondary">{{$evento['fechaInicio']->format('d')}}</span></h2>
                              <h3><strong>{{\Helper::dimeFechaCarbon($evento['fechaInicio'],10,'-')}}</strong></h3>
                            </div>
                            <div class="col-10">
                              <strong>{{$evento['titulo']}}</strong>
                              <ul class="list-inline">
                                  <li class="list-inline-item">&#128197; {{\Helper::dimeFechaCarbon($evento['fechaInicio'],2,'-')}}</li>
                                <li class="list-inline-item">&#128337; {{$evento['fechaInicio']->format('H:i')}} - {{$evento['fechaFin']->format('H:i')}}</li>
                                <li class="list-inline-item">&#128681; <a href="{{$evento['link']}}" target="_blank">{{__('text.MVverevent')}}</a></li>
                              </ul>
                              <p>{{$evento['descripcion']}}</p>
                            </div>
                          </div>
                          <br>
                        @endforeach
                        @endif
              </div>
              <div class="row">
                {{__('text.avisoreservas')}}
              </div>


            </div>
            <div class="col-6 col-xs-12">
              <div class="row">
                  <?php echo $cal2 ?>
              </div>

            </div>
          </div>
        </div> {{--FIN RESERVAS--}}



    {{--propuestas--}}
        <div class="tab-pane" id="propuestas" role="tabpanel" aria-labelledby="propuestas-tab">
            <br>
            @if (Auth::user()->hasAnyPermission(['Acceso_total','permiso_vocalia_'.$vocaliaActual['nombre']]))
                <div class="shadow p-3 mb-5 bg-white rounded justify-content-center">
                    <h5><strong>{{__('text.informxVocal')}}:</strong></h5>
                    <ul class="list-group">
                        <li class="list-group-item d-inline-flex justify-content-between align-items-center">
                            {{__('text.informxVocalL1')}}
                            @if(is_numeric($numVotaciones) && $numVotaciones == 0)
								<span class="badge badge-success badge-pill">{{$numVotaciones}}</span>
							@else
								<span class="badge badge-success badge-pill">{{count($numVotaciones)}}</span>
							@endif
                        </li>
                        <li class="list-group-item d-inline-flex justify-content-between align-items-center">
                            {{__('text.informxVocalL2')}}
                            <span class="badge badge-danger badge-pill">{{$numSocNV}}</span>
                        </li>
                        <li class="list-group-item d-inline-flex justify-content-between align-items-center">
                                {{__('text.informxVocalL3')}}
                            <span class="badge badge-warning badge-pill">{{$numSocIVNV}}</span>
                        </li>
                    </ul>
                </div>
            @endif

            <h3>
                {{__('text.propuestascompraActuales')}}
                <span class="badge bg-light text-dark"><small>{{__('text.propuestascompraActualesTXTsm')}}</small></span>
            </h3>
                <form action="{{ route('eliminaPropuesta') }}" method="POST" enctype="multipart/form-data">
                    @if($propuestas != null)
                        {{ csrf_field() }}
                        <input type="text" name="vocaliaActual" id="vocaliaActual" style="display: none !important" value="{{$vocaliaActual['id']}}" />
                        <table class="table table-hover table-bordered table-sm">
                            {{-- <input type="hidden" name="tablename" value="{{encrypt('users')}}">
                            <input type="hidden" name="tableid" value="{{encrypt('id')}}">  --}}
                            <thead style="text-align: center;">
                                <tr class="table-primary">
                                <th scope="col" align="center">{{__('text.orden')}}</th>
                                <th scope="col" align="center">{{__('text.socioProp')}}</th>
                                <th scope="col" align="center">{{__('text.propuesta')}}</th>
                                <th scope="col" align="center">{{__('text.precio')}}</th>
                                <th scope="col" align="center" data-toggle="tooltip" data-placement="top" title="{{__('text.tipoCompraDesc')}} dependiendo si es mayor a {{$cantPropMen}} {{ __('text.simbDin')}}">{{__('text.tipoCompra')}}</th>
                                @if($vocaliaActual->id == 2)
                                    <th scope="col" align="center">{{__('text.BGGlink')}}</th>
                                @endif
                                @if (Auth::user()->hasAnyPermission(['Acceso_total','permiso_vocalia_'.$vocaliaActual['nombre']]))
                                    <th scope="col" align="center">
                                        <label class="form-check-label" for="select-all"> {{ __('text.colEliminar')}}</label>
                                        <input type="checkbox" class="control-input custom-checkbox" id="select-all">
                                    </th>
                                @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($propuestas as $propuesta)
                                <tr class="justify-content-md-center">
                                    <td align="center">{{$propuesta['orden']}}º
                                        @if($propuesta['orden'] == 1)
                                            &#129351;
                                        @elseif($propuesta['orden'] == 2)
                                            &#129352;
                                        @elseif($propuesta['orden'] == 3)
                                            &#129353;
                                        @endif
                                    </td>
                                    <td align="center">{{$propuesta['socio']}}</td>
                                    <td align="center">{{$propuesta['propuesta']}}</td>
                                    <td align="center">{{$propuesta['cantidad']}}</td>
                                    <td align="center">{{$propuesta['mp']}}</td>
                                    @if($vocaliaActual->id == 2)
                                        <td align="center"><a type="button" class="btn btn-light" href="{{$propuesta['BGG']}}" target='_blank'>&#128064;</a></td>
                                    @endif
                                    @if (Auth::user()->hasAnyPermission(['Acceso_total','permiso_vocalia_'.$vocaliaActual['nombre']]))
                                        <td align="center">
                                        <input type="checkbox" class="control-input" name="prop[]" value="{{$propuesta['id']}}">
                                        </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                            </table>
                    @else
                        <h4>{{__('text.noPropuestas')}}</h4>
                    @endif
                    <div class="row justify-content-md-center" style="margin-top:5%">
                        <button type="button" class="btn btn-outline-primary col-md-auto" data-toggle="modal" data-target="#nuevaPropuesta_modal" >{{ __('text.crearPropuesta') }}</button>
                        &nbsp;&nbsp;
                        @if (Auth::user()->hasAnyPermission(['Acceso_total','permiso_vocalia_'.$vocaliaActual['nombre']]))
                            <button type="submit" class="btn btn-outline-danger col-md-auto" data-toggle="modal" data-target="#trabajando_modal">{{ __('text.eliminaPropuesta') }}</button>

                            <div class="col-md-auto" style="margin-left: 10px;">
                                <div class="row justify-content-md-center">
                                        <label class="form-label">{{ __('text.switch_CompSepQ') }}</label>
                                        @if ($vocaliaActual['separaComp'] == 0)
                                            <strong>&nbsp;{{ __('text.switch_Unidas') }}</strong>
                                        @else
                                        <strong>&nbsp;{{ __('text.switch_Separadas') }}</strong>
                                        @endif
                                </div>
                                <div class="row justify-content-md-center">
                                    <a href="{{url('/cambiaSepComp').'/'.$vocaliaActual['id']}}" class="btn btn-outline-warning btn-sm" onclick="return confirm('{{ __('text.QuestionVocaliaCP')}}')" >
                                        @if ($vocaliaActual['separaComp'] == 0)
                                            {{ __('text.switch_voc_SepComp') }}
                                        @else
                                            {{ __('text.switch_voc_UnirComp') }}
                                        @endif
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </form>

              <br><hr>
              <h3>{{__('text.comprasAnteriores')}}</h3>
              <table id="comprasTable" name="comprasTable" class="table table-hover table-sm" style="min-width: 100%;">
                <thead>
                  <tr class="table-success">
                    <th scope="col">{{__('text.fecha')}}</th>
                    <th scope="col">{{__('text.compra')}}</th>
                    <th scope="col">{{__('text.vocal')}}</th>
                    <th scope="col">{{__('text.precio')}}</th>
                    @if(Auth::user()->hasAnyPermission(['Acceso_total','permiso_vocalia_'.$vocaliaActual['nombre']]))
                        <th scope="col" class="text-center">{{__('text.eliminar')}}</th>
                    @else
                        <th>No</th>
                    @endif
                  </tr>
                </thead>
              </table>
        </div>

    {{--votaciones--}}
    <div class="tab-pane" id="votaciones" role="tabpanel" aria-labelledby="votaciones-tab">
        @if ($propuestas !=null)
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <span class="glyphicon glyphicon-circle-arrow-right"></span>{{__('text.propuestasTxt')}}</h3>
            </div>
                @if ($vocaliaActual['separaComp'] == 0)
                    @include('vocalia.v1')
                @else
                    @include('vocalia.v2')
                @endif
                <br>
                <hr>
                @if (Auth::user()->hasAnyPermission(['Acceso_total','permiso_vocalia_'.$vocaliaActual['nombre']]))
                  <h3><strong>{{__('text.convertirPropenCompra')}}</strong></h3>
                  <p>{{__('text.convertirPropenCompraDesc')}}</p>
                    @if($propuestas != null)
                        <form action="{{ route('crearCompra') }}" method="POST">
                          {{ csrf_field() }}
                          <input type="text" name="vocaliaActual" id="vocaliaActual" style="display: none !important" value="{{$vocaliaActual['id']}}" />
                              <div class="form-row">
                                <strong>{{__('text.selectpropuestaCompra')}}</strong>
                                <select class="selectpicker w-75" data-done-button="true" id="propuesta" name="propuesta">
                                  @foreach($propuestas as $propuesta)
                                      <option value='{{$propuesta['id']}}'>{{$propuesta['propuesta']}}  -  {{__('text.numVotos')}}: {{$propuesta['numVotos']}}
                                      </option>
                                  @endforeach
                                </select>
                                @foreach($propuestas as $propuesta)
                                  <input type="text" value="{{$propuesta['cantidad']}}" id="precio{{$propuesta['id']}}" style="display: none !important;">
                                @endforeach

                              </div>
                              <br>
                              <div class="form-row form-group">
                                  <label class="form-label" for="cantidadReal">{{__('text.cantidadReal')}}:</label>
                                <div class="input-group col-2">
                                  <input type="text" class="form-control" id="cantidadReal"  name="cantidadReal" onkeypress="return esNumero(event)">
                                  <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2">{{ __('text.simbDin')}}</span>
                                  </div>
                                </div>
                              </div>

                            <br>
                            <div class="form-row">
                              <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="borrarPropsT"  name="borrarProps" value="todas">
                                <label class="custom-control-label" for="borrarPropsT">{{__('text.borrarTodaspropuestas')}}</label>
                              </div>
                              <div class="custom-control custom-radio mb-3">
                                <input type="radio" class="custom-control-input" id="borrarProps1"  name="borrarProps" value="una" checked>
                                <label class="custom-control-label" for="borrarProps1">{{__('text.borrarSoloPropuesta')}}</label>
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="emailASuscriptores" name="emailASuscriptores" checked>
                                <label class="custom-control-label" for="emailASuscriptores">{{__('text.emailAnuncioVotacion')}}</label>
                              </div>
                            </div>

                            <div class="panel-footer">
                                <button class="btn btn-warning" type="submit" data-toggle="modal" data-target="#trabajando_modal">
                                    &#128722; &nbsp;{{__('text.crearCompra')}}
                                </button>
                            </div>
                        </form>
                      @endif
                  @endif
                  <br>
            </div>
            @else
              <h1>{{__('text.noPropuestas')}}</h1>
            @endif
          </div>
          {{-- FIN DEL PANEL VOTACIONES--}}

    {{--email--}}
        <div class="tab-pane" id="email" role="tabpanel" aria-labelledby="email-tab">
            <br>
            <form id="contact-form" method="post" action="{{ route('emailVocal') }}" role="form">
              {{ csrf_field() }}
              <input type="text" name="vocaliaActual" id="vocaliaActual" style="display: none !important" value="{{$vocaliaActual['id']}}" />
                    <div class="messages"></div>
                    <div class="controls">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="form_name">{{__('text.nombre')}}*</label>
                                    <input id="form_name" type="text" maxlength="191" name="nombre" class="form-control" value="{{$vocal['nombre']}}" required="required" data-error="Tu nombre es obligatorio.">
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="form_email">{{__('text.email')}}*</label>
                                    <input id="form_email" type="text" maxlength="191" name="email" aria-describedby="emailHelp" class="form-control" value="{{$vocal['email']}}" required="required" data-error="El email es obligatorio.">
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <div class="form-check">
                                  <input type="checkbox" class="form-check-input checked" id="checkSoloFollowers" name="checkSoloFollowers" data-toggle='collapse' data-target='#seleccionarSocios' selected checked>
                                  <label class="form-check-label" for="checkSoloFollowers">{{__('text.emailAsoloFollowers')}}</label>
                            </div>
                          </div>
                        </div>
                        <br>
                        <div id="seleccionarSocios" class="form-group row collapse">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="form_need"><strong>{{__('text.emailA')}}</strong> &nbsp;  {{__('text.actPrivacidad')}}</label>
                                    <select class="selectpicker form-control w-100 show-tick" data-done-button="true" multiple data-live-search="true" id="socioSelect[]" name="socioSelect[]" style="width: 100%" data-actions-box="true" data-selected-text-format="count > 3">
                                        @foreach ($sociosEmail as $socio)
                                            <option value="{{$socio['id']}}">({{$socio['numSocio']}}) - {{$socio['nombre']}} {{$socio['primerApellido']}} {{$socio['segundoApellido']}}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="form_message">{{__('text.mensaje')}}*</label>
                                    <textarea id="form_message" name="message" class="form-control" placeholder="Escribe un mensaje" rows="4" required="required" data-error="Por favor, escribe un mensaje"></textarea>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <input type="submit" class="btn btn-success btn-send" value="{{__('text.enviaremail')}}" data-toggle="modal" data-target="#trabajando_modal">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p class="text-muted">
                                    <strong>*</strong>{{__('text.camposObligatorios')}}
                            </div>
                        </div>
                    </div>
                </form>
        </div>

    </div>
</div>

{{-- MODALES --}}
 <!-- Modal NUEVA RESERVA-->
<div id="nuevaReserva_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="container-fluid">
            <!-- Modal content-->
            <div class="modal-content col-md-12">
                <div class="modal-body">
                    <p class="">{{ __('text.reservar') }}</p>
                    <hr>
                    <form action="{{ route('guardaEvento') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                            <div class="form-group row">
                               <label for="columnaTiempo" class="col-sm-4 col-form-label">{{ __('text.colHoraEvento')}}</label>
                                <div class="col-sm-8" id="columnaTiempo" name="columnaTiempo">
                                    <div class="row">
                                      <div class="col">
                                        <label for="eventoDesde" class="col-form-label">{{ __('text.colDesde')}}</label>
                                        <input type="text" name="eventoDesde" id="eventoDesde" class="form-control"  />
                                      </div>
                                      <div class="col">
                                        <label for="eventoHasta" class="col-form-label">{{ __('text.colDuracion')}}</label>
                                        <input type="text" name="eventoHasta" id="eventoHasta" class="form-control" />
                                      </div>
                                    </div>
                                </div>
                            </div>

                            <div style="display: none">
                              <input type="text" name="vocaliaActual" id="vocaliaActual" style="display: none !important" value="{{$vocaliaActual['id']}}" />
                              <input type="text" name="fechaInicio" id="fechaInicio" style="display: none !important" />
                              <input type="text" name="horaInicio" id="horaInicio" style="display: none !important" />
                              <input type="text" name="fechaFin" id="fechaFin" style="display: none !important" />
                              <input type="text" name="horaFin" id="horaFin" style="display: none !important" />
                              <fieldset class="form-group">
                                <input type="radio" name="salaRol"  value="noSala" id="noSala" style="display: none !important" >
                                <input type="radio" name="salaRol" value="siSala" id="siSala" style="display: none !important" >
                              </fieldset>
                            </div>

                            <div class="form-group row">
                               <label for="inputNombre" class="col-sm-4 col-form-label">{{ __('text.colNombre')}}</label>
                                <div class="col-sm-8">
                                  <input class="form-control" type="text" maxlength="190" name="inputNombre" id="inputNombre" placeholder="{{ __('text.nombreEventoDesc')}}" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="descripcionEvento">{{__('text.colDescripcion')}}</label>
                                <textarea id="descripcionEvento" name="descripcionEvento" class="form-control" placeholder="{{ __('text.descripcionEventoDesc')}}" rows="4"></textarea>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="form-group row">
                                <label for="socioSelect[]" class="col-sm-4 col-form-label">{{ __('text.participantes')}}</label>
                                  <div class="col-sm-8 select-outline">
                                      <select class="selectpicker form-control" data-done-button="true" multiple data-live-search="true" id="socioSelect[]" name="socioSelect[]" style="width: 100%">
                                          @foreach ($socios as $socio)
                                              <option value="{{$socio['id']}}">{{$socio['nombre']}} {{$socio['primerApellido']}}</option>
                                          @endforeach
                                      </select>
                                  </div>
                            </div>
                        <br/>
                        <hr>
                        <div class="form-group row justify-content-md-center">
                            <div class="col col-lg-2">
                                <button class="btn btn-success form-control" type="submit" data-toggle="modal" data-target="#trabajando_modal">
                                    {{ __('text.btnGuardar')}}
                                </button>
                            </div>
                            <div class="col-md-auto">

                            </div>
                            <div class="col col-lg-2">
                                <button type="button" class="btn btn-secondary form-control" data-dismiss="modal">
                                    {{ __('text.btnClose')}}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--FIN Modal NUEVA RESERVA-->

<!-- Modal Nueva PROPUESTA DE COMPRA-->
<div id="nuevaPropuesta_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="container-fluid">
            <!-- Modal content-->
            <div class="modal-content col-md-12">
                <div class="modal-body">
                    <p class="">{{ __('text.crearPropuesta') }}</p>
                    <hr>
                    <form action="{{ route('guardaPropuesta') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                            <input type="text" name="vocaliaActual" id="vocaliaActual" style="display: none !important" value="{{$vocaliaActual['id']}}" />

                            <div class="form-group row">
                               <label for="inputDesc" class="col-sm-2 col-form-label">{{ __('text.nombrePropuesta')}}</label>
                                <div class="col-sm-10">
                                  <input class="form-control" type="text" maxlength="191" name="inputDesc" id="informeDescripción" placeholder="{{ __('text.nombrePropuestaDesc')}}" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputCant" class="col-sm-2 col-form-label">{{ __('text.propPrecio')}}</label>
                                <div class="col-sm-10">
                                  <div class="input-group mb-3">
                                    <input class="form-control" type="text" maxlength="10" name="inputCant" id="inputCant" placeholder="{{ __('text.propPrecioDesc')}}" onkeypress="return esNumero(event)"/>
                                    <div class="input-group-append">
                                      <span class="input-group-text" id="basic-addon2">{{ __('text.simbDin')}}</span>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            <br/>
                        <hr>
                        <div class="form-group row justify-content-md-center">
                            <div class="col col-lg-2">
                                <button class="btn btn-success form-control" type="submit" data-toggle="modal" data-target="#trabajando_modal">
                                    {{ __('text.btnGuardar')}}
                                </button>
                            </div>
                            <div class="col-md-auto">

                            </div>
                            <div class="col col-lg-2">
                                <button type="button" class="btn btn-secondary form-control" data-dismiss="modal">
                                    {{ __('text.btnClose')}}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--FIN Modal PROPUESTA DE COMPRA-->

<!-- Modal CAMBIAR PANEL NOTICIAS-->
<div id="editaPanel_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="container-fluid">
            <!-- Modal content-->
            <div class="modal-content col-md-12">
                <div class="modal-body">
                    <h4>{{__('text.cambiaPanelVocalD1')}} </h4>
                    <p>{{__('text.cambiaPanelVocalD2')}}</p>
                    <hr>
                    <form action="{{ route('guardaPanelVocal') }}" method="POST">
                        {{ csrf_field() }}
                        <br>
                        <input type="text" name="vocaliaActual" id="vocaliaActual" style="display: none !important" value="{{$vocaliaActual['id']}}" />
                        <div class="form-group row">
                            <textarea id="summernote" name="editordata" style="min-width: 100%; background-color: white;">
                            </textarea>
                        </div>
                        <hr>
                        <div class="form-group row justify-content-md-center">
                            <div class="col-md-auto">
                                <input type="submit" class="btn btn-success" value="{{ __('text.cambiaPanelVocalbtn')}}" />
                            </div>
                            <div class="col-md-auto">

                            </div>
                            <div class="col col-lg-2">
                                <button type="button" class="btn btn-secondary form-control" data-dismiss="modal">
                                    {{ __('text.btnClose')}}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--FIN Modal CAMBIAR PANEL NOTICIAS-->

<!-- SUMMERNOTE EDITOR WYGIWYS -->
<script type="text/javascript" src="{{ asset('js/summernote.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/summernote-es-ES.js') }}"></script>

<script type="text/javascript">
    function httpGetBggGameUrl(gameName) {
        var url = 'https://api.geekdo.com/xmlapi2/search?query='+gameName;
        var xhr = new XMLHttpRequest();
        xhr.open("GET", url, false);
        xhr.send();
        var gameId = xhr.responseXML.getElementsByTagName('item')[0].id;
        var gameUrl = 'https://boardgamegeek.com/boardgame/'+gameId;
        return gameUrl;
    }

    // Show appropriate pill based on #anchor in URL
    var url = window.location.href;
    if (url.indexOf("#") > 0){
        var activeTab = url.substring(url.indexOf("#") + 1);
        $('.nav[role="tablist"] a[href="#'+activeTab+'"]').tab('show');
    }

    $('#select-all').click(function(event) {
        if(this.checked) {
        $(':checkbox').each(function() {
            this.checked = true;
        });
        }else{
        $(':checkbox').each(function() {
            this.checked = false;
        });
        }
    });

    $('socioSelect').selectpicker();
    $('propuestaSelect').selectpicker();

    $('.selectpicker').change(function () {
    $val = document.getElementById('propuesta').value;
    $pre = "precio" + $val;
    $cant= document.getElementById($pre).value;
    document.getElementById('cantidadReal').value = $cant;
    });

    $('#editaPanel_modal').on('shown.bs.modal', function() {
        var HTMLcode = $('#contenidoTablon').data("field-id");
        $('#summernote').summernote({
        lang: 'es-ES',
        tabsize: 2,
        height: 320,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'italic','strikethrough', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview', 'help']]
            // ['view', ['fullscreen', 'codeview', 'help']]
        ]
        });
        $('#summernote').summernote('pasteHTML', HTMLcode);
    })

    $(document).ready(function(){
        $idVocalia = document.getElementById('idVocalia_actual').value;

        $.ajax({
            url: "{{url('/comprasDatatable'.'/'.$vocaliaActual->id)}}",
            success : function(data) {
                var tabla = data.data;//.list.item;
                $('#comprasTable').DataTable( {
                    responsive: true,
                    processing: true,
                    colReorder: true,
                    serverSide: true,
                    "order": [[ 1, "desc" ]],
                    ajax: '{{url('/comprasDatatable').'/'.$vocaliaActual->id}}',
                    initComplete: function () {
                        this.api().columns().every(function () {
                            var column = this;
                            var input = document.createElement("input");
                            input.setAttribute('type', 'text');
                            input.setAttribute('class', 'form-control')
                            $(input).appendTo($(column.footer()).empty())
                            .on('change', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                        });
                    },
                    lengthMenu: [
                        [ 10, 25, 50, -1 ],
                        [ '10 filas', '25 filas', '50 filas', 'Mostrar Todo' ]
                    ],
                    dom: 'Bfrtip',
                    language: {
                        buttons: {
                            copyTitle: 'Copiar al Portapeles',
                            copyKeys: 'Presione <i> ctrl </i> o <i> \ u2318 </i> + <i> C </i> para copiar los datos de la tabla a su portapapeles. <br> <br> Para cancelar, haga clic en este mensaje o presione Esc.',
                            copySuccess: {
                                _: '%d líneas copiadas',
                                1: '1 línea copiada'
                            },
                            pageLength: {
                                _: "Mostrar %d filas",
                                '-1': "Mostrar Todo"
                            }
                        }
                    },
                    data : tabla,
                    columns: [
                        {"data" : "fecha"},
                        {"data" : "descripcion"},
                        {"data" : "vocal"},
                        {"data" : "cuantia"},
                        {
                            "className": 'details-control',
                            "orderable": false,
                            "data": null,
                            "searchable": false,
                            "render": function (data, type, row) {
                                return "<div class=\"d-flex justify-content-center\"><a href=\"{{url('/eliminaCompra/')}}/"+ row.idVocalia+'/'+ row.id +"\"><input id=\"elim/"+row.id+"\" @if (Auth::user()->hasAnyPermission(['Acceso_total','permiso_vocalia_'.$vocaliaActual['nombre']])) style=\"display:center\" @else style=\"display:none\" @endif type=\"button\" class=\"btn btn-danger\" value=\"&#10007\" onclick=\"return confirm('{{ __('text.QuestionSuredelete')}}')\"/></a></div>";
                            }
                        },
                    ],
                    buttons: [
                    {
                        extend: 'pageLength',
                        className: "btn-sm btn-info",
                    },
                    {
                    extend: 'collection',
                    className: 'exportButton',
                    text: 'Exportar Datos',
                        buttons: [
                            {
                                extend:'copy',
                                titleAttr: 'Copiar datos al portapapeles',
                                text: 'Copiar',
                                exportOptions: {
                                    modifier: {
                                        page: 'all',
                                        search: 'applied'
                                    }
                                },
                                action: function ( e, dt, node, config ) {
                                    $resp = confirm("{{ __('text.avisoExportar')}}" );
                                    if($resp == true){
                                        $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config)
                                    }
                                }
                            },
                            {
                                extend: 'print',
                                className: "btn-sm btn-secondary",
                                text: 'Imprimir',
                                titleAttr: 'Imprimir',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                action: function ( e, dt, node, config ) {
                                    $resp = confirm("{{ __('text.avisoExportar')}}" );
                                    if($resp == true){
                                        $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config)
                                    }
                                }
                            },
                            {
                                extend: 'excel',
                                className: 'btn btn-sm btn-secondary',
                                titleAttr: 'Exportar a Excel.',
                                text: 'Excel (XLSX)',
                                filename: 'excel-export',
                                extension: '.xlsx',
                                exportOptions : {
                                    modifier : {
                                        // DataTables core
                                        order : 'index',  // 'current', 'applied', 'index',  'original'
                                        page : 'all',      // 'all',     'current'
                                        search : 'none'     // 'none',    'applied', 'removed'
                                    }
                                },
                                action: function ( e, dt, node, config ) {
                                    $resp = confirm("{{ __('text.avisoExportar')}}" );
                                    if($resp == true){
                                        $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config)
                                    }
                                }
                            },

                        ],
                    }],  //Finde Buttons
                });
            },
        });//FIN DATATABLE COMPRAS


      $(".noSala").click(function(){
          $("#noSala").prop("checked", true);
      });
      $(".siSala").click(function(){
          $("#siSala").prop("checked", true);
      });
  });

  function irAVocalia ($num){
      var $ruta = "{{url('/vocalia')}}/"+$num ;
      window.location.href = $ruta;
  };

//DATE RANGE PICKER
    $(function() {
      $('input[name="eventoDesde"]').daterangepicker({
            "singleDatePicker": true,
            "timePicker": true,
            "timePickerIncrement": 10,
            "timePicker24Hour": true,
            "locale": {
                "format": "DD/MMM  -   hh:mm A",
                "separator": " - ",
                "applyLabel": "OK",
                "cancelLabel": "Cancelar",
                "fromLabel": "Desde",
                "toLabel": "Hasta",
                "customRangeLabel": "Personalizar",
                "weekLabel": "S",
                "daysOfWeek": [
                    "Do",
                    "Lu",
                    "Ma",
                    "Mi",
                    "Ju",
                    "Vi",
                    "Sa"
                ],
                "monthNames": [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre"
                ],
                "firstDay": 1
            },
            "alwaysShowCalendars": true,
            "startDate": moment().startOf('hour'),
            "endDate": moment().startOf('hour').add(3, 'hour'),
            "opens": "center",
            "applyButtonClasses": "btn btn-sm btn-success",
            "cancelClass": "btn btn-sm btn-secondary"
        }, function(start, end, label) {
          //console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
          document.getElementById("fechaInicio").value = start.format('DD/MM/YYYY');
          document.getElementById("horaInicio").value = start.format('H:mm');
        });
      $('input[name="eventoHasta"]').daterangepicker({
            "singleDatePicker": true,
            "timePicker": true,
            "timePickerIncrement": 10,
            "timePicker24Hour": true,
            "locale": {
                "format": "DD/MMM  -   hh:mm A",
                "separator": " - ",
                "applyLabel": "OK",
                "cancelLabel": "Cancelar",
                "fromLabel": "Desde",
                "toLabel": "Hasta",
                "customRangeLabel": "Personalizar",
                "weekLabel": "S",
                "daysOfWeek": [
                    "Do",
                    "Lu",
                    "Ma",
                    "Mi",
                    "Ju",
                    "Vi",
                    "Sa"
                ],
                "monthNames": [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre"
                ],
                "firstDay": 1
            },
            "alwaysShowCalendars": true,
            "startDate": moment().startOf('hour'),
            "endDate": moment().startOf('hour').add(3, 'hour'),
            "opens": "center",
            "applyButtonClasses": "btn btn-sm btn-success",
            "cancelClass": "btn btn-sm btn-secondary"
        }, function(start, end, label) {
          //console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('H:mm') + ' (predefined range: ' + label + ')');
          document.getElementById("fechaFin").value = start.format('DD/MM/YYYY');
          document.getElementById("horaFin").value = start.format('H:mm');
        });
    });

    // CHART REDONDO


    // CHART REDONDO MyP
    @if($vocaliaActual['separaComp'] == 0)
    {
        var ctxR = document.getElementById("myPieChart");
        var myPieChart = new Chart(ctxR, {
        type: 'pie',
        data: {
            labels: @json($props),
            datasets: [{
            data: @json($vots),
            backgroundColor: @json($fillColors),
            }],
        },
        options: {
            maintainAspectRatio: true,
            tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
            },
            legend: {
            display: true
            },
        },
        });
        myPieChart.render();
    }
    @else
    {
        var ctxRM = document.getElementById("myPieChartM");
        var myPieChartM = new Chart(ctxRM, {
        type: 'pie',
        title: "{{__('text.M_mayores')}}",
        data: {
            labels: @json($propsM),
            datasets: [{
            data: @json($votsM),
            backgroundColor: @json($fillColors),
            }],
        },
        options: {
            maintainAspectRatio: true,
            tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
            },
            legend: {
            display: true
            },
        },
        });
        var ctxRP = document.getElementById("myPieChartP");
        var myPieChartP = new Chart(ctxRP, {
        type: 'pie',
        title: "{{__('text.M_menores')}}",
        data: {
            labels: @json($propsP),
            datasets: [{
            data: @json($votsP),
            backgroundColor: @json($fillColors),
            }],
        },
        options: {
            maintainAspectRatio: true,
            tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
            },
            legend: {
            display: true
            },
        },
        });
        myPieChartM.render();
        myPieChartP.render();
    }
    @endif

</script>

<noscript>Please, enable JavaScript to see this page. Por favor, habilita JavaScript para ver esta página.</noscript>
@endsection

