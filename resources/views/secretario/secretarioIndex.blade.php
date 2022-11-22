@extends('layouts.app')

@section('content')

<div class="container-fluid">
@if(Auth::user()->hasRole(['admin','secretario','tesorero']))
    <br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="button">
                          <a class="nav-link" href="{{ route('visorInvitaciones') }}">
                            &#128108;&nbsp;{{ __('text.vistaInvitaciones') }}
                          </a>
                        </div>
                        <div class="button">
                          <a class="nav-link" href="{{ route('vocaliasTesorero') }}">
                            &#127917;&nbsp;{{ __('text.gestionVocalias') }}
                          </a>
                        </div>
                        <div class="button" data-toggle="modal" data-target="#importarExcel_modal">
                          <a class="nav-link" href="#">
                            &#127775;&nbsp;{{ __('text.importarExcelSocios') }}
                          </a>
                        </div>
                        <div class="button">
                          <a class="nav-link" href="{{ url('/descargaExcelSocios') }}">
                              &#128317;&nbsp;{{ __('text.descargarPlantillaSocios') }}
                          </a>
                        </div>

                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                       <strong>{{ __('text.listaSocios') }}</strong>
                        <a href="{{ route('home') }}"><input type="button" class="btn btn-outline-secondary btn-sm" value="{{ __('text.back')}}" /></a>
                    </div>
                     <br>
                {{-- <div class="card-body"> --}}
                    {{ __('text.listadoSociosTxt') }}
                    <br/>
                    <hr>

                        <table class="table table-striped table-bordered text-center" id="tablaSocios" style="min-width: 100%;">
                            <thead class="thead-light">
                                <tr>
                                    <th data-priority="4">{{ __('text.foto')}}</th>
                                    <th data-priority="1">{{ __('text.numsocio')}}</th>
                                    <th data-priority="3">{{ __('text.alta')}}</th>
                                    <th data-priority="3">{{ __('text.baja')}}</th>
                                    <th data-priority="2">{{ __('text.nombre')}}</th>
                                    <th data-priority="4">{{ __('text.1apellido')}}</th>
                                    <th data-priority="5">{{ __('text.2apellido')}}</th>
                                    <th data-priority="3">{{ __('text.fnacimiento')}}</th>
                                    <th data-priority="2">{{ __('text.DNI')}}</th>
                                    <th data-priority="4">{{ __('text.email')}}</th>
                                    <th data-priority="4">{{ __('text.telefono')}}</th>
                                    <th data-priority="2">{{ __('text.activo')}}</th>
                                    <th data-priority="7">{{ __('text.direccion')}}</th>
                                    <th data-priority="6">{{ __('text.localidad')}}</th>
                                    <th data-priority="6">{{ __('text.provincia')}}</th>
                                    <th data-priority="3">{{ __('text.username')}}</th>
                                    <th data-priority="5">{{ __('text.sexo')}}</th>
                                    <th data-priority="6">{{ __('text.accs1')}}</th>
                                    <th data-priority="6">{{ __('text.accs2')}}</th>
                                    <th data-priority="7">{{ __('text.notas')}}</th>
                                    <th data-priority="1">{{ __('text.modificar')}}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>{{ __('text.foto')}}</th>
                                    <th>{{ __('text.numsocio')}}</th>
                                    <th>{{ __('text.alta')}}</th>
                                    <th>{{ __('text.baja')}}</th>
                                    <th>{{ __('text.nombre')}}</th>
                                    <th>{{ __('text.1apellido')}}</th>
                                    <th>{{ __('text.2apellido')}}</th>
                                    <th>{{ __('text.fnacimiento')}}</th>
                                    <th>{{ __('text.DNI')}}</th>
                                    <th>{{ __('text.email')}}</th>
                                    <th>{{ __('text.telefono')}}</th>
                                    <th>{{ __('text.activo')}}</th>
                                    <th>{{ __('text.direccion')}}</th>
                                    <th>{{ __('text.localidad')}}</th>
                                    <th>{{ __('text.provincia')}}</th>
                                    <th>{{ __('text.username')}}</th>
                                    <th>{{ __('text.sexo')}}</th>
                                    <th>{{ __('text.accs1')}}</th>
                                    <th>{{ __('text.accs2')}}</th>
                                    <th>{{ __('text.notas')}}</th>
                                    <th>{{ __('text.modificar')}}</th>
                                </tr>
                            </tfoot>
                        </table>

                        @include('secretario.modalesSecre')

                   @else
						<div class="container">
						<div class="row justify-content-center">
							<div class="col-md-12">
								<div class="card text-center">
									<img src="{{ asset('public/images/youshallnotpass.gif') }}" alt="No puedes pasar" class="rounded mx-auto d-block" />
									<label><h1><strong>{{ __('text.noAutorizado')}}</strong></h1></label>
								</div>
							</div>
						</div>
					@endif
                {{-- </div> --}}
            </div>
        </div>
    </div>
</div>

@include('secretario.scriptsSecre')

@endsection
