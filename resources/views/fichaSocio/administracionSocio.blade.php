@if (Auth::user()->hasRole(['secretario','admin','tesorero',]))
    <h5>{{ __('text.SeccionSecretario')}}</h5>
    <label for="roles">{{ __('text.cambiaRoles')}}</label>
    <div class="form-group row align-self-center">
        <div class="col-6">
            <select id="roles" name="roles" class="custom-select" onchange="colapsar()">
              @foreach ($posiblesRoles as $posiblesRol)
                    <option value="{{$posiblesRol['id']}}" @if($roles == $posiblesRol->name) selected  @endif onclick="colapsar()">
                        @if($posiblesRol->name == "admin")&#9484; {{__('text.administrador')}}
                        @elseif ($posiblesRol->name == "secretario") &#9500;&#9472;&#9472; {{__('text.secretario')}}
                        @elseif ($posiblesRol->name == "tesorero") &#9500;&#9472;&#9472; {{__('text.tesorero')}}
                        @elseif ($posiblesRol->name == "junta")&#9500;&#9472;&#9472;&#9472;&#9472;&#9472; {{__('text.miembrodejunta')}}
                        @elseif ($posiblesRol->name == "vocal") &#9500;&#9472;&#9472;&#9472;&#9472;&#9472; {{__('text.vocal')}}
                        @else &#9500;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472; {{__('text.socio')}}
                        @endif
                    </option>
              @endforeach
            </select>
        </div>
        <div id="vocaliaSelectDiv" class="col-6">
            <select id="vocaliaSelect" name="vocaliaSelect" class="custom-select">
              @foreach ($vocalias as $vocalia)
                    <option value="{{$vocalia['id']}}" @if($vocalia['nombre'] == $vocaliaSelect) selected @endif>
                        {{$vocalia['nombre']}}
                    </option>
              @endforeach
            </select>
        </div>
    </div>

    <br><hr>

    <h6><label >{{ __('text.propiedadesSocio')}}</label></h6>
    <div class="form-group row">
        <div Class='col'>
            <div class="input-group">
                <button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" disabled data-placement="top" title="{{ __('text.txtHabilitado')}}">
                    ?
                </button>
               <label for="inputHabilitado" class="form-control"><strong>{{ __('text.habilitado')}}</strong></label>
                  <select id="inputHabilitado" name="inputHabilitado" class="form-control custom-select" class="form-control">
                    <option value="1" @if($usuario['habilitado'] == true) selected @endif> {{ __('text.si') }} </option>
                    <option value="0" @if($usuario['habilitado'] == false) selected @endif> {{ __('text.no') }} </option>
                  </select>
              </div>
        </div>

        <div Class='col'>
            <div class="input-group">
                <button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" disabled data-placement="top" title="{{ __('text.txtAccs1')}}">
                    ?
                </button>
               <label for="inputaccesoDrive" class="form-control">{{ __('text.accs1')}}</label>
                  <select id="inputaccesoDrive" name="inputaccesoDrive" class="form-control custom-select" class="form-control">
                    <option value="1" @if($usuario['accesoDrive'] == true) selected @endif> {{ __('text.si') }} </option>
                    <option value="0" @if($usuario['accesoDrive'] == false) selected @endif> {{ __('text.no') }} </option>
                  </select>
              </div>
        </div>

        <div Class='col'>
            <div class="input-group">
                <button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" disabled data-placement="top" title="{{ __('text.txtAccs2')}}">
                    ?
                </button>
               <label for="inputAccesoJunta" class="form-control" >{{ __('text.accs2')}}</label>
                  <select id="inputAccesoJunta" name="inputAccesoJunta" class="form-control  custom-select" >
                    <option value="1" @if($usuario['accesoJunta'] == true) selected @endif> {{ __('text.si') }} </option>
                    <option value="0" @if($usuario['accesoJunta'] == false) selected @endif> {{ __('text.no') }} </option>
                  </select>
              </div>
        </div>
    </div>
    <br><hr>
    <div class="form-group row">
        <div class="col-4">
            <h6><span class="badge badge-pill badge-dark">{{ __('text.baja')}}</span>
            {{ __('text.txtBaja')}}</h6>
        </div>
        <div class="col-8">
            @if($usuario['bajaSocio']==null)
                <h4>{{ __('text.txtNOBaja')}}<small>{{ __('text.txtNOBajaintron')}}</small></h4><input type='text' data-provide="datepicker" data-date-format="d/m/yyyy" id="fechaBaja" name="fechaBaja" class="form-control datepicker" value="" />
            @else
                <h4><strong>{{ __('text.txtSIBaja')}}</strong></h4> <input type='text' data-provide="datepicker" data-date-format="d/m/yyyy" id="fechaBaja" name="fechaBaja" class="form-control datepicker" value="{{ date('d/m/Y', strtotime($usuario['bajaSocio']))}}" />
            @endif
        </div>
    </div>


    <br><hr>
    <div class="form-group row">
        <div class="col">
            <h6><span class="badge badge-pill badge-primary">{{ __('text.notas')}}</span>
            {{ __('text.txtNotas')}}</h6>

            <div class="col-10 align-self-center">
              <textarea class="form-control" id="txtNotas" maxlength="600" name="txtNotas" rows="4"><?php echo $usuario['notas'] ?></textarea>
            </div>
        </div>
    </div>
    <br>
    <hr>
@endif

@if (Auth::user()->hasRole(['secretario','admin','tesorero',]))
    <h5>{{ __('text.SeccionTesorero')}}</h5>
    <strong><label">{{ __('text.cuotaSocio')}}</label></strong>
    <div class="form-check mb-2 mr-sm-2">
        <input class="form-check-input" type="checkbox" id="actualizaCuotaCheck" name="actualizaCuotaCheck" data-toggle="collapse" href="#collapseTesorero" role="button" aria-expanded="false" aria-controls="collapseTesorero" unchecked>
        <label class="form-check-label" for="actualizaCheck">
            {{__('text.nuevaCuotaCheck')}}
        </label>
    </div>
    <div class="form-group row collapse" id="collapseTesorero">
            <div class="col-4">
                <label for="tipoCuota">{{ __('text.tipoCuota')}}</label>
                <select id="tipoCuota" name="tipoCuota" class="select custom-select">
                    @foreach ($tiposCuota as $tp)
                        <option @if(!$noCuota && $cuota['id'] == $tp['id']) selected @endif value="{{$tp['id']}}" data-cant="{{$tp['cantidad']}}">{{$tp['nombre']}} ({{$tp['cantidad']}}{{ __('text.simbDin')}})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <label for="cantidadCuota">{{ __('text.cantidadCuota')}}</label>
                @if($noCuota == true)
                    <input class="form-control" autocomplete="off" type="text" name="cantidadCuota" id="cantidadCuota" onkeypress="return esNumero(event)" value=""/>
                @else
                    <input class="form-control" autocomplete="off" type="text" name="cantidadCuota" id="cantidadCuota" onkeypress="return esNumero(event)" value="{{$tiposCuota->where('id',$cuota['tipoCuota'])->first()->cantidad}}"/>
                @endif
            </div>
            <div class="col-4">
                <label for="fechaCuota">{{ __('text.fechaCuota')}}</label>
                <input type='text' data-provide="datepicker" data-date-format="d/m/yyyy" id="fechaCuota" name="fechaCuota" required class="form-control datepicker" value="{{ date('d/m/Y', strtotime($cuota['fechaCuota']))}}" />
            </div>
    </div>
@endif
