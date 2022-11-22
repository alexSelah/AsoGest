<!-- Modal Nueva Cuota Modal-->
<div id="nuevaCuota_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="container-fluid">
            <!-- Modal content-->
            <div class="modal-content col-md-12">
                <div class="modal-body">
                    <h4>{{__('text.nuevaCuota')}} </h4>
                    <p>{{__('text.nuevaCuotaTXT')}}</p>
                    <hr>
                    <form action="{{ route('nuevaCuota') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-row">
                            <div class="col">
                                <label for="tipoCuota">{{ __('text.tipoCuota')}}</label>
                                <select id="tipoCuota" name="tipoCuota" class="select form-control" style="width: 100%; display: block !important;">
                                    @foreach ($tiposCuota as $tp)
                                            <option value="{{$tp['id']}}" data-cant="{{$tp['cantidad']}}">{{$tp['nombre']}} ({{$tp['cantidad']." ".Lang::get('text.simbDin') }} )</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label for="cantidadCuota">{{ __('text.cantidadCuota')}}</label>
                                <input class="form-control" type="text" name="cantidadCuota" id="cantidadCuota" onkeypress="return esNumero(event)" value="{{$tiposCuota[0]['cantidad']}}">
                            </div>
                        </div>
                        <br>
                        <div class="form-row">
                            <div id="seleccionarSocios" class="col">
                                    <label for="form_need">{{ __('text.socio')}} <small>{{ __('text.MVcreacuot')}}</small>:</label>
                                    <select class="selectpicker form-control w-100" data-live-search="true" id="socioSelect" name="socioSelect" style="width: 100%">
                                        @foreach ($socios as $socio)
                                            <option value="{{$socio['id']}}">({{$socio['numSocio']}}) - {{$socio['nombre']}} {{$socio['primerApellido']}} {{$socio['segundoApellido']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            <div class="col">
                                <label for="fechaCuota">{{ __('text.fechaCuota')}}</label>
                                <input type='text' data-provide="datepicker" data-date-format="d/m/yyyy" id="fechaCuota" name="fechaCuota" required class="form-control datepicker" value="" />
                            </div>
                    </div>
                    <br><hr>
                    <div class="form-group row justify-content-md-center">
                        <div class="col-md-auto">
                            <button class="btn btn-success form-control" type="submit" data-toggle="modal" data-target="#trabajando_modal">
                                {{ __('text.altaCuota')}}
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
                </div>
            </div>
        </div>
    </div>
</div>
<!--FIN Modal Nueva Cuota-->


<!--MODAL EDITAR CUOTA-->
<div id="editCuotaModal" class="modal fade" role="dialog">
     <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
               <div class="form-group row">
                    <div class="col-md-auto">
                        <h4 class="modal-title">
                            {{__('text.editarCuota')}}
                        </h4>
                    </div>
               </div>
           </div>
           <div class="modal-body">

               <div id="modal-loader"
                    style="display: none; text-align: center;">
                <img src="{{ asset('/images/logoCargando.gif')}}">
               </div>

               <!-- EL CONTENIDO SE CARGA AQUÍ DE FORMA DINÁMICA -->
               <div id="dynamic-content"></div>

            </div>
      </div>
    </div><!-- /.modal -->
</div>

<!--MODAL ENVIAR EMAIL-->
<div id="enviaEmailCuotaModal" class="modal fade" role="dialog">
     <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
               <div class="form-group row">
                    <div class="col-md-auto">
                        <h4 class="modal-title">
                            {{__('text.emailA')}}
                        </h4>
                    </div>
               </div>
           </div>
           <div class="modal-body">

               <div id="modal-loader-email" style="display: none; text-align: center;">
                    <img src="{{ asset('/images/logoCargando.gif')}}">
               </div>

               <!-- EL CONTENIDO SE CARGA AQUÍ DE FORMA DINÁMICA -->
               <div id="dynamic-content-email"></div>

            </div>
      </div>
  </div>
</div><!-- /.modal -->
