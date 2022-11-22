<form action="{{ route('actualizaCuota') }}" method="POST">
    {{ csrf_field() }}
    <div class="col-12">
	    <div class="form-row">
	        <div class="col">
	        	<input class="form-control" type="text" name="id" id="id" value="{{$cuota->id}}" style="display: none !important;">
	            <label for="tipoCuota">{{ __('text.tipoCuota')}}</label>
	            <select id="tipoCuota" name="tipoCuota" class="selectpicker form-control" style="width: 100%; display: block !important;">
	                @foreach ($tiposCuota as $tp)
                            <option @if($cuota['id'] == $tp['id']) selected @endif value="{{$tp['id']}}" data-cant="{{$tp['cantidad']}}">{{$tp['nombre']}} ({{$tp['cantidad']}}{{ __('text.simbDin')}})</option>
                    @endforeach
	            </select>
	        </div>
	        <div class="col">
	            <label for="cantidadCuota">{{ __('text.cantidadCuota')}}</label>
	            <input class="form-control" type="text" name="cantidadCuota" id="cantidadCuota" onkeypress="return esNumero(event)" value="{{$cuota->cantidad}}">
	        </div>
	    </div>
	    <br>
	    <div class="form-row">
	        <div id="seleccionarSocios" class="col">
	            <label for="socioSelect">{{ __('text.socio')}} <small>{{ __('text.MVcreacuot')}}</small>:</label>
	            <select class="selectpicker form-control" data-done-button="true" data-live-search="true" id="socioSelect" name="socioSelect" style="width: 100%; display: block !important;">
	                @foreach ($socios as $socio)
	                    <option value="{{$socio['id']}}" @if($socioEdit->id == $socio['id']) selected @endif >({{$socio['numSocio']}}) - {{$socio['nombre']}} {{$socio['primerApellido']}} {{$socio['segundoApellido']}}</option>
	                @endforeach
	            </select>
	        </div>
	        <div class="col">
	            <label for="fechaCuota">{{ __('text.fechaCuota')}}</label>
	            <input type='text' data-provide="datepicker" data-date-format="d/m/yyyy" id="fechaCuota" name="fechaCuota" required class="form-control datepicker" value="{{($cuota->fechaCuota)->format('d/m/Y')}}" />
	        </div>
	    </div>
	</div>
	 <br><hr>
            <div class="form-group row justify-content-md-center">
                <div class="col-md-auto">
                    <button class="btn btn-success form-control" type="submit" data-toggle="modal" data-target="#trabajando_modal">
                        {{ __('text.btnGuardar')}}
                    </button>
                </div>
                <div class="col-md-auto">

                </div>
                <div class="col-md-auto">
                    <button type="button" class="btn btn-secondary form-control" data-dismiss="modal">
                        {{ __('text.btnClose')}}
                    </button>
                </div>
            </div>
</form>


<script type="text/javascript">
    $(document).ready(function() {
    	$('socioSelect').selectpicker();
		$('.datepicker').datepicker({
           todayBtn: 'linked',
           language: 'es',
           autoclose: true,
           todayHighlight: true,
           format: 'd/m/yyyy'
        });
    });
</script>
