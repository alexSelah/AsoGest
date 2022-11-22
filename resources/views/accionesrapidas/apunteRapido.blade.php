@extends('layouts.app')

@section('content')

<div class="container">
    <br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                            <strong>{{ __('text.nuevoApunte') }}</strong>
                        <a href="{{ route('home') }}"><input type="button" class="btn btn-outline-secondary btn-sm" value="{{ __('text.back')}}" /></a>
                    </div>
                    <hr>
                    <form action="{{ route('nuevoApunte') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="numAsiento">{{ __('text.numAsiento') }}</label>
                                <input type="text" class="form-control" id="numAsiento" name="numAsiento" value="{{$ultimoAsiento}}" disabled required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="fecha">{{ __('text.fechaApunte') }}</label>
                                <input type="date" class="form-control" id="fecha" name="fecha" required value="<?php echo date('Y-m-d'); ?>" >
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="year">{{ __('text.anio') }}</label>
                                <input type="text" class="form-control" id="year" name="year" value="<?php echo date("Y"); ?>" required maxlength="4" onkeypress="return esNumero(event)">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-4" id="divTipo">
                               <label for="numAsiento">Tipo</label>
                               <select class="custom-select" id="tipo" name="tipo" required>
                                    <option value="Gasto" selected><span style="background-color: red !important;">&#8722; {{ __('text.gasto') }}</span></option>
                                    <option value="Ingreso"><span style="color: green !important;">&#43; {{ __('text.ingreso') }}</span></option>
                                </select>
                            </div>
                            <div class="col-4" id="divCA">
                                <label for="conceptoAgrupado">{{ __('text.conceptoAgrupado')}}</label>
                                <select class="selectpicker form-control" data-live-search="true" name="conceptoAgrupado" id="conceptoAgrupado" onclick="colapsarSocios()" onchange="colapsarSocios()">
                                    @foreach($conceptosAgrupados as $concepto)
                                        <option value="{{$concepto}}">{{$concepto}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="seleccionarSocios" class="col-4 collapse">
                                <label for="form_need">{{ __('text.socio')}} <small>{{ __('text.MVcreacuot')}}</small>:</label>
                                <select class="selectpicker form-control w-100" data-live-search="true" id="socioSelect" name="socioSelect" style="width: 100%">
                                    @foreach ($socios as $socio)
                                        <option value="{{$socio['id']}}">({{$socio['numSocio']}}) - {{$socio['nombre']}} {{$socio['primerApellido']}} {{$socio['segundoApellido']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col">
                                <label for="descripcion">{{ __('text.descripcion')}}</label>
                                <input type="text" class="form-control" id="descripcion" name="descripcion"  maxlength="191" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="vocalia">{{ __('text.cantidadCuota')}}</label>
                                <select class="selectpicker form-control" data-live-search="true" name="vocalia" id="vocalia">
                                    @foreach($vocalias as $vocalia)
                                            <option value="{{$vocalia}}">{{$vocalia}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="cantidad">{{ __('text.cantidadCuota')}}</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="cantidad" name="cantidad" maxlength="9" placeholder="Solo números" onkeypress="return esNumero(event)">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">{{__('text.simbDin')}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                               <label for="pagcob">{{ __('text.pagcob')}}</label>
                               <select class="custom-select" id="pagcob" name="pagcob" required>
                                    <option value="Si">&#9745;</span> {{ __('text.si') }}</option>
                                    <option value="No" selected>&#9746;</span> {{ __('text.no') }}</option>
                                </select>
                            </div>
                        </div>

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
