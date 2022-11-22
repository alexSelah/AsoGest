<div class="form-group row">
    <div class="col-12 col-md-12 col-sd-12 col-xs-12">
        <label for="inputDireccion" class="col-form-label">{{ __('text.direccion')}}</label>
        <input class="form-control" type="text" maxlength="191" name="inputDireccion" id="inputDireccion" value="{{$usuario['direccion']}}">
    </div>
</div>

<div class="form-group row">
    <div class="col-6 col-md-6 col-sd-6 col-xs-6">
        <label for="localidad" class="col-form-label">{{ __('text.localidad')}}</label>
        <input class="form-control" autocomplete="off" type="text" maxlength="191" name="inputLocalidad" id="inputLocalidad" value="{{$usuario['localidad']}}">
    </div>
    <div class="col-4 col-md-4 col-sd-4 col-xs-4">
        <label for="provincia" class="col-form-label">{{ __('text.provincia')}}</label>
        <input type='text' autocomplete="off" id="inputProvincia" maxlength="191" name="inputProvincia" class="form-control" value="{{$usuario['provincia']}}"/>
    </div>
    <div class="col-2 col-md-2 col-sd-2 col-xs-2">
        <label for="inpurCodPostal" class="col-form-label">{{ __('text.codPostal')}}</label>
        <input type='text' autocomplete="off" id="inpurCodPostal" maxlength="191" name="inpurCodPostal" class="form-control" value="{{$usuario['codPostal']}}"/>
    </div>
</div>
<br>
<div class="form-group row">
    <div class="col-6">
       <label for="inputEmail" class="col-form-label">{{ __('text.email')}}</label>
        <input class="form-control" autocomplete="off" aria-describedby="emailHelp" type="email" name="inputEmail" id="inputEmail" value="{{$usuario['email']}}">
    </div>
    <div class="col-6">
	   <label for="inputTelefono" class="col-form-label">{{ __('text.telefono')}}</label>
	    <input class="form-control" autocomplete="off" type="text" maxlength="9" name="inputTelefono" id="inputTelefono" value="{{$usuario['telefono']}}" onkeypress="return esNumero(event)">
	</div>
</div>
