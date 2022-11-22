@extends('layouts.app')

@section('content')

<script type="text/javascript" src="{{ asset('js/bootstable.js') }}"></script>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <strong>{{ __('text.textoConfiguras') }}</strong>
                        <strong><a href="{{ route('visor') }}"><input type="button" class="btn btn-outline-success btn-sm" value="{{ __('text.visorEventos')}}" /></a></strong>
                        <a href="{{ route('home') }}"><input type="button" class="btn btn-outline-secondary btn-sm" value="{{ __('text.back')}}" /></a>
                    </div>
                    <div class="card-body">
                        @if (Auth::user()->hasPermissionTo('permiso_editar_socios'))
                            <form class="form-inline" enctype="multipart/form-data" action="{{ url('guardaConfiguras') }}" method="POST">
                                 {{ csrf_field() }}
                                    <table class="table table-hover table-bordered table-sm text-center" id="tablaConfigura" style="table-layout: fixed;">
                                        <thead class="thead-light">
                                            <tr class="table-primary">
                                                <th class="col-md-2" style="width: 20%;">{{ __('text.colNombreCorto')}}</th>
                                                <th class="col-md-6"  style="width: 60%;">{{ __('text.colDescripcion')}}</th>
                                                <th class="col-md-2"  style="width: 20%;">{{ __('text.colValor')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($configuras as $configura)
                                                <tr>
                                                    <td class="align-middle" style="display: none !important;"><input type="text" id="id[]" name="id[]" value="{{$configura['id']}}"></td>
                                                    <td class="align-middle">{{$configura['nombre']}}</td>
                                                    <td class="align-middle"><input type="text" class="md-form form-control" style="width: 100%" id="descripcion[]" name="descripcion[]" value="{{$configura['descripcion']}}"></td>
                                                    @if ($configura['valorNumero'] != null)
                                                        <td class="align-middle"><input type="text" class="md-form form-control" style="width: 100%; text-align: center;" id="valorNumero[]" name="valorNumero[]" value="{{$configura['valorNumero']}}"></td>
                                                        <td class="align-middle" style="display: none;"><input type="text" class="md-form form-control" style="width: 100%; text-align: center;" id="valorTexto[]" name="valorTexto[]" value="{{$configura['valorTexto']}}" ></td>
                                                    @else
                                                        <td class="align-middle"><input type="text" class="md-form form-control" style="width: 100%; text-align: center;" id="valorTexto[]" name="valorTexto[]" value="{{$configura['valorTexto']}}"></td>
                                                        <td class="align-middle" style="display: none;"><input type="text" class="md-form form-control" style="width: 100%; text-align: center;" id="valorNumero[]" name="valorNumero[]" value="{{$configura['valorNumero']}}" ></td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{--
                                    ##     ##  #######   ######     ###    ##       ####    ###     ######
                                    ##     ## ##     ## ##    ##   ## ##   ##        ##    ## ##   ##    ##
                                    ##     ## ##     ## ##        ##   ##  ##        ##   ##   ##  ##
                                    ##     ## ##     ## ##       ##     ## ##        ##  ##     ##  ######
                                     ##   ##  ##     ## ##       ######### ##        ##  #########       ##
                                      ## ##   ##     ## ##    ## ##     ## ##        ##  ##     ## ##    ##
                                       ###     #######   ######  ##     ## ######## #### ##     ##  ######
                                        --}}
                                    <h4> {{__('text.vocalias')}} </h4>
                                    <table class="table table-success table-hover text-center" id="tablaConfigura">
                                        <thead class="thead-light">
                                            <tr style="display: none">
                                                <th class="col-md-2"></th>
                                                <th class="col-md-6"></th>
                                                <th class="col-md-2"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                   {{__('text.vocalias')}}
                                                </td>
                                                <td>
                                                    @foreach ($vocalias as $vocalia)
                                                            <span style="color: {{$colores[$vocalia->color]}}">&#128447;&nbsp;</span>{{$vocalia['nombre']}} &nbsp;&nbsp;
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseNuevaVocalia" aria-expanded="false" aria-controls="collapseNuevaVocalia" >
                                                        {{__('text.crearVocalia')}}
                                                    </button>
                                                    &nbsp;
                                                    <button class="btn btn-danger" type="button" data-toggle="collapse" data-target="#collapseBorrarVocalia" aria-expanded="false" aria-controls="collapseBorrarVocalia" >
                                                        {{__('text.borrarVocalia')}}
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div class="collapse col-12" id="collapseNuevaVocalia">
                                        <table class="table table-striped table-light" id="tablaNuevaVocalia">
                                            <thead class="thead-light">
                                                <tr style="display: none">
                                                    <th class="col-2"></th>
                                                    <th class="col-10"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        {{ __('text.colNombreVocalia')}}
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="text" name="inputNombreVocalia" id="inputNombreVocalia" placeholder="{{ __('text.newVocaliaNombre')}}" style="width: 100% !important"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        {{ __('text.colDescripcionVocalia')}}
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="text" name="inputDescVocalia" id="inputDescVocalia" placeholder="{{ __('text.newVocaliaDesc')}}" style="width: 100% !important"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        {{ __('text.inputImagenVocalia')}}
                                                    </td>
                                                    <td>
                                                        <input id="inputImagenVocalia" type="file" class="form-control @error('file') is-invalid @enderror" name="inputImagenVocalia" value="{{ old('file') }}"  style="width: 100% !important">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        {{ __('text.colorVocalia')}}
                                                    </td>
                                                    <td>
                                                        <div style="background-color: white" id="selc">
                                                            <select class="form-control selectpicker" id="selectColor" name="selectColor" style="width: 100% !important">
                                                                @foreach ($coloresValidos as $key => $colorv)
                                                                    <option value="{{$key}}" data-content="<span class='label' style='background-color: {{$colorv}} !important'>&#128448;</span>">
                                                                        <span style="background-color: {{$colorv}} !important">&#9635;</span>
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        {{ __('text.calendarioID')}}
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="text" name="idCalendario" id="idCalendario" placeholder="{{ __('text.calendarioIDtext')}}" style="width: 100% !important"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <input class="form-check-input form-control" type="checkbox" id="crearVocaliaCheck" name="crearVocaliaCheck" unchecked>
                                                        <label class="form-check-label form-control" for="crearVocaliaCheck" style="background-color: orange; color: white">
                                                            {{__('text.nuevaVocaliaCheck')}}
                                                        </label>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="collapse col-12" id="collapseBorrarVocalia">
                                        <div class="row">
                                            <div class="col-4">
                                                <select class="custom-select w-100 p-3" size="{{$vocalias->count()}}" name="vocaliaDestroy" id="vocaliaDestroy">
                                                    @foreach ($vocalias as $vocalia)
                                                            <option value="{{$vocalia['id']}}">{{$vocalia['nombre']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-4 text-center">
                                                {{__('text.avisoBorrarVocalia')}}
                                            </div>
                                            <div class="col-4 text-center">
                                                <input class="form-check-input-lg form-control" type="checkbox" id="estoySeguro" name="estoySeguro" unchecked>
                                                <label class="form-check-label" for="estoySeguro" style="width: 100%" style="background-color: orange; color: white">
                                                    {{__('text.checkBorrarSeguro')}}
                                                </label>
                                            </div>
                                        </div>
                                    </div>



                                    {{--
                                        ******  **     **   *******   **********     **      ********
                                      **////**/**    /**  **/////** /////**///     ****    **//////
                                     **    // /**    /** **     //**    /**       **//**  /**
                                    /**       /**    /**/**      /**    /**      **  //** /*********
                                    /**       /**    /**/**      /**    /**     **********////////**
                                    //**    **/**    /**//**     **     /**    /**//////**       /**
                                     //****** //*******  //*******      /**    /**     /** ********
                                      //////   ///////    ///////       //     //      // //////// --}}
                                    <h4> {{__('text.tiposCuota')}} </h4>
                                    <div class="collapse col-12" id="collapseBorrar">
                                        <div class="row">
                                            <div class="col-4">
                                                <select class="custom-select w-100 p-3" size="{{$cuotas->count()}}" name="vocaliaDestroy" id="vocaliaDestroy">
                                                    @foreach ($cuotas as $cuota)
                                                            <option value="{{$cuota['nombre']}} ({{$cuota['cantidad']}} Lang::get('text.simbDin')}})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-4 text-center">
                                                {{__('text.avisoBorrarCuota')}}
                                            </div>
                                            <div class="col-4 text-center">
                                                <input class="form-check-input form-control" type="checkbox" id="estoySeguroCuota" name="estoySeguroCuota" unchecked>
                                                <label class="form-check-label" for="estoySeguro" style="width: 100%" style="background-color: red; color: white">
                                                    {{__('text.checkBorrarSeguro')}}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <table class="table table-info table-hover text-center" id="tablaConfigura">
                                        <thead class="thead-light">
                                            <tr style="display: none">
                                                <th class="col-md-2"></th>
                                                <th class="col-md-2"></th>
                                                <th class="col-md-6"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                   {{__('text.tiposCuota')}}
                                                </td>
                                                <td>
                                                    @foreach ($cuotas as $cuota)
                                                            {{$cuota['nombre']}} ({{$cuota['cantidad']}}{{ __('text.simbDin')}}) &nbsp;&nbsp;
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <button class="btn btn-warning" type="button" data-toggle="collapse" data-target="#collapseEditarCuota" aria-expanded="false" aria-controls="collapseEditarCuota" >
                                                        {{__('text.editarTiposCuota')}}
                                                    </button>
                                                    &nbsp;
                                                    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExampleCuota" aria-expanded="false" aria-controls="collapseExampleCuota" >
                                                        {{__('text.crearCuota')}}
                                                    </button>
                                                    &nbsp;
                                                    <button class="btn btn-danger" type="button" data-toggle="collapse" data-target="#collapseBorrarCuota" aria-expanded="false" aria-controls="collapseBorrarCuota" >
                                                        {{__('text.borrarCuota')}}
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div class="collapse col-12" id="collapseEditarCuota">
                                        <table class="table table-hover table-bordered table-sm text-center" id="tablaTiposCuota" style="table-layout: fixed;">
                                            <thead class="thead-light">
                                                <tr class="table-primary">
                                                    <th class="col-md-2" style="width: 20%;">{{ __('text.nombre')}}</th>
                                                    <th class="col-md-6"  style="width: 60%;">{{ __('text.colDescripcion')}}</th>
                                                    <th class="col-md-2"  style="width: 20%;" data-toggle="tooltip" data-placement="top" title="{{__('text.cantConfText')}}">{{ __('text.cantidadeneur')}}</th>
                                                    <th class="col-md-2"  style="width: 10%;" data-toggle="tooltip" data-placement="top" title="{{__('text.mesesConfText')}}">{{ __('text.meses')}}</th>
                                                    <th class="col-md-2"  style="width: 10%;" data-toggle="tooltip" data-placement="top" title="{{__('text.invitText')}}">{{ __('text.invitaciones')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($cuotas as $tp)
                                                    <tr>
                                                        <td class="align-middle" style="display: none !important;"><input type="text" id="TPid[]" name="TPid[]" value="{{$tp['id']}}"></td>
                                                        <td class="align-middle"><input type="text" class="md-form form-control" style="width: 100%" id="TPnombre[]" name="TPnombre[]" value="{{$tp['nombre']}}"></td>
                                                        <td class="align-middle"><input type="text" class="md-form form-control" style="width: 100%" id="TPdescripcion[]" name="TPdescripcion[]" value="{{$tp['descripcion']}}"></td>
                                                        <td class="align-middle"><input type="number" min=0 class="md-form form-control" style="width: 100%; text-align: center;" id="TPcantidad[]" name="TPcantidad[]" value="{{$tp['cantidad']}}"></td>
                                                        <td class="align-middle"><input type="number" min=1 class="md-form form-control" style="width: 100%; text-align: center;" id="TPmeses[]" name="TPmeses[]" value="{{$tp['meses']}}" ></td>
                                                        <td class="align-middle"><input type="number" min=1 class="md-form form-control" style="width: 100%; text-align: center;" id="TPmeses[]" name="TPmeses[]" value="{{$tp['invitaciones']}}" ></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="collapse col-12" id="collapseExampleCuota">
                                        <table class="table table-striped table-light" id="tablaNuevaCuota">
                                            <thead class="thead-light">
                                                <tr style="display: none">
                                                    <th class="col-2"></th>
                                                    <th class="col-10"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        {{ __('text.colNombreCuota')}}
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="text" name="inputNombrecuota" id="inputNombrecuota" placeholder="{{ __('text.newCuotaNombre')}}" style="width: 100% !important"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        {{ __('text.colDescripcionCuota')}}
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="text" name="inputDescCuota" id="inputDescCuota" placeholder="{{ __('text.newCuotaDesc')}}" style="width: 100% !important"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        {{ __('text.colCantidadCuotaDesc')}}
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="number" min=1 name="inputCantCuota" id="inputCantCuota" placeholder="{{ __('text.colCantidadCuota')}}" style="width: 100% !important"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        {{ __('text.colMesesCuota')}}
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="number" min=1 name="inputMesescuota" id="inputMesescuota" style="width: 100% !important" value="1"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <input class="form-check-input form-control" type="checkbox" id="crearCuotaCheck" name="crearCuotaCheck" unchecked>
                                                        <label class="form-check-label form-control" for="crearCuotaCheck" style="background-color: orange; color: white">
                                                            {{__('text.nuevaCuotaCheck')}}
                                                        </label>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="collapse col-12" id="collapseBorrarCuota">
                                        <div class="row">
                                            <div class="col-4">
                                                <select class="custom-select w-100 p-3" size="{{$cuotas->count()}}" name="cuotaDestroy" id="cuotaDestroy">
                                                    @foreach ($cuotas as $cuota)
                                                            <option value="{{$cuota['id']}}">{{$cuota['nombre']}} ({{$cuota['cantidad']}} {{ __('text.simbDin')}})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-4 text-center">
                                                {{__('text.avisoBorrarCuota')}}
                                            </div>
                                            <div class="col-4 text-center">
                                                <input class="form-check-input form-control" type="checkbox" id="estoySeguroCuota" name="estoySeguroCuota" unchecked>
                                                <label class="form-check-label" for="estoySeguroCuota" style="width: 100%" style="background-color: red; color: white">
                                                    {{__('text.checkBorrarSeguro')}}
                                                </label>
                                            </div>
                                        </div>
                                    </div>


                                </div>

                                <br>
                                <div class="form-group row">
                                    <div class="col col-lg-2">
                                        <button class="btn btn-success form-control" type="submit" data-toggle="modal" data-target="#trabajando_modal">
                                            {{ __('text.btnGuardar')}}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @else
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
</div>

<script type="text/javascript">
    $('tablaConfigura').SetEditable();

    function cambiaColor($color){
        document.getElementById('selc').style.color =  "$color";
    }

    $('tablaConfigura').SetEditable({
      columnsEd: "1,2,3"
    });

    $('tablaConfigura').SetEditable({
      onEdit: function() {
        alert("Has editado la linea");
      },
      onDelete: function() {
        alert("Es mejor no eliminar nada...");
      },
      onBeforeDelete: function() {},
      onAdd: function() {
        alert("¿Para qué quieres añadir más configuraciones?");
      }
    });
</script>

<noscript>Please, enable JavaScript to see this page. Por favor, habilita JavaScript para ver esta página.</noscript>
@endsection

