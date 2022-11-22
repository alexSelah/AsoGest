@extends('movil.layout')

@section('content')

<div class="container">

<p class="">{{ __('text.nuevoApunte') }}</p>
<hr>
<form action="{{ route('nuevoApunteMovil') }}" method="POST">
    {{ csrf_field() }}
    <div class="form-row">
        <div class="col-6">
            <label for="numAsiento">{{__('text.nuevoApunte')}}</label>
            <input type="text" class="form-control" id="numAsiento" name="numAsiento" value="{{$ultimoAsiento}}" disabled required>
        </div>
        <div class="col-6">
            <label for="fecha">{{__('text.fecha')}}</label>
            <input type="date" class="form-control" id="fecha" name="fecha" required value="<?php echo date('Y-m-d'); ?>" >
        </div>
    </div>
    <br>
    <div class="form-row">
        <div class="col-6">
            <label for="year">{{__('text.anio')}}</label>
            <input type="text" class="form-control" id="year" name="year" value="<?php echo date("Y"); ?>" required maxlength="4" onkeypress="return esNumero(event)">
        </div>
        <div class="col-6" id="divTipo">
           <label for="tipo">Tipo</label>
           <select class="custom-select" id="tipo" name="tipo" required>
                <option value="Gasto" selected><span style="background-color: red !important;">&#8722; {{__('text.gasto')}}</span></option>
                <option value="Ingreso"><span style="color: green !important;">&#43; {{__('text.ingreso')}}</span></option>
            </select>
        </div>
    </div>
    <br>
    <div class="form-row">
        <div class="col-12" id="divCA">
            <label for="conceptoAgrupado">{{__('text.conceptoAgrupado')}}</label>
            <select class="selectpicker form-control" data-live-search="true" name="conceptoAgrupado" id="conceptoAgrupado" onclick="colapsarSocios()" onchange="colapsarSocios()">
                @foreach($conceptosAgrupados as $concepto)
                    <option value="{{$concepto}}">{{$concepto}}</option>
                @endforeach
            </select>
        </div>
        <div id="seleccionarSocios" class="col-12 collapse show">
            <label for="socioSelect">{{__('text.socio')}} <small>({{__('text.nuevaCuota')}})</small>:</label>
            <select class="selectpicker form-control w-100" data-live-search="true" id="socioSelect" name="socioSelect" style="width: 100%">
                @foreach ($socios as $socio)
                    <option value="{{$socio['id']}}">({{$socio['numSocio']}}) - {{$socio['nombre']}} {{$socio['primerApellido']}} {{$socio['segundoApellido']}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <br>
    <div class="form-row">
        <div class="col-12">
            <label for="descripcion">{{__('text.descripcion')}}:</label>
            <input type="text" class="form-control" id="descripcion" name="descripcion"  maxlength="191" required>
        </div>
    </div>
    <br>
    <div class="form-row">
        <div class="col-md-4 mb-3">
            <label for="vocalia">{{__('text.vocalia')}}</label>
            <select class="selectpicker form-control" data-live-search="true" name="vocalia" id="vocalia">
                @foreach($vocalias as $vocalia)
                        <option value="{{$vocalia}}">{{$vocalia}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label for="cantidad">{{__('text.cantidad')}}</label>
            <div class="input-group">
                <input type="text" class="form-control" id="cantidad" name="cantidad" maxlength="9" placeholder="Solo números" onkeypress="return esNumero(event)">
                <div class="input-group-prepend">
                    <div class="input-group-text">{{ __('text.simbDin')}}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
           <label for="pagcob">{{__('text.pagcob')}}</label>
           <select class="custom-select" id="pagcob" name="pagcob" required>
                <option value="Si">&#9745;</span> {{__('text.si')}}</option>
                <option value="No" selected>&#9746;</span> {{__('text.no')}}</option>
            </select>
        </div>
    </div>
    <br>
    <div class="form-row w-100">
        <h6><span class="badge badge-pill badge-primary">{{ __('text.notas')}}</span></h6>
        <div class="col align-self-center">
          <textarea class="form-control" id="notas" maxlength="600" name="notas" rows="3"></textarea>
        </div>
    </div>

    <hr>
    <br>
    <div class="form-group row justify-content-md-center">
        <div class="col-md-auto">
            <button class="btn btn-success form-control" type="submit">
                {{ __('text.btnGuardar')}}
            </button>
        </div>
    </div>
</form>

</div>

<script type="text/javascript">
	function colapsarSocios(argument) {
        var sel = document.getElementById('conceptoAgrupado');
        if(sel!=null){
            var opt = sel.options[sel.selectedIndex];
            if(opt.text == "Ingreso cuotas"){
                document.getElementById("seleccionarSocios").classList.add('show');
                document.getElementById("seleccionarSocios").classList.remove('hide');
            }
            else{
                //$('.collapse').collapse('hide');
                document.getElementById("seleccionarSocios").classList.add('hide');
                document.getElementById("seleccionarSocios").classList.remove('show');
            }
        }
    }
	// Concept: Render select2 fields after all javascript has finished loading
    var initSelect2 = function(){
        // function that will initialize the select2 plugin, to be triggered later
        var renderSelect = function(){
            $('section#formSection select').each(function(){
                $(this).select2({
                    'dropdownCssClass': 'dropdown-hover',
                    'width': '',
                    'minimumResultsForSearch': -1,
                });
            })
        };
        // create select2 HTML elements
        var style = document.createElement('link');
        var script = document.createElement('script');
        style.rel = 'stylesheet';
        style.href = 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css';
        script.type = 'text/javascript';
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.full.min.js';
        // trigger the select2 initialization once the script tag has finished loading
        script.onload = renderSelect;
        // render the style and script tags into the DOM
        document.getElementsByTagName('head')[0].appendChild(style);
        document.getElementsByTagName('head')[0].appendChild(script);
    };
    initSelect2();

    $(document).ready(function() {
        colapsarSocios();
    });
</script>


<noscript>Please, enable JavaScript to see this page. Por favor, habilita JavaScript para ver esta página.</noscript>
@endsection
