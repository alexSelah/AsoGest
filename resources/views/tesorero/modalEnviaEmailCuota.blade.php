<form action="{{ route('enviaEmail') }}" method="POST">
    {{ csrf_field() }}
    <div class="col-12">
	    <div class="form-row">
	        <div class="col">
	        	<input class="form-control" type="text" name="id" id="id" value="{{$cuota->id}}" style="display: none !important;">
	        	<input class="form-control" type="text" name="idSocio" id="idSocio" value="{{$socio->id}}" style="display: none !important;">
	            <label><strong>{{ __('text.txtEmailCuota1')}}:</strong> {{$socio->dimeNombre()}}</label>
	        </div>
	    </div>
	    <div class="form-row">
		        <div class="col-4">
		            <label><strong>{{ __('text.txtEmailCuota2')}}:</strong></label>
		        </div>
		        <div class="col-8">
		            <div class="row">
		            	<label>{{ __('text.fechaCuota')}}: {{$cuota->fechaCuota->format('Y-m-d')}}</label>
		            </div>
		            <div class="row">
		            	<label>{{ __('text.tipoCuota')}}: {{$cuota->tipoCuota}}</label>
		            </div>
		            <div class="row">
		            	<label>{{ __('text.cantidad')}}: {{$cuota->cantidad}} {{__('text.simbDin')}}</label>
		            </div>
		        </div>
	    </div>
	    <br>
	    <div class="form-row">
	        <div class="col">
	            <label for="socioSelect"><strong>{{ __('text.txtEmailCuota3')}}:</strong></label>
	            <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <textarea id="summernote" name="mensaje" style="white-space: pre; min-width: 100%; background-color: white;" required="required" data-error="{{__('errortext.porfaEscrMens')}}"></textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>
	        </div>
	    </div>
	</div>
	 <br><hr>
    <div class="form-group row justify-content-md-center">
        <div class="col-md-auto">
            <button class="btn btn-success form-control" type="submit" data-toggle="modal" data-target="#trabajando_modal">
                {{ __('text.enviaremail')}}
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

        $('#summernote').summernote({
            lang: 'es-ES',
            placeholder: '',
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

    });
</script>
