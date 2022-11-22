@extends('layouts.app')

@section('content')
    <div class="container"> {{--CONTAINER PRINCIPAL DE PÁGINA--}}
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    {{-- #p1 {background-color: rgba(255, 0, 0, 0.3);}  red with opacity
                    #p2 {background-color: rgba(0, 255, 0, 0.3);}  green with opacity
                    #p3 {background-color: rgba(0, 0, 255, 0.3);}  blue with opacity --}}
                    <div class="card-header" style="background-color: rgba(0,0,255,0.2) !important;">
                        <div class="d-flex justify-content-between align-items-center">
                           <strong>{{ __('text.gestionFicha') }}</strong>
                            <a href="{{ route('home') }}"><input type="button" class="btn btn-outline-secondary btn-sm" value="{{ __('text.back')}}" /></a>
                        </div>
                    </div>

                    <div class="container">{{-- CONTAINER de la ficha--}}
                        <br>
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-12">
                                <br>
                                <div class="card-body">
                                    {{ __('text.descGestionFicha') }}
                                    <br/><hr>
                                    <div class="col-12 col-md-12">
                                        <form enctype="multipart/form-data" action="{{ route('usuarioStore') }}" method="POST">
                                            {{ csrf_field() }}

{{-- ***************COLUMNA DE FOTO Y DATOS PRINCIPALES *********************** --}}
                                            <div class="form-group row">
                                                <input class="form-control" type="text" readonly name="inputIdSocio" id="inputIdSocio" value="{{$usuario['id']}}" style="display:none; !important">
                                                <div class="row">
                                                    <div class="col align-self-center center">
                                                        <img src="{{ asset('images/fotos/'.$foto) }}" alt="Foto del socio" style="float:left; margin-right:25px; !important"/>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top:20px;">
                                                    <div class="col align-self-center">
                                                        <label for="fotoPerfil" class="col-form-label">{{ __('text.cambiaFoto')}}</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col align-self-center">
                                                        <input type="file" name="fotoPerfil">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputNumsocio" class="col-form-label">{{ __('text.numsocio')}}</label>
                                                <input class="form-control" type="text"maxlength="191" readonly name="inputNumsocio" id="inputNumsocio" value="{{$usuario['numSocio']}}">
                                            </div>

                                            <div class="form-group row">
                                                <label for="altaSocio" class="col-form-label">{{ __('text.altaSocio')}}</label>
                                                <input data-date-format="d/m/yyyy" required type="text" @if (Auth::user()->hasPermissionTo('permiso_secretaria')) class="form-control datepicker"  data-provide="datepicker" @else readonly class="form-control"  @endif name="altaSocio" id="altaSocio" value="{{ date('d/m/Y', strtotime($usuario['altaSocio'])) }}">
                                            </div>
                                            <div class="form-group row">
                                                <label for="veterano" class="col-form-label">{{ __('text.veterano')}}</label>
                                                <input class="form-control" required type="text" readonly name="veterano" id="veterano" value="@if($veterano == true) Si @else No @endif">
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputDNI" class="col-form-label">{{ __('text.DNI')}}</label>
                                                <input class="form-control" maxlength="9" type="text" @if (Auth::user()->hasPermissionTo('permiso_secretaria')) @else readonly @endif name="inputDNI" id="inputDNI" value="{{$usuario['DNI']}}">
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputName" class="col-form-label">{{ __('text.nombre')}}</label>
                                                <input class="form-control" maxlength="191" type="text" name="inputName" id="inputName" value="{{$usuario['nombre']}}">
                                            </div>
                                        </div>
{{-- Fin de Columna de foto y datos principales --}}

{{-- ***************COLUMNA DATOS DE FILIACION *********************** --}}
                                        <div class="form-group row">
                                            <label for="inputApellido1" class="col-form-label">{{ __('text.1apellido')}}</label>
                                            <input class="form-control" maxlength="191" type="text" name="inputApellido1" id="inputApellido1" value="{{$usuario['primerApellido']}}">
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputApellido2" class="col-form-label">{{ __('text.2apellido')}}</label>
                                            <input class="form-control" maxlength="191" type="text" name="inputApellido2" id="inputApellido2" value="{{$usuario['segundoApellido']}}">
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputTelefono" class="col-form-label">{{ __('text.telefono')}}</label>
                                            <input class="form-control" onkeypress="return esNumero(event)" maxlength="191" type="text" name="inputTelefono" id="inputTelefono" value="{{$usuario['telefono']}}">
                                        </div>
                                        <div class="form-group row">
                                            <label for="fnacimiento" class="col-form-label">{{ __('text.fnacimiento')}}</label>
                                            <input data-date-format="d/m/yyyy" required type="text" @if (Auth::user()->hasPermissionTo('socios')) class="form-control datepicker"  data-provide="datepicker" @else readonly class="form-control"  @endif name="fnacimiento" id="fnacimiento" value="{{ date('d/m/Y', strtotime($usuario['fnacimiento'])) }}">
                                        </div>
                                        <div class="form-group row">
                                           <label for="inputsexo" class="col-form-label">{{ __('text.sexo')}}</label>
                                              <select id="inputsexo" name="inputsexo" class="form-control custom-select">
                                                <option value="varon" @if($usuario['sexo'] == "varon") selected @endif>&#128104; Hombre</option>
                                                <option value="mujer" @if($usuario['sexo'] == "mujer") selected @endif>&#128105; Mujer</option>
                                                <option value="nodefinido" @if($usuario['sexo'] == "nodefinido") selected @endif>&#128125; No definido / No quiero decirlo</option>
                                              </select>
                                        </div>
{{-- Fin de Columna de Filiacion--}}
{{-- ** COLUMNA DATOS DE EMAIL Y NOMBREUSUARIO *********************** --}}
                                            <div class="form-group row">
                                                <div class="col-12">
                                                   <label for="inputEmail" class="col-form-label">{{ __('text.email')}}</label>
                                                    <input class="form-control" aria-describedby="emailHelp" maxlength="191" type="email" name="inputEmail" id="inputEmail" value="{{$usuario['email']}}">
                                                </div>
                                                <div class="col-12">
                                                   <label for="inputUsername" class="col-form-label">{{ __('text.username')}}</label>
                                                      <input class="form-control" type="text" maxlength="191" name="inputUsername" id="inputUsername" value="{{$usuario['username']}}">
                                                </div>
                                            </div>
{{-- Fin de Columna de email y nombreusuario--}}
{{-- COLUMNA DIRECCION LOCALIDAD PROVINCIA,INVITACIONES Y CONTRASEÑA *********************** --}}
                                            <div class="form-group row">
                                                <div class="col-12 col-md-12 col-sd-12 col-xs-12">
                                                    <label for="inputDireccion" class="col-form-label">{{ __('text.direccion')}}</label>
                                                    <input class="form-control" type="text" maxlength="191" name="inputDireccion" id="inputDireccion" value="{{$usuario['direccion']}}">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <label for="localidad" class="col-form-label">{{ __('text.localidad')}}</label>
                                                    <input class="form-control" type="text" maxlength="191" name="inputLocalidad" id="inputLocalidad" value="{{$usuario['localidad']}}">
                                                </div>
                                                <div class="col-12">
                                                    <label for="provincia" class="col-form-label">{{ __('text.provincia')}}</label>
                                                    <input type='text' id="inputProvincia" maxlength="191" name="inputProvincia" class="form-control" value="{{$usuario['provincia']}}"/>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="form-group row">
                                                <div class="col-md-auto">
                                                   <strong><label for="password" class="col-form-label">{{ __('text.cambiaPassword')}}</label></strong>
                                               </div>
                                                <div class="col col-lg">
                                                       <div class="input-group">
                                                          <input id="passwordText" name="passwordText" maxlength="25" type="password" class="form-control pwd" placeholder="{{ __('text.nuevaPassword')}}">
                                                          <span class="input-group-btn">
                                                            <button class="btn btn-default reveal" type="button">&#x1f441;</button>
                                                          </span>
                                                        </div>
                                                        <input id="password" type="hidden" class="form-control" name="password" value="{{$usuario['password']}}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-auto">
                                                   <strong><label for="invitaciones" class="col-form-label">{{ __('text.numInvitaciones')}}</label></strong>
                                                </div>
                                                <div class="col">
                                                    <input id="invitaciones" name="invitaciones" type="text" readonly class="form-control" value="{{$usuario['invitaciones']}}">
                                                </div>
                                                <div class="col">
                                                    <a href="#" class="btn btn-outline-success" data-toggle="modal" data-target="#gastarInvitacion_modal">
                                                        {{ __('text.btnInvitacion')}}
                                                    </a>
                                                </div>
                                            </div>
{{--Fin de Columna de Direccion, Localidad, Provincia, Invitaciones y Çontraseña--}}
                                            <hr>
{{-- ************************PREGUNTAS DE PRIVACIDAD *********************** --}}
                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="form-group row">
                                                        <strong><label class="col-form-label">{{ __('text.preguntasPrivacidad')}}</label></strong>
                                                    </div>
                                                    <div class="form-group row input-group">
                                                        <label class="col-form-label" for="recibirCorreos">{{ __('text.checkEmails')}} &nbsp;&#8688;&nbsp;</label>
                                                        <select class="form-control" id="recibirCorreos" name="recibirCorreos">
                                                            <option value="1" @if($usuario['recibirCorreos']) selected @endif>&#9989; {{__('text.si')}}</option>
                                                            <option value="0" @if(!$usuario['recibirCorreos']) selected @endif>&#10060; {{__('text.no')}}</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group row input-group">
                                                        <label class="col-form-label" for="privacidad">{{ __('text.checkPrivacidad')}} &nbsp;&#8688;&nbsp;</label>
                                                        <select class="form-control" id="privacidad" name="privacidad">
                                                            <option value="1" @if($usuario['privacidad']) selected @endif>&#9989; {{__('text.si')}}</option>
                                                            <option value="0" @if(!$usuario['privacidad']) selected @endif>&#10060; {{__('text.no')}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
{{-- ********Fin de Preguntas de Privacidad *********************** --}}
                                            <hr>
{{-- ************************ASIGNACIOON DE CUOTA *********************** --}}
                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="form-group row">
                                                        <strong><label class="col-form-label">{{ __('text.asignacionSocio')}}</label></strong>
                                                    </div>
                                                    <div class="form-group row input-group">
                                                        <label class="col-form-label" for="asignacionSocio">{{ __('text.eligeAsignacionSocio')}} &nbsp;&#8688;&nbsp;</label>
                                                        <select class="form-control" id="asignacionSocio[]" name="asignacionSocio[]" multiple>
                                                            @foreach ($vocalias as $vocalia)
                                                                <option value="{{$vocalia['id']}}" @foreach ($as as $asi) @if($asi['idVocalia']==$vocalia['id']) selected @endif @endforeach style="width: 50%;">
                                                                    {{$vocalia['nombre']}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
{{--Fin de Columna Asignacion de Cuota***************************************** --}}
                                            <hr>
{{-- COLUMNA DE ULTIMA CUOTA PAGADA***************************************** --}}
                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="form-group row">
                                                        <label class="col-form-label">{{ __('text.socioUltimaCuota')}}</label>
                                                    </div>
                                                    <div class="form-group row input-group">
                                                        <div class="col-12">
                                                            <div class="input-group mb-3">
                                                                <label for="cuotaRDsocio" class="col-form-label">{{ __('text.tipoCuota')}}</label>
                                                                <input class="form-control" type="text" readonly name="cuotaRDsocio" value="{{$cuota['tipoCuota']}}" style="background-color: white; border-style: none; !important">
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="input-group mb-3">
                                                                <label for="cuotaRDsocio" class="col-form-label">{{ __('text.cantidadCuota')}}</label>
                                                                <input class="form-control" type="text" readonly name="cuotaRDsocio" value="{{$cuota['cantidad']}} {{ __('text.simbDin')}}" style="background-color: white; border-style: none; !important">
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="input-group mb-3">
                                                                <label for="cuotaDsocio" class="col-form-label">{{ __('text.fechaCuota')}}</label>
                                                                <input class="form-control" type="text" data-date-format="d/m/yyyy" readonly name="cuotaDsocio" value="{{ date('d/m/Y', strtotime($cuota['fechaCuota']))}}" style="background-color: white; border-style: none; !important">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row input-group">
                                                        <div class="input-group mb-3">
                                                            <label for="cuotaRenovacionDsocio" class="col-form-label"><strong>{{ __('text.fechaRenovacion')}}&nbsp;&nbsp;&#128198;&nbsp;&nbsp;</strong></label>
                                                            <input type="text" data-date-format="d/m/yyyy" readonly name="cuotaRenovacionDsocio" value="{{ date('d/m/Y', strtotime($fechaRenovacionCuota))}}" style="width: auto; background-color: white; border-style: none; !important">
                                                            <label for="cuotaRDsocio" class="col-form-label">{{__('text.emailRecordando')}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
{{--Fin de Columna de Ultima Cuota Pagada*****************************************--}}
                                            <hr>
{{-- SECCIÓN DEL SECRETARIO**********************************************************************************  --}}
                                            @if (Auth::user()->hasPermissionTo('permiso_secretaria'))
                                                <h3>{{ __('text.SeccionSecretario')}}</h3>
                                                <div class="form-group row">
                                                    <label for="roles">{{ __('text.cambiaRoles')}}</label>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-12">
                                                        <select id="roles" name="roles" class="custom-select">
                                                          @foreach ($posiblesRoles as $posiblesRol)
                                                                <option value="{{$posiblesRol['id']}}" @if($roles == $posiblesRol->name) selected  @endif onclick="colapsar()" onchange="colapsar()">
                                                                    @if($posiblesRol->name == "admin")&#9484; {{__('text.administrador')}}
                                                                    @elseif ($posiblesRol->name == "secretario") &#9500;&#9472;&#9472; {{__('text.secretario')}}
                                                                    @elseif ($posiblesRol->name == "tesorero") &#9500;&#9472;&#9472; {{__('text.tesorero')}}
                                                                    @elseif ($posiblesRol->name == "junta")&#9500;&#9472;&#9472;&#9472;&#9472;&#9472; {{__('text.miembrodejunta')}}
                                                                    @elseif ($posiblesRol->name == "vocal") &#9500;&#9472;&#9472;&#9472;&#9472;&#9472; {{__('text.vocal')}}
                                                                    @else &#9500;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472; {{__('text.socio')}}
                                                                    @endif
                                                                </option>
                                                          @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-12 collapse">
                                                        <select id="vocaliaSelect" name="vocaliaSelect" class="custom-select form-control">
                                                          @foreach ($vocalias as $vocalia)
                                                                <option value="{{$vocalia['id']}}" @if($vocalia['nombre'] == $vocaliaSelect) selected @endif>
                                                                    {{$vocalia['nombre']}}
                                                                </option>
                                                          @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <br><hr>

                                                <div class="form-group row">
                                                    <h6><label >{{ __('text.propiedadesSocio')}}</label></h6>
                                                </div>
                                                <div class="form-group col-12">
                                                    <div Class='row'>
                                                        <div class="input-group">
                                                            <button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" disabled data-placement="top" title="{{ __('text.txtHabilitado')}}">
                                                                ?
                                                            </button>
                                                           <label for="inputHabilitado" class="form-control"><strong>{{ __('text.habilitado')}}</strong></label>
                                                              <select id="inputHabilitado" name="inputHabilitado" class="form-control custom-select" class="form-control">
                                                                <option value="1" @if($usuario['habilitado'] == true) selected @endif> {{__('text.si')}} </option>
                                                                <option value="0" @if($usuario['habilitado'] == false) selected @endif> {{__('text.no')}} </option>
                                                              </select>
                                                          </div>
                                                    </div>

                                                    <div Class='row'>
                                                        <div class="input-group">
                                                            <button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" disabled data-placement="top" title="{{ __('text.txtAccesoDrive')}}">
                                                                ?
                                                            </button>
                                                           <label for="inputaccesoDrive" class="form-control">{{ __('text.accesoDrive')}}</label>
                                                              <select id="inputaccesoDrive" name="inputaccesoDrive" class="form-control custom-select" class="form-control">
                                                                <option value="1" @if($usuario['accesoDrive'] == true) selected @endif> {{__('text.si')}} </option>
                                                                <option value="0" @if($usuario['accesoDrive'] == false) selected @endif> {{__('text.no')}} </option>
                                                              </select>
                                                          </div>
                                                    </div>

                                                    <div Class='row'>
                                                        <div class="input-group">
                                                            <button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" disabled data-placement="top" title="{{ __('text.txtAccesoJunta')}}">
                                                                ?
                                                            </button>
                                                           <label for="inputAccesoJunta" class="form-control" >{{ __('text.accesoJunta')}}</label>
                                                              <select id="inputAccesoJunta" name="inputAccesoJunta" class="form-control  custom-select" >
                                                                <option value="1" @if($usuario['accesoJunta'] == true) selected @endif> {{__('text.si')}} </option>
                                                                <option value="0" @if($usuario['accesoJunta'] == false) selected @endif> {{__('text.no')}} </option>
                                                              </select>
                                                          </div>
                                                    </div>
                                                </div>
                                                <br><hr>
                                                <div class="form-group row">
                                                    <h6><span class="badge badge-pill badge-dark">{{ __('text.notas')}}</span>
                                                    {{ __('text.txtNotas')}}</h6>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-12">
                                                      <textarea class="form-control" id="txtNotas" maxlength="600" name="txtNotas" rows="4"><?php echo $usuario['notas'] ?></textarea>
                                                    </div>
                                                </div>
                                                <br>
                                                <hr>
                                            @endif
{{-- Fin de la Seccion del Secretario --}}

{{-- SECCIÓN DEL TESORERO   **********************************************************************************  --}}
                                            @if (Auth::user()->hasPermissionTo('permiso_tesoreria'))
                                                <h3>{{ __('text.SeccionTesorero')}}</h3>
                                                <div class="form-group row">
                                                    <strong><label">{{ __('text.cuotaSocio')}}</label></strong>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="actualizaCuotaCheck" name="actualizaCuotaCheck" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" unchecked>
                                                    <label class="form-check-label" for="actualizaCheck">
                                                        {{__('text.nuevaCuotaCheck')}}
                                                    </label>
                                                </div>
                                                <div class="form-group row collapse" id="collapseExample">
                                                        <div class="col-12">
                                                            <label for="tipoCuota">{{ __('text.tipoCuota')}}</label>
                                                            <select id="tipoCuota" name="tipoCuota" class="select custom-select">
                                                                @foreach ($tiposCuota as $tp)
                                                                        <option @if(!isset($cuota['id']) && $cuota['id'] == $tp['id']) selected @endif value="{{$tp['id']}}" data-cant="{{$tp['cantidad']}}">{{$tp['nombre']}} ({{$tp['cantidad']}}{{ __('text.simbDin')}})</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-12">
                                                            <label for="cantidadCuota">{{ __('text.cantidadCuota')}}</label>
                                                            <input class="form-control" type="text" name="cantidadCuota" id="cantidadCuota" onkeypress="return esNumero(event)" value="{{$cuota['cantidad']}}">
                                                        </div>
                                                        <div class="col-12">
                                                            <label for="fechaCuota">{{ __('text.fechaCuota')}}</label>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <input type='text' data-provide="datepicker" data-date-format="d/m/yyyy" id="fechaCuota" name="fechaCuota" required class="form-control datepicker" value="{{ date('d/m/Y', strtotime($cuota['fechaCuota']))}}" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                                <hr>
                                            @endif
{{-- Fin de la Seccion del Tesorero --}}

                                            <div class="form-group row justify-content-md-center">
                                                <div class="col-12">
                                                    <button class="btn btn-success form-control" type="submit" data-toggle="modal" data-target="#trabajando_modal">
                                                        <strong>{{ __('text.btnGuardar')}}</strong>
                                                    </button>
                                                </div>
                                            </div> {{--Fin de Boton general de Guardar Ficha--}}
                                        </form>  {{-- Fin de Formulario --}}
                                    </div>
                                </div>
                            </div>
                        </div>


<!-- Modal Gastar Invitacion-->
<div id="gastarInvitacion_modal" class="modal fade bd-example-modal-sm" role="dialog">
    <div class="container-fluid">
        <!-- Modal content-->
        <div class="modal-content col-12">
            <div class="modal-body">
                <label class="label-info">{{ __('text.gestionInvitaciones') }}</label>
                <hr>
                <form class="form-horizontal" method="POST" action="{{ route('gastarInvitacion') }}">
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <input class="form-control" type="text" readonly name="idSocio" id="idSocio" value="{{$usuario['id']}}" style="display: none !important;">
                    </div>
                    <div class="row">
                        <label for="fechaInvitacion" class="col-form-label">{{ __('text.fechaInvitacion')}}</label>
                    </div>
                    <div class="row">
                            <input type='text' data-provide="datepicker" data-date-format="d/m/yyyy" id="fechaInvitacion" name="fechaInvitacion" required class="form-control col-10 datepicker" value="{{ date('d/m/Y')}}" />
                    </div>
                    <div class="row">
                        <label for="invitado" class="col-form-label">{{ __('text.invitado')}}</label>
                    </div>
                    <div class="row">
                            <input type='text' id="invitado" required class="form-control col-10" name="invitado" placeholder="{{__('text.invitadotxtMovil')}}">
                    </div>
                    <hr>
                    <div class="form-group row">
                        <button class="btn btn-success form-control" type="submit">
                            {{ __('text.btnInvitacion') }}
                        </button>
                    </div>
                    <div class="form-group row">
                        <button type="button" class="btn btn-secondary form-control" data-dismiss="modal">
                            {{ __('text.btnClose')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--FIN Modal Gastar Invitacion-->

                    </div> {{-- CONTAINER de la ficha--}}
                </div>
            </div>
        </div>
    </div> {{-- FIN DE CONTAINER PRINCIPAL DE PÁGINA--}}

<script type="text/javascript">

    function colapsar(argument) {
        var sel = document.getElementById('roles');
        var opt = sel.options[sel.selectedIndex];
        if(sel.value == "6" || opt.text == "├───── Vocal"){
            console.log("muestra");
            $('.collapse').collapse('show');
        }
        else{
            console.log("oculta");
            $('.collapse').collapse('hide');
        }
    }

    $(document).ready(function(){
        colapsar();
        $(".select").change(function () {
            nuevaCant = $(this).children(':selected').data('cant');
            document.getElementById("cantidadCuota").value = nuevaCant;
        });

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

@endsection
