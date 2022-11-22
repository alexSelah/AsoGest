@extends('layouts.app')

@section('content')

<meta http-equiv="Content-Security-Policy" content="default-src 'none'; img-src 'self'; script-src 'self' apis.google.com; style-src 'self';">
<meta http-equiv="Content-Security-Policy" content="default-src 'self' apis.google.com">
<meta http-equiv="Content-Security-Policy" content="script-src 'self' 'unsafe-eval' https://*.google.com; object-src 'self' 'unsafe-eval'">
<meta http-equiv="Content-Security-Policy" content="script-src 'self' 'unsafe-eval' apis.google.com;">

<style type="text/css">
    html{
    scroll-behavior: smooth;
  }
  .responsiveCal {
    position: relative; padding-bottom: 75%; height: 0; overflow: hidden;
  }
  .responsiveCal iframe {
    position: absolute; top:0; left: 0; width: 100%; height: 100%;
  }

  table.dataTable tbody td {
  vertical-align: middle;
}

</style>

<div class="container">

    <!-- Nav tabs -->
    <ul class="nav nav-tabs mr-auto" id="myTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><strong>{{__('text.nombreAsoc')}}</strong></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="ludoteca-tab" data-toggle="tab" href="#ludoteca" role="tab" aria-controls="ludoteca" aria-selected="false">{{__('text.ludoteca')}}</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="false">{{__('text.infoAsocacion')}}</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="gruposchat-tab" data-toggle="tab" href="#gruposchat" role="tab" aria-controls="gruposchat" aria-selected="false">{{__('text.gruposchat')}}</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="calendario-tab" data-toggle="tab" href="#calendario" role="tab" aria-controls="calendario" aria-selected="false">{{__('text.calendario')}}</a>
      </li>

      <li class="nav-item" style="margin-left: 95px">
          <a href="{{ route('home') }}"><input type="button" class="btn btn-outline-secondary btn-sm" value="{{ __('text.back')}}" /></a>
      </li>


    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
    {{--INICIO--}}
        <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
          <br>
            <div class="card-deck">
              <div class="card" style="width:400px">
                <img class="card-img-top" src="{{ asset('images/logoB.png') }}" alt="Logo Asociacion"  style="width: 300px !important; align-content: center; margin :auto">
                <div class="card-body">
                  <h5 class="card-title">{{__('text.nombreAsoc')}}</h5>
                  <p class="card-text text-justify">{{__('text.textoInfoAsoc')}}</p>
                </div>
              </div>
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">{{__('text.juntadirectiva')}}</h5>
                  <p class="card-text text-justify">{{__('text.textoInfoJunta')}}</p> <br>
                  <p class="card-text text-justify"><b>{{__('text.presidente')}}:</b> {{$presidente->nombre}}&nbsp;{{$presidente->primerApellido}}&nbsp;<a href="mailto:{{$presidente->email}}">&#128231;</a> </p>
                  <p class="card-text text-justify"><b>{{__('text.tesorero')}}:</b> {{$tesorero->nombre}}&nbsp;{{$tesorero->primerApellido}}&nbsp;<a href="mailto:{{$tesorero->email}}">&#128231;</a> </p>
                  <p class="card-text text-justify"><b>{{__('text.secretario')}}:</b> {{$secretario->nombre}}&nbsp;{{$secretario->primerApellido}}&nbsp;<a href="mailto:{{$secretario->email}}">&#128231;</a> </p>
                </div>
                <div class="row">
                    <div class="col-sm column">
                        <img src="{{ asset('images/fotos/'.$presidente->foto) }}" class="rounded float-left" alt="Foto Presidente" style="width: 150px !important;">
                    </div>
                    <div class="col-sm column">
                        <img src="{{ asset('images/fotos/'.$tesorero->foto) }}" class="rounded float-center" alt="Foto Tesorero" style="width: 150px !important;">
                    </div>
                    <div class="col-sm column">
                        <img src="{{ asset('images/fotos/'.$secretario->foto) }}" class="rounded float-right" alt="Foto Secretario" style="width: 150px !important;">
                    </div>
                </div>
              </div>

            </div>

        </div> {{--FIN INICIO--}}

    {{--ludoteca--}}
        <div class="tab-pane" id="ludoteca" role="tabpanel" aria-labelledby="ludoteca-tab">
          @if ( Auth::user()->hasAnyPermission(['Acceso_total','permiso_tesoreria', 'permiso_secretaria','permiso_ver_informes','permiso_editar_socios','permiso_vocalia_Mesa']))
            <br>
            <a href="{{ route('actualizaLudoteca') }}"><input type="button" class="btn btn-outline-danger btn" value="{{ __('text.recargarLudoteca')}}" data-toggle="modal" data-target="#trabajando_modal"/></a>
            <br>
          @endif
          <br>
          <table class="table table-striped table-bordered text-center display compact nowrap" id="tablaJuegos" style="min-width: 100%;">
              <thead class="thead-light">
                  <tr>
                      <th data-priority="1">{{ __('text.imagen')}}</th>
                      <th data-priority="1">{{ __('text.nombrejuego')}}</th>
                      <th data-priority="2">{{ __('text.anio')}}</th>
                      <th data-priority="3">{{ __('text.minjugadores')}}</th>
                      <th data-priority="3">{{ __('text.maxjugadores')}}</th>
                      <th data-priority="3">{{ __('text.playtime')}}</th>
                      <th data-priority="2">{{ __('text.ver')}}</th>
                  </tr>
              </thead>
              <tfoot class="thead-light">
                  <tr>
                      <th data-priority="1">{{ __('text.imagen')}}</th>
                      <th data-priority="1">{{ __('text.nombrejuego')}}</th>
                      <th data-priority="2">{{ __('text.anio')}}</th>
                      <th data-priority="3">{{ __('text.minjugadores')}}</th>
                      <th data-priority="3">{{ __('text.maxjugadores')}}</th>
                      <th data-priority="3">{{ __('text.playtime')}}</th>
                      <th data-priority="2">{{ __('text.ver')}}</th>
                  </tr>
              </tfoot>
          </table>

        </div> {{--FIN ludoteca--}}


    {{--info--}}
        <div class="tab-pane" id="info" role="tabpanel" aria-labelledby="info-tab">
          <br>
          @include('timeline')
        </div>

      {{--Grupos de Chat--}}
        <div class="tab-pane" id="gruposchat" role="tabpanel" aria-labelledby="gruposchat-tab">
          <br>
            {{__('text.txtogruposchat')}}
          <hr>
          <div class="accordion" id="accordionChats">
            @foreach($grupochat as $keych=>$grc)
              <div class="card">
                <div class="card-header" id="{{$keych}}">
                  <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse{{$keych}}" aria-expanded="true" aria-controls="collapseOne">
                          {{$grc['nombre']}}
                    </button>
                  </h2>
                </div>

                <div id="collapse{{$keych}}" class="collapse" aria-labelledby="heading{{$keych}}" data-parent="#accordionChats">
                  <div class="card-body">
                      {{$grc['desc']}}
                      <br><br>
                      <a href="{{$grc['URL']}}" target="_blank">
                        {!! QrCode::size(200)->generate($grc['URL']) !!}
                      </a>
                  </div>
                </div>
              </div>
            @endforeach
        </div>
        <br>
        </div> {{-- FIN DEL PANEL Grupos de Chat--}}

    {{--calendario--}}
          <div class="tab-pane" id="calendario" role="tabpanel" aria-labelledby="calendario-tab">
           <br>
              {{__('text.txtoCalendario')}}
              <br>

                <div class="form-group row justify-content-md-center">
                    <div class="col col-lg-4">
                        <a href="#" class="btn btn-outline-success" data-toggle="modal" data-target="#suscribirCalendario_modal">
                            {{ __('text.suscribirCal')}}
                        </a>
                    </div>
                    <div class="col-md-auto">
                      @if (Auth::user()->hasPermissionTo('permiso_editar_socios'))
                        <a href="#" class="btn btn-outline-danger" data-toggle="modal" data-target="#crearEvento_modal">
                            {{ __('text.nuevoEventoImportante')}}
                        </a>
                      @endif
                    </div>
                </div>

              <hr>

                <div class="row">
                  <div class="col">
                    <label><b>{{ __('text.estaSemana')}}</b></label>
                    <div class="responsiveCal">
                      <?php echo $cal2 ?>
                    </div>
                  </div>
                  <div class="col">
                    <label><b>{{ __('text.vistaAgenda')}}</b></label>
                    <div class="responsiveCal">
                        <?php echo $cal3 ?>
                    </div>
                  </div>
                </div>
              <br>
          </div> {{-- FIN DEL PANEL calendario--}}

    </div>

