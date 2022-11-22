<div class="form-group row">
    <div class="col-6">
        <input class="form-control" type="text" readonly name="inputIdSocio" id="inputIdSocio" value="{{$usuario['id']}}" style="display:none; !important">
        <div class="row">
            <div class="col align-self-center center">
                <img src="{{ asset('images/fotos/'.$foto) }}" alt="Foto del socio" style="float:left; margin-right:25px; max-width: 300px !important"/>
            </div>
        </div>
        <div class="row" style="margin-top:20px;">
            <div class="col align-self-center">
                <label for="fotoPerfil">{{ __('text.cambiaFoto')}}</label>
            </div>
        </div>
        <div class="row">
            <div class="col align-self-center">
                <input type="file" name="fotoPerfil">
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group row">
            <div class="col">
                <label for="inputNumsocio" class="col-form-label">{{ __('text.numsocio')}}</label>
                <input class="form-control" type="text" maxlength="4" readonly name="inputNumsocio" id="inputNumsocio" value="{{$usuario['numSocio']}}">
            </div>
        </div>
        <div class="form-group row">
            <div class="col">
                <div class="row">
                    <div class="col">
                        <label for="altaSocio" class="col-form-label">{{ __('text.altaSocio')}}</label>
                        <input data-date-format="d/m/yyyy" required type="text" @if (Auth::user()->hasPermissionTo('permiso_secretaria')) class="form-control datepicker"  data-provide="datepicker" @else readonly class="form-control"  @endif name="altaSocio" id="altaSocio" value="{{ date('d/m/Y', strtotime($usuario['altaSocio'])) }}">
                    </div>
                    <div class="col">
                        <label for="veterano" class="col-form-label">{{ __('text.veterano')}}</label>
                        <input class="form-control" required type="text" readonly name="veterano" id="veterano" value="@if($veterano == true) Si @else No @endif">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-6">
                <label for="inputDNI" class="col-form-label">{{ __('text.DNI')}}</label>
                <input class="form-control" autocomplete="off" required type="text" maxlength="9" @if (Auth::user()->hasPermissionTo('permiso_secretaria')) @else readonly @endif name="inputDNI" id="inputDNI" value="{{$usuario['DNI']}}">
            </div>
            <div class="col-6">
                <label for="fnacimiento" class="col-form-label">{{ __('text.fnacimiento')}}</label>
                <input data-date-format="d/m/yyyy" required type="text" @if (Auth::user()->hasPermissionTo('socios')) class="form-control datepicker"  data-provide="datepicker" @else readonly class="form-control"  @endif name="fnacimiento" id="fnacimiento" value="{{ date('d/m/Y', strtotime($usuario['fnacimiento'])) }}">
            </div>
        </div>
    </div>
</div>
<div class="form-group row">
    <div class="col-4">
        <label for="inputUsername" class="col-form-label">{{ __('text.username')}} ({{__('text.nickname')}})</label>
        <input class="form-control" type="text" autocomplete="off" required maxlength="191" name="inputUsername" id="inputUsername" value="{{$usuario['username']}}">
    </div>
     <div class="col-4">
       <label for="inputsexo" class="col-form-label">{{ __('text.sexo')}}</label>
          <select id="inputsexo" name="inputsexo" class="form-control custom-select">
            <option value="varon" @if($usuario['sexo'] == "varon") selected @endif>&#128104; {{__('text.hombre')}}</option>
            <option value="mujer" @if($usuario['sexo'] == "mujer") selected @endif>&#128105; {{__('text.mujer')}}</option>
            <option value="nodefinido" @if($usuario['sexo'] == "nodefinido") selected @endif>&#128125; {{__('text.nodef')}}</option>
          </select>
    </div>
    <div class="col-4">
        <label for="inputName" class="col-sm-4 col-form-label">{{ __('text.nombre')}}</label>
        <input class="form-control" autocomplete="off" type="text" maxlength="60" name="inputName"  @if (Auth::user()->hasPermissionTo('permiso_secretaria')) @else readonly @endif id="inputName" value="{{$usuario['nombre']}}">
    </div>
</div>
<div class="form-group row">
    <div class="col-6">
        <label for="inputApellido1" class="col-form-label">{{ __('text.1apellido')}}</label>
        <input class="form-control" autocomplete="off" type="text" maxlength="191" name="inputApellido1"  @if (Auth::user()->hasPermissionTo('permiso_secretaria')) @else readonly @endif id="inputApellido1" value="{{$usuario['primerApellido']}}">
    </div>
    <div class="col-6">
        <label for="inputApellido2" class="col-form-label">{{ __('text.2apellido')}}</label>
        <input class="form-control" autocomplete="off" type="text" maxlength="191" name="inputApellido2" id="inputApellido2" value="{{$usuario['segundoApellido']}}">
    </div>
</div>

<div class="form-group row">


</div>

<div class="form-group row">
    <div class="col-md-auto">
       <strong><label for="password" class="col-form-label">{{ __('text.cambiaPassword')}}</label></strong>
   </div>
    <div class="col col-lg">
           <div class="input-group">
              <input id="passwordText" autocomplete="off" name="passwordText" maxlength="25" type="password" class="form-control pwd" placeholder="{{ __('text.nuevaPassword')}}">
              <span class="input-group-btn">
                <button class="btn btn-default reveal" type="button">&#x1f441;</button>
              </span>
            </div>
            <input id="password" type="hidden" class="form-control" name="password" value="{{$usuario['password']}}">
    </div>
</div>
