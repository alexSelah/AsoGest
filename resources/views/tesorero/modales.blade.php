<!-- Modal Nuevo Apunte Rápido-->
<div id="apunteRapido_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="container-fluid">
            <!-- Modal content-->
            <div class="modal-content col-md-12">
                <div class="modal-body">
                   <p class="">{{ __('text.nuevoApunte') }}</p>
                    <hr>
                    <form action="{{ route('nuevoApunte') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="numAsiento">{{ __('text.numAsiento') }}</label>
                                <input type="text" autocomplete="off" class="form-control" id="numAsiento" name="numAsiento" value="{{$ultimoAsiento}}" disabled required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="fecha">{{ __('text.fechaApunte') }}</label>
                                <input type="date" autocomplete="off" class="form-control" id="fecha" name="fecha" required value="<?php echo date('Y-m-d'); ?>" >
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="year">{{ __('text.anio') }}</label>
                                <input type="number" autocomplete="off" min=1900 max=9999 class="form-control" id="year" name="year" value="<?php echo date("Y"); ?>" required maxlength="4" onkeypress="return esNumero(event)">
                            </div>
                        </div>
                        <br>
                        <div class="form-row">
                            <div class="col-4" id="divTipo">
                               <label for="numAsiento">{{ __('text.tipo') }}</label>
                               <select class="custom-select" id="tipo" name="tipo" required>
                                    <option value="Gasto" selected><span style="background-color: red !important;">&#8722; {{ __('text.gasto') }}</span></option>
                                    <option value="Ingreso"><span style="color: green !important;">&#43; {{ __('text.ingreso') }}</span></option>
                                </select>
                            </div>
                            <div class="col-4" id="divCA">
                                <label for="conceptoAgrupado">{{ __('text.conceptoAgrupado') }}</label>
                                <select class="selectpicker form-control" data-live-search="true" name="conceptoAgrupado" id="conceptoAgrupado" onclick="colapsarSocios()" onchange="colapsarSocios()">
                                    @foreach($conceptosAgrupados as $concepto)
                                        <option value="{{$concepto}}">{{$concepto}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="seleccionarSocios" class="col-4 collapse">
                                <label for="form_need">{{ __('text.socio') }} <small>{{ __('text.nuevaCuota') }}</small>:</label>
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
                                <label for="descripcion">{{ __('text.descripcion') }}</label>
                                <input type="text" class="form-control" id="descripcion" name="descripcion"  maxlength="191" required>
                            </div>
                        </div>
                        <br>
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="vocalia">{{ __('text.vocalia') }}</label>
                                <select class="selectpicker form-control" data-live-search="true" name="vocalia" id="vocalia">
                                    @foreach($vocalias as $vocalia)
                                            <option value="{{$vocalia}}">{{$vocalia}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="cantidad">{{ __('text.cantidad') }}</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="cantidad" name="cantidad" maxlength="9" placeholder="Solo números" onkeypress="return esNumero(event)">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">{{__('text.simbDin')}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                               <label for="pagcob">{{ __('text.pagcob') }}</label>
                               <select class="custom-select" id="pagcob" name="pagcob" required>
                                    <option value="Si">&#9745;</span> {{ __('text.si') }}</option>
                                    <option value="No" selected>&#9746;</span> {{ __('text.no') }}</option>
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
                            <div class="col-md-auto">
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

            <!--FIN Modal Nuevo Apunte Rápido-->

<!-- Modal Nuevo Fichero Excel-->
<div id="importarExcel_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="container-fluid">
            <!-- Modal content-->
            <div class="modal-content col-md-12">
                <div class="modal-body">
                    @if (Auth::user()->hasAnyPermission('permiso_tesoreria'))
                    <form action="{{ route('tesoreroImport') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                           <h4>{{__('text.importarExcel')}} </h4>
                           <h6>{{__('text.importarExceltxt')}}</h6>
                           <p>{{__('text.importarExcelAviso')}}</p>
                           <hr>
                          <div class="form-group row">
                            <label for="excel" class="col-sm-2 col-form-label">{{ __('text.archivo')}}</label>
                            <div class="col-sm-10">
                              <input id="excel" type="file" class="form-control @error('file') is-invalid @enderror" name="excel" value="{{ old('file') }}" style="height: 100%">
                            </div>
                          </div>
                        <hr>
                        <div class="form-group row justify-content-md-center">
                            <div class="col col-lg-2">
                                <button class="btn btn-success form-control" type="submit" data-toggle="modal" data-target="#trabajando_modal">
                                    {{ __('text.btnImportar')}}
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
                    @else
                        <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <img src="{{ asset('public/images/youshallnotpass.gif') }}" alt="No puedes pasar" class="rounded mx-auto d-block" />
                                    <label><h1><strong>{{ __('text.noAutorizado')}}</strong></h1></label>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!--FIN Modal Nuevo Fichero-->

<!-- Modal MANTENIMIENTO Modal-->
<div id="mantenimiento_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="container-fluid">
            <!-- Modal content-->
            <div class="modal-content col-md-12">
                <div class="modal-body">
                    <h4>{{__('text.datosMantenimiento')}} </h4>
                    <p>{{__('text.datosMantenimientoTxt')}}</p>
                    <hr>
                    <div class="form-group row">
                        <label class="col-sm-6 col-form-label">{{ __('text.mes')}}</label>
                        <label class="col-sm-2 col-form-label"> {{ \Helper::dimeFecha(date('d'), date('m'),date('Y'), 3) }}</label>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-6 col-form-label">{{ __('text.sociosActivo')}}</label>
                        <label class="col-sm-2 col-form-label">{{$sociosMant}}</label>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-6 col-form-label">{{ __('text.cantidadXsocioMant')}}</label>
                        <label class="col-sm-2 col-form-label">{{ $mantenimiento}} {{ __('text.simbDin')}}</label>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-6 col-form-label"><strong>{{ __('text.mantTotal')}}</strong></label>
                        <label class="col-sm-2 col-form-label"><strong>{{$totalMantenimiento}} {{ __('text.simbDin')}}</strong></label>
                    </div>
                    <hr>
                    <div class="form-group row justify-content-md-center">
                        <div class="col-md-auto">
                            <a href="{{ route('mantenimientoPDF') }}"><input type="button" class="btn btn-success" value="{{ __('text.informePDF')}}" /></a>
                        </div>
                        <div class="col-md-auto">

                        </div>
                        <div class="col col-lg-2">
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
<!--FIN Modal Nuevo Apunte Rápido-->

<!-- Modal Informe Personalizado Modal-->
<div id="inforPers_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="container-fluid">
            <!-- Modal content-->
            <div class="modal-content col-md-12">
                <div class="modal-body">
                    <h4>{{__('text.informePers')}} </h4>
                    <p>{{__('text.informePersDesc')}}</p>
                    <hr>
                    <form action="{{ route('informePersTesorero') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group row">
                            <label for="nombreInf" class="col-sm-2 col-form-label"><strong>{{__('text.nombreInforme')}}</strong></label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="nombreInf" name="nombreInf" placeholder="{{__('text.nombreInformeDesc')}}">
                            </div>
                        </div>
                        <br>
                        <div class="form-group row">
                            <textarea id="summernote" name="editordata" style="min-width: 100%; background-color: white;"></textarea>
                        </div>
                        <hr>
                        <div class="form-group row justify-content-md-center">
                            <div class="col-md-auto">
                                <input type="submit" class="btn btn-success" value="{{ __('text.informePDF')}}" />
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
<!--FIN Modal Informe Personalizado-->

<!-- Modal MORATORIA CUOTAS-->
<div id="moratoriaCuotas_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="container-fluid">
            <!-- Modal content-->
            <div class="modal-content col-md-12">
                <div class="modal-body">
                    @if (Auth::user()->hasAnyPermission('permiso_tesoreria'))
                    <form action="{{ route('aplazaCuotas') }}" method="POST">
                        {{ csrf_field() }}

                        <h4>{{__('text.moratoriaCuotasDesc')}} </h4>
                        <h6>{{__('text.moratoriaCuotasTxt')}}</h6>
                        <p>{{__('text.moratoriaCuotasTxt2')}}</p>
                        <p>{{__('text.moratoriaCuotasAviso')}}</p>
                        <hr>

                        <div class="row">
                          <div class="col-md-12">
                            <div class="form-check">
                                  <input type="checkbox" class="form-check-input checked" id="checkTodosSocios" name="checkTodosSocios" onclick="cuotasSociosAplazado();">
                                  <label class="form-check-label" for="checkTodosSocios">{{__('text.cuotasTodosSocios')}}</label>
                            </div>
                          </div>
                        </div>
                        <br>
                        <div id="aplazarAlgunosSocios" class="form-group row">
                            <div id="seleccionarSocios" class="col-12">
                                <label for="form_need">{{__('text.MVsocios')}} <small>({{__('text.modAplTxt')}})</small>:</label>
                                <select class="selectpicker form-control w-100" data-live-search="true" id="socioSelect[]" multiple name="socioSelect[]" data-done-button="true" style="width: 100%">
                                    @foreach ($socios as $socio)
                                        <option value="{{$socio['id']}}">({{$socio['numSocio']}}) - {{$socio['nombre']}} {{$socio['primerApellido']}} {{$socio['segundoApellido']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="aplazarTodosSocios" class="form-group row collapse">
                            <h4>{{__('text.seAplazanTodos')}} </h4>
                        </div>

                        <hr>
                            <div class="form-group">
                              <label for="inputMesesAplazamiento"><strong>{{__('text.modAplaztxt')}}</strong></label>
                              <select id="inputMesesAplazamiento" name="inputMesesAplazamiento" class="form-control" onchange="colapsardiasAplazamiento();">
                                <option value="1" onclick="colapsardiasAplazamiento();" selected>1 {{__('text.meses')}}</option>
                                <option value="2" onclick="colapsardiasAplazamiento();">2 {{__('text.meses')}}</option>
                                <option value="3" onclick="colapsardiasAplazamiento();">3 {{__('text.meses')}}</option>
                                <option value="4" onclick="colapsardiasAplazamiento();">4 {{__('text.meses')}}</option>
                                <option value="5" onclick="colapsardiasAplazamiento();">5 {{__('text.meses')}}</option>
                                <option value="6" onclick="colapsardiasAplazamiento();">6 {{__('text.meses')}}</option>
                                <option value="7" onclick="colapsardiasAplazamiento();">7 {{__('text.meses')}}</option>
                                <option value="8" onclick="colapsardiasAplazamiento();">8 {{__('text.meses')}}</option>
                                <option value="9" onclick="colapsardiasAplazamiento();">9 {{__('text.meses')}}</option>
                                <option value="10" onclick="colapsardiasAplazamiento();">10 {{__('text.meses')}}</option>
                                <option value="11" onclick="colapsardiasAplazamiento();">11 {{__('text.meses')}}</option>
                                <option value="12" onclick="colapsardiasAplazamiento();">12 {{__('text.meses')}}</option>
                                <option disabled="disabled">------------------</option>
                                <option value="0" data-toggle="collapse" data-target="#diasSueltos"> {{__('text.modIntManTxt')}}</option>
                              </select>
                            </div>

                            <div id="diasSueltos" class="form-group row collapse in hide">
                                <label for="diasSueltosInput" class="col-sm-8 col-form-label">{{ __('text.diasSueltosAplazamiento')}}</label>
                                <div class="col-sm-4">
                                  <input class="form-control" type="text" name="diasSueltosInput" id="diasSueltosInput" onkeypress="return esNumero(event)" />
                                </div>
                            </div>

                        <hr>
                        <div class="form-group row justify-content-md-center">
                            <div class="col col-lg-2">
                                <button class="btn btn-success form-control" type="submit" data-toggle="modal" data-target="#trabajando_modal">
                                    {{ __('text.btnAplicar')}}
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
                    @else
                        <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <img src="{{ asset('public/images/youshallnotpass.gif') }}" alt="No puedes pasar" class="rounded mx-auto d-block" />
                                    <label><h1><strong>{{ __('text.noAutorizado')}}</strong></h1></label>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!--FIN Modal MORATORIA CUOTAS-->
