<div class="form-group row">
    <div class="col-md-auto">
       <strong><label for="invitaciones" class="col-form-label">{{ __('text.numInvitaciones')}}</label></strong>
    </div>
    <div class="col-2">
        <input id="invitaciones" name="invitaciones" type="text" readonly class="form-control" value="{{$usuario['invitaciones']}}">
    </div>
    <div class="col btn-group">
        @if($usuario['invitaciones']>0)
            <a href="#" class="btn btn-outline-success" data-toggle="modal" data-target="#gastarInvitacion_modal">
                {{ __('text.btnInvitacion')}}
            </a>
         @else
            <a href="#" disabled class="btn btn-outline-secondary">
                {{ __('text.btnNoInvitacion')}}
            </a>
        @endif

        <a href="{{ route('visorInvitaciones') }}" class="btn btn-outline-info">
        {{ __('text.vistaInvitaciones')}}
        </a>
        @if (Auth::user()->hasPermissionTo('permiso_editar_socios'))
            <a href="{{url('/resetearInvitaciones')}}/{{$usuario['id']}}" class="btn btn-outline-warning">
                {{ __('text.btnResetearInvitacion')}}
            </a>
        @endif
    </div>
</div>
<hr>
<div class="form-group row align-items-center">
    <div class="col text-justify">
        <strong><label class="col-form-label">{{ __('text.asignacionSocio')}}</label></strong>
    </div>
    <div class="col align-self-center">
        <label class="col-form-label" for="asignacionSocio">{{ __('text.eligeAsignacionSocio')}} &nbsp;&#8688;&nbsp;</label>
    </div>
    <div class="col">
        <select class="selectpicker" id="asignacionSocio[]" name="asignacionSocio[]" multiple>
            @foreach ($vocalias as $vocalia)
                <option value="{{$vocalia['id']}}" @foreach ($as as $asi) @if($asi['idVocalia']==$vocalia['id']) selected @endif @endforeach style="width: 50%;">
                    {{$vocalia['nombre']}}
                </option>
            @endforeach
        </select>
    </div>
</div>
<hr>
<div class="form-group row">
    <div class="col-12">
        <label class="col-form-label">{{ __('text.socioUltimaCuota')}}: </label>
        @if ($noCuota == true)
            <div class="col-12">
                <div class="input-group mb-3">
                    <label for="cuotaRenovacionDsocio" class="col-form-label"><strong>{{ __('errortext.noCuotaEncontrada')}}</strong></label>
                </div>
            </div>
        @else
            <div class="form-group row input-group">
                <div class="col-4">
                    <div class="input-group mb-3">
                        <label for="cuotaRDsocio" class="col-form-label">{{ __('text.tipoCuota')}}: </label>
                        @if($noCuota == true)
                            <input class="form-control" type="text" readonly name="cuotaRDsocio" value="" style="background-color: white; border-style: none; !important">
                        @else
                            <input class="form-control" type="text" readonly name="cuotaRDsocio" value="{{$tiposCuota->where('id',$cuota['tipoCuota'])->first()->nombre}}" style="background-color: white; border-style: none; !important">
                        @endif
                    </div>
                </div>
                <div class="col-4">
                    <div class="input-group mb-3">
                        <label for="cuotaRDsocio" class="col-form-label">{{ __('text.cantidadCuota')}}: </label>
                        @if($noCuota == true)
                            <input class="form-control" type="text" readonly name="cuotaRDsocio" value="" style="background-color: white; border-style: none; !important">
                        @else
                            <input class="form-control" type="text" readonly name="cuotaRDsocio" value="{{$cuota['cantidad']}} {{__('text.simbDin')}}" style="background-color: white; border-style: none; !important">
                        @endif
                    </div>
                </div>
                <div class="col-4">
                    <div class="input-group mb-3">
                        <label for="cuotaDsocio" class="col-form-label">{{ __('text.fechaCuota')}}</label>
                        <input class="form-control" type="text" data-date-format="d/m/yyyy" readonly name="cuotaDsocio" value="{{ date('d/m/Y', strtotime($cuota['fechaCuota']))}}" style="background-color: white; border-style: none; !important">
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="input-group mb-3">
                    <label for="cuotaRenovacionDsocio" class="col-form-label"><strong>{{ __('text.fechaRenovacion')}}&nbsp;&nbsp;&#128198;&nbsp;&nbsp;</strong></label>
                    <input type="text" data-date-format="d/m/yyyy" readonly name="cuotaRenovacionDsocio" value="{{ date('d/m/Y', strtotime($fechaRenovacionCuota))}}" style="width: auto; background-color: white; border-style: none; !important">
                    <label for="cuotaRDsocio" class="col-form-label">{{__('text.emailRecordando')}}</label>
                </div>
            </div>
        @endif
    </div>
</div>
<hr>
<div class="form-group row">
    <div class="col">
        <strong><label class="col-form-label">{{ __('text.preguntasPrivacidad')}}</label></strong>
        <div class="col-12 input-group">
            <label class="col-form-label" for="recibirCorreos">{{ __('text.checkEmails')}} &nbsp;&#8688;&nbsp;</label>
            <select class="selectpicker" id="recibirCorreos" name="recibirCorreos">
                <option value="1" @if($usuario['recibirCorreos']) selected @endif>&#9989; {{__('text.si')}}</option>
                <option value="0" @if(!$usuario['recibirCorreos']) selected @endif>&#10060; {{__('text.no')}}</option>
            </select>
        </div>

        <div class="col-12 input-group">
            <label class="col-form-label" for="privacidad">{{ __('text.checkPrivacidad')}} &nbsp;&#8688;&nbsp;</label>
            <select class="selectpicker" id="privacidad" name="privacidad">
                <option value="1" @if($usuario['privacidad']) selected @endif>&#9989; {{__('text.si')}}</option>
                <option value="0" @if(!$usuario['privacidad']) selected @endif>&#10060; {{__('text.no')}}</option>
            </select>
        </div>
    </div>
</div>
