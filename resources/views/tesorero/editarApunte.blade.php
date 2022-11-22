@extends('layouts.app')

@section('content')

<div class="container">
    <br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                       <strong>{{ __('text.editaApunte') }}</strong>
                        <a href="{{ route('tesoreroFechas') }}"><input type="button" class="btn btn-outline-secondary btn-sm" value="{{ __('text.back')}}" /></a>
                    </div>
                <div class="card-body">
                    <hr>
                    @if(Auth::user()->hasAnyPermission('Acceso_total', 'permiso_tesoreria'))
                        <form action="{{ route('guardarApunte') }}" method="POST">
                        {{ csrf_field() }}
                            <div class="form-row">
                                <div class="col-md-4 mb-3">
                                    <label for="numAsiento">{{__('text.numAsiento')}}</label>
                                    <input type="text" class="form-control" id="id" name="id" value="{{$asiento->id}}" readonly required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="fecha">{{__('text.fecha')}}</label>
                                    <input class="form-control" id="fecha" name="fecha" value="{{ $asiento->fechaApunte->format('Y-m-d') }}" type="date" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="year">{{__('text.anio')}}</label>
                                    <input type="text" class="form-control" id="year" name="year" value="{{$asiento->año}}" required maxlength="4" onkeypress="return esNumero(event)">
                                </div>
                            </div>
                            <br>
                            <div class="form-row">
                                <div class="col-4" id="divTipo">
                                   <label for="numAsiento">{{__('text.tipo')}}</label>
                                   <select class="custom-select" id="tipo" name="tipo" required>
                                        <option value="Gasto" @if($asiento->tipo == "Gasto") selected @endif><span style="background-color: red !important;">&#8722; {{__('text.gasto')}}</span></option>
                                        <option value="Ingreso" @if($asiento->tipo == "Ingreso") selected @endif><span style="color: green !important;">&#43; {{__('text.ingreso')}}</span></option>
                                    </select>
                                </div>
                                <div class="col-4" id="divCA">
                                    <label for="conceptoAgrupado">{{__('text.MVconagrup')}}</label>
                                    <select class="selectpicker form-control" data-live-search="true" name="conceptoAgrupado" id="conceptoAgrupado" onclick="colapsarSocios()" onchange="colapsarSocios()" data-style="btn-white">
                                        @foreach($conceptosAgrupados as $concepto)
                                            <option value="{{$concepto}}" @if($asiento->conceptoAgrupado == $concepto) selected @endif>{{$concepto}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="seleccionarSocios" class="col-4 collapse">
                                    <label for="form_need">{{__('text.socio')}} <small>({{__('text.MVcreacuot')}})</small>:</label>
                                    <select class="selectpicker form-control w-100" data-live-search="true" id="socioSelect" name="socioSelect" style="width: 100%">
                                        @foreach ($socios as $socio)
                                            <option value="{{$socio['id']}}">({{$socio['numSocio']}}) - {{$socio['nombre']}} {{$socio['primerApellido']}} {{$socio['segundoApellido']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="form-row">
                                <div class="col">
                                    <label for="descripcion">{{__('text.descripcion')}}</label>
                                    <input type="text" class="form-control" id="descripcion" name="descripcion" value="{{$asiento->detalle}}" maxlength="191" required>
                                </div>
                            </div>
                            <br>
                            <div class="form-row">
                                <div class="col-md-4 mb-3">
                                    <label for="vocalia">{{__('text.vocalia')}}</label>
                                    <select class="selectpicker form-control" data-live-search="true" name="vocalia" id="vocalia" data-style="btn-white">
                                        @foreach($vocalias as $vocalia)
                                                <option value="{{$vocalia}}" @if($asiento->vocalia == $vocalia) selected @endif>{{$vocalia}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="cantidad">{{__('text.cantidad')}}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="cantidad" name="cantidad" maxlength="9" value="{{$asiento->cantidad}}" placeholder="Solo números" onkeypress="return esNumero(event)">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">{{__('text.simbDin')}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                   <label for="pagcob">{{__('text.pagcob')}}</label>
                                   <select class="custom-select" id="pagcob" name="pagcob" required>
                                        <option value="Si" @if($asiento->pagcob == "Si") selected @endif>&#9745;</span> {{__('text.si')}}</option>
                                        <option value="No" @if($asiento->pagcob == "No") selected @endif>&#9746;</span> {{__('text.no')}}</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="form-row w-100">
                                <h6><span class="badge badge-pill badge-primary">{{ __('text.notas')}}</span></h6>
                                <div class="col align-self-center">
                                  <textarea class="form-control" id="notas" maxlength="600" name="notas" rows="3">{{$asiento->notas}}</textarea>
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
                                <div class="col-md-auto">
                                    <a href="{{ url()->previous() }}"><input type="button" class="btn btn-secondary" value="{{ __('text.back')}}" /></a>
                                </div>
                            </div>
                        </form>

                     @else {{-- ES USER --}}
                        <div class="card">
                            <div class="row text-center">
                                <div class="col" style="float: none; margin: 0 auto;">
                                    <img src="{{ asset('images/youshallnotpass.gif') }}" alt="No puedes pasar" style="float:center;!important"/>
                                </div>
                            </div>
                            <div class="row text-center">
                                <div class="col" style="float: none; margin: 0 auto;">
                                    <label class="col-form-label"><strong>{{ __('text.noAutorizado')}}</strong></label>
                                </div>
                            </div>
                            <div class="row text-center" style="justify-self: end;">
                                <div class="col" style="float: none; margin: 0 auto;">
                                    <a href="{{ url()->previous() }}"><input type="button" class="btn btn-outline-danger" value="{{ __('text.back')}}" /></a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function colapsarSocios(argument) {
        var sel = document.getElementById('conceptoAgrupado');
        if(sel!=null){
            var opt = sel.options[sel.selectedIndex];
            if(opt.text == "Ingreso cuotas"){
                $('.collapse').collapse('show');
                document.getElementById("divTipo").className = "col-3";
                document.getElementById("divCA").className = "col-5";
            }
            else{
                $('.collapse').collapse('hide');
                document.getElementById("divTipo").className = "col-4";
                document.getElementById("divCA").className = "col-8";
            }
        }
    }

    $(document).ready(function() {
        colapsarSocios();
    });
</script>

@endsection
