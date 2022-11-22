@extends('layouts.app')

@section('content')

<div class="container">
    <br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                            <strong>{{ __('text.reservar') }}</strong>
                        <a href="{{ route('home') }}"><input type="button" class="btn btn-outline-secondary btn-sm" value="{{ __('text.back')}}" /></a>
                    </div>
                    <hr>
                    <form action="{{ route('guardaEventoHome') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                            <div class="row">
                                <legend class="col-4 col-form-label"><strong>{{__('text.preguntareservasala')}}</strong></legend>
                                <div class="col-2 form-check form-check-inline">
                                  <input class="form-check-input" type="radio" name="salaRol"  value="noSala" id="noSala" checked>
                                  <label class="form-check-label" for="inlineRadio1">&#128078;&nbsp;{{__('text.no')}}</label>
                                </div>
                                <div class="col-2 form-check form-check-inline">
                                  <input class="form-check-input" type="radio" name="salaRol" value="siSala" id="siSala">
                                  <label class="form-check-label" for="inlineRadio2">&#128077;&nbsp;{{__('text.si')}}</label>
                                </div>
                            </div>
                            <br>

                            <div class="form-group row">
                               <label for="columnaTiempo" class="col-sm-4 col-form-label">{{ __('text.colHoraEvento')}}</label>
                                <div class="col-sm-8" id="columnaTiempo" name="columnaTiempo">
                                    <div class="row">
                                      <div class="col">
                                        <label for="eventoDesde" class="col-form-label">{{ __('text.colDesde')}}</label>
                                        <input type="text" name="eventoDesde" id="eventoDesde" class="form-control"  />
                                      </div>
                                      <div class="col">
                                        <label for="eventoHasta" class="col-form-label">{{ __('text.colDuracion')}}</label>
                                        <input type="text" name="eventoHasta" id="eventoHasta" class="form-control" />
                                      </div>
                                    </div>
                                </div>
                            </div>

                            <div style="display: none">
                              <input type="text" name="fechaInicio" id="fechaInicio" style="display: none !important" />
                              <input type="text" name="horaInicio" id="horaInicio" style="display: none !important" />
                              <input type="text" name="fechaFin" id="fechaFin" style="display: none !important" />
                              <input type="text" name="horaFin" id="horaFin" style="display: none !important" />
                            </div>



                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="vocalia" class="col-form-label">{{__('text.vocalia')}}</label>
                                    <select class="selectpicker form-control" data-live-search="true" name="vocalia" id="vocalia">
                                        @foreach($vocalias as $vocalia)
                                            <option value="{{$vocalia->id}}">{{$vocalia->nombre}}</option>
                                        @endforeach
                                        @if(Auth::user()->hasRole(['admin','tesorero','secretario']))
                                            <option value="0">{{__('text.eventoGeneral')}}</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-sm-8">
                                    <label for="inputNombre" class="col-form-label">{{ __('text.colNombre')}}</label>
                                    <input class="form-control" type="text" maxlength="190" name="inputNombre" id="inputNombre" placeholder="{{ __('text.nombreEventoDesc')}}" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="descripcionEvento">{{__('text.colDescripcion')}}</label>
                                <textarea id="descripcionEvento" name="descripcionEvento" class="form-control" placeholder="{{ __('text.descripcionEventoDesc')}}" rows="4"></textarea>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="form-group row">
                                <label for="socioSelect[]" class="col-sm-4 col-form-label">{{ __('text.participantes')}}</label>
                                  <div class="col-sm-8 select-outline">
                                      <select class="selectpicker form-control" data-done-button="true" multiple data-live-search="true" id="socioSelect[]" name="socioSelect[]" style="width: 100%">
                                          @foreach ($socios as $socio)
                                              <option value="{{$socio['id']}}">{{$socio['nombre']}} {{$socio['primerApellido']}}</option>
                                          @endforeach
                                      </select>
                                  </div>
                            </div>
                        <br/>
                        <hr>
                        <div class="form-group row justify-content-md-center">
                            <div class="col col-lg-4">
                                <button class="btn btn-success form-control" type="submit" data-toggle="modal" data-target="#trabajando_modal">
                                    {{ __('text.btnGuardar')}}
                                </button>
                            </div>
                            <div class="col-md-auto">

                            </div>
                        </div>
                    </form>
                    <br>

                </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    //DATE RANGE PICKER
    $(function() {
      $('input[name="eventoDesde"]').daterangepicker({
            "singleDatePicker": true,
            "timePicker": true,
            "timePickerIncrement": 10,
            "timePicker24Hour": true,
            "locale": {
                "format": "DD/MMM  -   hh:mm A",
                "separator": " - ",
                "applyLabel": "OK",
                "cancelLabel": "Cancelar",
                "fromLabel": "Desde",
                "toLabel": "Hasta",
                "customRangeLabel": "Personalizar",
                "weekLabel": "S",
                "daysOfWeek": [
                    "Do",
                    "Lu",
                    "Ma",
                    "Mi",
                    "Ju",
                    "Vi",
                    "Sa"
                ],
                "monthNames": [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre"
                ],
                "firstDay": 1
            },
            "alwaysShowCalendars": true,
            "startDate": moment().startOf('hour'),
            "endDate": moment().startOf('hour').add(3, 'hour'),
            "opens": "center",
            "applyButtonClasses": "btn btn-sm btn-success",
            "cancelClass": "btn btn-sm btn-secondary"
        }, function(start, end, label) {
          //console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
          document.getElementById("fechaInicio").value = start.format('DD/MM/YYYY');
          document.getElementById("horaInicio").value = start.format('H:mm');
        });
      $('input[name="eventoHasta"]').daterangepicker({
            "singleDatePicker": true,
            "timePicker": true,
            "timePickerIncrement": 10,
            "timePicker24Hour": true,
            "locale": {
                "format": "DD/MMM  -   hh:mm A",
                "separator": " - ",
                "applyLabel": "OK",
                "cancelLabel": "Cancelar",
                "fromLabel": "Desde",
                "toLabel": "Hasta",
                "customRangeLabel": "Personalizar",
                "weekLabel": "S",
                "daysOfWeek": [
                    "Do",
                    "Lu",
                    "Ma",
                    "Mi",
                    "Ju",
                    "Vi",
                    "Sa"
                ],
                "monthNames": [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre"
                ],
                "firstDay": 1
            },
            "alwaysShowCalendars": true,
            "startDate": moment().startOf('hour'),
            "endDate": moment().startOf('hour').add(3, 'hour'),
            "opens": "center",
            "applyButtonClasses": "btn btn-sm btn-success",
            "cancelClass": "btn btn-sm btn-secondary"
        }, function(start, end, label) {
          //console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('H:mm') + ' (predefined range: ' + label + ')');
          document.getElementById("fechaFin").value = start.format('DD/MM/YYYY');
          document.getElementById("horaFin").value = start.format('H:mm');
        });
    });
</script>

@endsection