<!-- Modal Suscribirse al Calendario-->
<div id="suscribirCalendario_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="container-fluid">
            <!-- Modal content-->
            <div class="modal-content col-md-16">
                <div class="modal-body">
                    <label class="label-info">{{ __('text.suscribirTXT') }}</label>
                    <hr>
                    <div class="row">
                      <div class="col">
                        {!!QrCode::size(300)->generate($codQR) !!}
                      </div>
                      {{-- <img src="{{ asset('images/qr.png') }}" alt="Codigo QR" style="width: 300px; float:left; margin-right:25px; !important"/> --}}
                      <br>
                      <div class="col">
                        <a href="{{$calurl}}" class="btn btn-outline-primary form-control" target="_blank">
                          {{ __('text.suscribirCal')}}
                        </a>
                        <br><br>
                        <button type="button" class="btn btn-secondary form-control" data-dismiss="modal">
                            {{ __('text.btnClose')}}
                        </button>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 <!--FIN Modal Suscribirse al Calendario-->

 <!-- Modal CREAR EVENTO ASOCIACION-->
<div id="crearEvento_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="container-fluid">
            <!-- Modal content-->
            <div class="modal-content col-md-16">
                <div class="modal-body">
                    <label class="label-info">{{ __('text.crearEvento') }}</label>
                    <hr>
                    <form action="{{ route('guardarEventoAsoc') }}" method="POST" enctype="multipart/form-data">
                      {{ csrf_field() }}
                          <div class="form-group row">
                             <label for="columnaTiempo" class="col-sm-4 col-form-label">{{ __('text.colHoraEvento')}}*</label>
                              <div class="col-sm-8" id="columnaTiempo" name="columnaTiempo">
                                  <div class="row">
                                    <div class="col">
                                      <label for="eventoDesde" class="col-form-label">{{ __('text.colDesde')}}</label>
                                      <input type="text" name="eventoDesde" id="eventoDesde" class="form-control" />
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
                             <label for="inputNombre" class="col-sm-4 col-form-label">{{ __('text.colNombre')}}*</label>
                              <div class="col-sm-8">
                                <input class="form-control" type="text" name="inputNombre" id="inputNombre" placeholder="{{ __('text.nombreEventoDesc')}}" />
                              </div>
                          </div>

                          <div class="form-group">
                              <label for="descripcionEvento">{{__('text.colDescripcion')}}</label>
                              <textarea id="descripcionEvento" name="descripcionEvento" class="form-control" placeholder="{{ __('text.descripcionEventoDesc')}}" rows="4"></textarea>
                              <div class="help-block with-errors"></div>
                          </div>

                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-check">
                                    <input type="checkbox" class="form-check-input checked" id="checkSoloJD" name="checkSoloJD" data-toggle='collapse' data-target='#seleccionarSocios' selected checked>
                                    <label class="form-check-label" for="checkSoloJD">{{__('text.emailAsoloJD')}}</label>
                              </div>
                            </div>
                          </div>
                          <div id="seleccionarSocios" class="form-group row collapse">
                                <label for="socioSelect[]" class="col-sm-4 col-form-label">{{ __('text.participantes')}}</label>
                                  <div class="col-sm-8 select-outline">
                                      <select class="selectpicker form-control show-tick" multiple data-live-search="true" id="socioSelect[]" name="socioSelect[]" style="width: 100%" data-actions-box="true" data-selected-text-format="count > 3">
                                          @foreach ($socios as $socio)
                                              <option value="{{$socio['id']}}">{{$socio['nombre']}} {{$socio['primerApellido']}}</option>
                                          @endforeach
                                      </select>
                                  </div>
                          </div>
                      <br/>
                      <hr>
                      <div class="form-group row justify-content-md-center">
                          <div class="col col-lg-2">
                              <button class="btn btn-success form-control" type="submit" data-toggle="modal" data-target="#trabajando_modal">
                                  {{ __('text.btnGuardar')}}
                              </button>
                          </div>
                          <div class="col-md-auto">

                          </div>
                          <div class="col col-lg-2">
                              <button type="button" class="btn btn-secondary form-control" data-dismiss="modal">
                                  {{ __('text.btnClose')}}
                              </button>
                          </div>
                      </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
