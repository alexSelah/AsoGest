@extends('movil.layout')

@section('content')

<div class="container">
    <div class="form">
    <form action="{{ route('guardaFichaMovil') }}" method="POST">
        {{ csrf_field() }}
        <h2 class="page_title">Tu Ficha de Socio</h2>
        <input class="form-control" type="text" readonly name="inputIdSocio" id="inputIdSocio" value="{{$usuario['id']}}" style="display:none; !important">
        <div class="image_single radius4"><img src="{{ asset('images/fotos/'.$foto) }}" alt="" title="" border="0" /></div>
        <label>{{ __('text.cambiaFoto')}}</label>
        <input type="file" class="form_input" name="fotoPerfil">

        <br>
        <br>

        <div id="accordion">
		  <div class="card">
		    <div class="card-header" id="headingOne">
		      <h5 class="mb-0">
		        <a class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
		           	&#128375; &nbsp;Datos Básicos
		        </a>
		      </h5>
		    </div>
		    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
		      <div class="card-body">
		        <div >
                    <label>{{ __('text.numsocio')}}:</label>
                    <input type="text" name="numSocio" id="numSocio" value="{{$usuario['numSocio']}}" class="form-control" readonly style="background-color: LightGray !important;"  autocomplete="off" />
                    <br>
                    <label>{{ __('text.altaSocio')}}:</label>
                    <input @if(!Auth::user()->hasRole(['admin','secretario','tesorero'])) readonly style="background-color: LightGray !important;"  @endif class="form-control" type="text" name="altaSocio" id="altaSocio" value="{{\Helper::dimeFechaCarbon($usuario['altaSocio'],5,'-') }}" autocomplete="off">
                    <br>
                    <label>{{ __('text.veterano')}}:</label>
                    <input type="text" name="veterano" id="veterano" value="{{$veterano}}" class="form-control" @if(!Auth::user()->hasRole(['admin','secretario','tesorero'])) readonly style="background-color: LightGray !important;"  @endif autocomplete="off"/>
                    <br>
                    <label>{{ __('text.username')}}:</label>
                    <input type="text" maxlength="191" name="inputUsername" id="inputUsername" value="{{$usuario['username']}}" class="form-control" @if(!Auth::user()->hasRole(['admin','secretario','tesorero'])) readonly style="background-color: LightGray !important;"  @endif autocomplete="off" />
                    <br>
                    <label>{{ __('text.cambiaPassword')}}:</label>
                    <div class="input-group">
                      <input id="passwordText" name="passwordText" maxlength="25" type="password" class="form-control pwd" placeholder="{{ __('text.nuevaPassword')}}" autocomplete="off">
                      <span class="input-group-btn">
                        <button class="btn btn-default reveal" type="button">&#x1f441;</button>
                      </span>
                    </div>
                    <input id="password" type="hidden" class="form-control" name="password" value="{{$usuario['password']}}" style="display:none !important;" autocomplete="off">
                </div>
		      </div>
		    </div>
		  </div>

		  <div class="card">
		    <div class="card-header" id="headingTwo">
		      <h5 class="mb-0">
		        <a class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
		          &#128483; &nbsp;{{__('text.MVdatospersonales')}}
		        </a>
		      </h5>
		    </div>
		    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
		      <div class="card-body">
		        <div >
                    <label>{{ __('text.DNI')}}:</label>
                    <input type="text" name="inputDNI" id="inputDNI" value="{{$usuario['DNI']}}" class="form-control"  @if(!Auth::user()->hasRole(['admin','secretario','tesorero'])) readonly style="background-color: LightGray !important;"  @endif  @if(!Auth::user()->hasRole(['admin','secretario','tesorero'])) readonly style="background-color: LightGray !important;"  @endif autocomplete="off"/>
                    <br>
                    <label>{{ __('text.nombre')}}:</label>
                    <input type="text" name="inputName" id="inputName" value="{{$usuario['nombre']}}" class="form-control"  @if(!Auth::user()->hasRole(['admin','secretario','tesorero'])) readonly style="background-color: LightGray !important;"  @endif  @if(!Auth::user()->hasRole(['admin','secretario','tesorero'])) readonly style="background-color: LightGray !important;"  @endif autocomplete="off"/>
                    <br>
                    <label>{{ __('text.1apellido')}}:</label>
                    <input type="text" name="inputApellido1" id="inputApellido1" value="{{$usuario['primerApellido']}}" class="form-control"  @if(!Auth::user()->hasRole(['admin','secretario','tesorero'])) readonly style="background-color: LightGray !important;"  @endif  @if(!Auth::user()->hasRole(['admin','secretario','tesorero'])) readonly style="background-color: LightGray !important;"  @endif autocomplete="off"/>
                    <br>
                    <label>{{ __('text.2apellido')}}:</label>
                    <input type="text" name="inputApellido2" id="inputApellido2" value="{{$usuario['segundoApellido']}}" class="form-control"  @if(!Auth::user()->hasRole(['admin','secretario','tesorero'])) readonly style="background-color: LightGray !important;"  @endif autocomplete="off"/>
                    <br>
                    <label>{{ __('text.fnacimiento')}}:</label>
                    <input required type="text" @if (Auth::user()->hasPermissionTo('socios')) class="form-control" data-provide="datepicker" @else @if(!Auth::user()->hasRole(['admin','secretario','tesorero'])) readonly @endif class="form-control"  @endif name="fnacimiento" id="fnacimiento" value="{{ date('d/m/Y', strtotime($usuario['fnacimiento'])) }}" autocomplete="off" />
                    <br>
                    <label>{{ __('text.sexo')}}:</label>
                    <select id="inputsexo" name="inputsexo" class="form-control custom-select">
                        <option value="varon" @if($usuario['sexo'] == "varon") selected @endif>&#128104; {{__('text.hombre')}}</option>
                        <option value="mujer" @if($usuario['sexo'] == "mujer") selected @endif>&#128105; {{__('text.mujer')}}</option>
                        <option value="nodefinido" @if($usuario['sexo'] == "nodefinido") selected @endif>&#128125;  {{__('text.nodef')}}</option>
                    </select>
                </div>
		      </div>
		    </div>
		  </div>

		  <div class="card">
		    <div class="card-header" id="headingThree">
		      <h5 class="mb-0">
		        <a class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
		           	&#128388; &nbsp;{{__('text.MVdatoscontact')}}
		        </a>
		      </h5>
		    </div>
		    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
		      <div class="card-body">
		        <div >
                    <label>{{ __('text.telefono')}}:</label>
                    <input type="text" onkeypress="return esNumero(event)" maxlength="191" type="text" name="inputTelefono" id="inputTelefono" value="{{$usuario['telefono']}}" class="form-control" autocomplete="off"/>
                    <br>
                    <label>{{ __('text.email')}}:</label>
                    <input maxlength="191" type="text" name="inputEmail" id="inputEmail" value="{{$usuario['email']}}" class="form-control email" autocomplete="off"/>
                    <br>
                    <label>{{ __('text.direccion')}}:</label>
                    <input type="text" maxlength="191" name="inputDireccion" id="inputDireccion" value="{{$usuario['direccion']}}" class="form-control" autocomplete="off"/>
                    <br>
                    <label>{{ __('text.localidad')}}:</label>
                    <input type="text" maxlength="191" name="inputLocalidad" id="inputLocalidad" value="{{$usuario['localidad']}}" class="form-control" autocomplete="off"/>
                    <br>
                    <label>{{ __('text.provincia')}}:</label>
                    <input type="text" maxlength="191" name="inputProvincia" value="{{$usuario['provincia']}}" class="form-control" autocomplete="off"/>
                    <br>
                    <label>{{ __('text.codPostal')}}:</label>
                    <input type="text" maxlength="191" name="inputCodPostal" id="inputCodPostal" value="{{$usuario['codPostal']}}" class="form-control" autocomplete="off"/>
                </div>
		      </div>
		    </div>
		  </div>

		</div>

		<br>

		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">&#128373;{{__('text.MVpriv')}}</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">&#128440;{{__('text.MVotras')}}</a>
			</li>
			@if(Auth::user()->hasRole(['admin','tesorero','secretario']))
			<li class="nav-item">
				<a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">{{__('text.MVadmin')}}</a>
			</li>
			@endif
		</ul>
		<div class="tab-content" id="myTabContent">
			<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
				<label for="recibirCorreos" class="form-label">{{ __('text.checkEmails')}}</label>
				<select id="recibirCorreos" name="recibirCorreos" class="form-control custom-select col-6">
					<option value="1" @if($usuario['recibirCorreos']) selected @endif>&#9989; {{__('text.si')}}</option>
					<option value="0" @if(!$usuario['recibirCorreos']) selected @endif>&#10060; {{__('text.no')}}</option>
				</select>
				<br>
				<label for="privacidad" class="form-label" >{{ __('text.checkPrivacidad')}}</label>
				<select id="privacidad" name="privacidad" class="form-control  custom-select col-6" >
					<option value="1" @if($usuario['privacidad']) selected @endif>&#9989; {{__('text.si')}}</option>
					<option value="0" @if(!$usuario['privacidad']) selected @endif>&#10060; {{__('text.no')}}</option>
				</select>
			</div>

			<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
				<strong><label for="invitaciones" class="col-form-label">{{ __('text.numInvitaciones')}}</label></strong>
                    <input id="invitaciones" name="invitaciones" type="text" @if(!Auth::user()->hasRole(['admin','secretario','tesorero'])) readonly style="background-color: LightGray !important;"  @endif class="form-control" value="{{$usuario['invitaciones']}}">
                    <br>
                    @if($usuario['invitaciones']>0)
	                    <a href="{{ route('gastarInvitacionMovil',$usuario['id']) }}" class="btn btn-warning btn-sm">
	                        {{ __('text.btnInvitacion')}}
	                    </a>
	                 @else
	                 	<a href="#"  class="btn btn-secondary btn-sm">
	                        {{ __('text.btnNoInvitacion')}}
	                    </a>
	                @endif
                    <br>
                    <strong><label class="col-form-label">{{ __('text.asignacionSocio')}}</label></strong>
                    <label class="col-form-label" for="asignacionSocio">{{ __('text.eligeAsignacionSocio')}} &nbsp;&#8688;&nbsp;</label>
                    <br>
                    <select class="select multiple" id="asignacionSocio[]" name="asignacionSocio[]" multiple style="width: 100% !important;">
                        @foreach ($vocalias as $vocalia)
                            <option value="{{$vocalia['id']}}" @foreach ($as as $asi) @if($asi['idVocalia']==$vocalia['id']) selected @endif @endforeach style="width: 100%;">
                                {{$vocalia['nombre']}}
                            </option>
                        @endforeach
                    </select>


			</div>

			@if(Auth::user()->hasRole(['admin','tesorero','secretario']))
				<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
					<br>
						<div class="input-group">
		                <button type="button" class="btn btn-success btn-sm" data-toggle="tooltip"  data-placement="top" title="{{ __('text.txtHabilitado')}}">
		                    ?
		                </button>
		                <label for="inputHabilitado" class="form-control"><strong>{{ __('text.habilitado')}}</strong></label>
		                  <select id="inputHabilitado" name="inputHabilitado" class="form-control custom-select" class="form-control">
		                    <option value="1" @if($usuario['habilitado'] == true) selected @endif> Si </option>
		                    <option value="0" @if($usuario['habilitado'] == false) selected @endif> No </option>
		                  </select>
		                </div>
		                <br>
		                <div class="input-group">
		                <button type="button" class="btn btn-info btn-sm" data-toggle="tooltip"  data-placement="top" title="{{ __('text.txtAccs1')}}">
		                    ?
		                </button>
		                <label for="inputaccesoDrive" class="form-control">{{ __('text.accs1')}}</label>
		                  <select id="inputaccesoDrive" name="inputaccesoDrive" class="form-control custom-select" class="form-control">
		                    <option value="1" @if($usuario['accesoDrive'] == true) selected @endif> {{__('text.si')}} </option>
		                    <option value="0" @if($usuario['accesoDrive'] == false) selected @endif> {{__('text.no')}} </option>
		                  </select>
		                </div>
		                <br>
		                <div class="input-group">
		                <button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip"  data-placement="top" title="{{ __('text.txtAccs2')}}">
		                    ?
		                </button>
		                <label for="inputAccesoJunta" class="form-control" >{{ __('text.accs1')}}</label>
		                  <select id="inputAccesoJunta" name="inputAccesoJunta" class="form-control  custom-select" >
		                    <option value="1" @if($usuario['accesoJunta'] == true) selected @endif> {{__('text.si')}} </option>
		                    <option value="0" @if($usuario['accesoJunta'] == false) selected @endif> {{__('text.no')}} </option>
		                  </select>
		                </div>
					<br>
					<h5>{{ __('text.SeccionTesorero')}}</h5>
	                <strong><label>{{ __('text.cuotaSocio')}}</label></strong>
	                <br><br>
	                <div class="form-check mb-2 mr-sm-2">
	                    <input class="form-check-input" type="checkbox" id="actualizaCuotaCheck" name="actualizaCuotaCheck" unchecked>
	                    <label class="form-check-label" for="actualizaCheck">
	                        {{__('text.nuevaCuotaCheck')}}
	                    </label>
	                </div>
	                <br>
	                <label for="tipoCuota">{{ __('text.tipoCuota')}}</label>
	                <select id="tipoCuota" name="tipoCuota" class="select form-control">
                        <option value="" selected disabled>{{__('text.selectUna')}}</option>
                        @foreach ($tiposCuota as $tp)
                        <option value="{{$tp['id']}}" data-cant="{{$tp['cantidad']}}">{{$tp['nombre']}} ({{$tp['cantidad']}} {{ __('text.simbDin')}})</option>
                @endforeach
	                </select>
	                <br>
	                <label for="cantidadCuota">{{ __('text.cantidadCuota')}}</label>
	                <input class="form-control" type="text" name="cantidadCuota" id="cantidadCuota" onkeypress="return esNumero(event)" value="">
	                <br>
	                <label for="fechaCuota">{{ __('text.fechaCuota')}}</label>
	                <input type='date' id="fechaCuota" name="fechaCuota" class="form-control" value="{{ date('Y-m-d', strtotime($cuota['fechaCuota']))}}" />
				</div>
			@endif
		</div>

		<br><hr>

        <input type="submit" name="submit" class="btn btn-success btn-block" value="GUARDAR" />
        <br>
        <div class="clearfix"></div>
			<div class="scrolltop radius20"><a href="#"><img src="{{ asset('movil/images/icons/top.png')}}" alt="Ir arriba" title="Ir arriba" /></a></div>
    </form>
    </div>
</div>
<!--End of page container-->

<script type="text/javascript">
    $(".select").change(function () {
        nuevaCant = $(this).children(':selected').data('cant');
        document.getElementById("cantidadCuota").value = nuevaCant;
    });
</script>



<noscript>Please, enable JavaScript to see this page. Por favor, habilita JavaScript para ver esta página.</noscript>
@endsection