</div>
 <!--FIN CREAR EVENTO ASOCIACION-->


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

    $('socioSelect').selectpicker();

    $(document).ready(function() {
         $.ajax({
          url: "{{url('/juegosDataTable')}}",
          success : function(data) {
              var tabla = data.data;//.list.item;
              $('#tablaJuegos').DataTable( {
                    responsive: true,
                    processing: true,
                    colReorder: true,
                    serverSide: true,
                    ajax: '{{url('/juegosDataTable')}}',
                    initComplete: function () {
                        this.api().columns(1).every(function () {
                            var column = this;
                            var input = document.createElement("input");
                            input.setAttribute('type', 'text');
                            input.setAttribute('class', 'form-control')
                            $(input).appendTo($(column.footer()).empty())
                            .on('change', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                        });
                        this.api().columns(2).every(function () {
                            var column = this;
                            var input = document.createElement("input");
                            input.setAttribute('type', 'text');
                            input.setAttribute('class', 'form-control')
                            $(input).appendTo($(column.footer()).empty())
                            .on('change', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                        });
                        this.api().columns(3).every(function () {
                            var column = this;
                            var input = document.createElement("input");
                            input.setAttribute('type', 'text');
                            input.setAttribute('class', 'form-control')
                            $(input).appendTo($(column.footer()).empty())
                            .on('change', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                        });
                        this.api().columns(4).every(function () {
                            var column = this;
                            var input = document.createElement("input");
                            input.setAttribute('type', 'text');
                            input.setAttribute('class', 'form-control')
                            $(input).appendTo($(column.footer()).empty())
                            .on('change', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                        });
                        this.api().columns(5).every(function () {
                            var column = this;
                            var input = document.createElement("input");
                            input.setAttribute('type', 'text');
                            input.setAttribute('class', 'form-control')
                            $(input).appendTo($(column.footer()).empty())
                            .on('change', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                        });
                    },
                    data : tabla,
                    columns: [
                        {
                            "className": 'details-control',
                            "orderable": false,
                            "data": "thumbnail",
                            "searchable": false,
                            "render": function (data, type, row) {
                                //return '<a class="btn btn-danger" onclick="alert('+ row.offset +')">Deletear</a>'
                                return "<img src=\""+data+"\"></img>";
                            }
                        },
                        {"data" : "name"},
                        {"data" : "yearpublished"},
                        {"data" : "minplayers"},
                        {"data" : "maxplayers"},
                        {"data" : "playingtime"},
                        {
                            "className": 'details-control',
                            "orderable": false,
                            "data": "objectid",
                            "searchable": false,
                            "render": function (data, type, row) {
                                //return '<a class="btn btn-danger" onclick="alert('+ row.offset +')">Deletear</a>'
                                return "<a href=\"http://www.boardgamegeek.com/boardgame/"+ data +"\" target=\"_blank\"><input type=\"button\" class=\"btn btn-outline-info\" value=\"&#128064;\" /></a>";
                                return "<a href=\"http://www.boardgamegeek.com/boardgame/"+ data +"\" target=\"_blank\"><input type=\"button\" class=\"btn btn-outline-info\" value=\"&#128064;\"/></a>";
                            }
                        },
                    ],
                    buttons: [
                        @include('layouts.datatableComun')
              });
          },
      });
    });

</script>

<noscript>Por favor, habilita JavaScript para ver esta página. Please, enable JavaScript to see this page propertly.</noscript>
@endsection

