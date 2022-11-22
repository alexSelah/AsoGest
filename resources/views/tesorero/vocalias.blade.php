@extends('layouts.app')

@section('content')

<div class="container">
    <br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>{{ __('text.gestVocalias') }}</strong>
                        @if (Auth::user()->hasRole('tesorero'))
                            <a href="{{ route('tesorero') }}"><input type="button" class="btn btn-outline-secondary btn-sm" value="{{ __('text.back')}}" /></a>
                        @elseif (Auth::user()->hasRole('secretario'))
                            <a href="{{ route('secretario') }}"><input type="button" class="btn btn-outline-secondary btn-sm" value="{{ __('text.back')}}" /></a>
                        @else
                            <a href="{{ url()->previous() }}"><input type="button" class="btn btn-outline-secondary btn-sm" value="{{ __('text.back')}}" /></a>
                        @endif
                    </div>
                     <br>
                    {{ __('text.gestVocaliasDesc') }}
                <div class="card-body">
                    @if(count($vocalias) > 0)
                    <hr>
                        @if(Auth::user()->hasAnyPermission('Acceso_total', 'permiso_tesoreria', 'permiso_secretaria'))
                            <form action="{{ route('guardaVocaliasTesorero') }}" enctype="multipart/form-data" method="POST" name="vocaliaForm">
                                {{ csrf_field() }}
                                <div class="card-deck">
                                    <?php $aux = 1; ?>
                                    @foreach($vocalias as $vocalia)
                                        <?php
                                            if($aux > 3){
                                                echo "</div><br>";
                                                echo "<div class=\"card-deck\">";
                                                $aux = 1;
                                            }
                                        ?>

                                        <div class="card" style="width: 18rem;">
                                            <input type="text" name="id[]" id="id[]" value="{{$vocalia->id}}" style="display:none !important;">
                                        <img class="card-img-top" src="{{ asset('images/'.$vocalia->imagen) }}" alt="Card image cap">
                                        <div class="card-body">
                                            <h5 class="card-title"> {{ __('text.nombre') }}:
                                                <input class="form-control" type="text" name="nombre[]" id="nombre[]" value="{{$vocalia->nombre}}" maxlength="191" style="width: 100% !important" readonly />
                                            </h5>
                                            <p class="card-text"> {{ __('text.colDescripcionVocalia') }}:
                                                <textarea  class="form-control" name="desc[]" id="desc[]" rows="4">{{$vocalia->descripcion}}</textarea>
                                            </p>
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">{{ __('text.cambiaImagen') }}: <br>
                                                <input id="inputImagenVocalia[]" type="file" class="form-control @error('file') is-invalid @enderror" name="inputImagenVocalia[]" value="{{ old('file') }}"  style="width: 100% !important">
                                            </li>
                                            <li class="list-group-item">{{ __('text.color') }}:
                                                <div style="background-color: white" id="selc">
                                                    <select class="form-control selectpicker" id="selectColor[]" name="selectColor[]" style="width: 100% !important">
                                                        @foreach ($coloresValidos as $key => $colorv)
                                                            <option value="{{$key}}" data-content="<span class='label' style='background-color: {{$colorv}} !important'>&#9635;</span>" @if($vocalia->color == $key) selected @endif>
                                                                <span style="background-color: {{$colorv}} !important">&#9635;</span>
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </li>
                                            <li class="list-group-item">{{ __('text.idCalendario') }}: <br>
                                                <input class="form-control" type="text" maxlength="191" name="idCalendario[]" id="idCalendario[]" value="{{$vocalia->idCalendario}}" style="width: 100% !important"/>
                                            </li>
                                            @if(Auth::user()->hasAnyPermission('Acceso_total', 'permiso_tesoreria'))
                                                <li class="list-group-item"><b>{{ __('text.presupuesto') }}:</b>
                                                    <div class="input-group mb-2 mr-sm-2">
                                                        <input class="form-control" type="text" maxlength="191" name="presupuesto[]" id="presupuesto[]" value="{{$vocalia->presupuesto}}" style="width: 100% !important" onkeypress="return esNumero(event)"/>
                                                        <div class="input-group-prepend">
                                                        <div class="input-group-text">{{__('text.simbDin')}}</div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endif
                                        </ul>
                                        </div>

                                        <?php
                                            $aux = $aux + 1;
                                        ?>
                                    @endforeach

                                    <?php
                                        for ($i = $aux;; $i++) {
                                            if($aux <= 3){
                                                echo  "<div class=\"card\" style=\"width: 18rem; border:none;\"> <div class=\"card-body\"></div></div>";
                                                $aux = $aux +1;
                                            }else{
                                                break;
                                            }
                                        }
                                    ?>
                                </div>

                                <br><hr>
                                <div class="form-group row justify-content-md-center">
                                    <div class="col-md-auto">
                                        <button class="btn btn-success form-control" type="submit" data-toggle="modal" data-target="#trabajando_modal">
                                            {{ __('text.btnGuardar')}}
                                        </button>
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
                    @else
                        <div class="card">
                            <div class="row text-center">
                                <div class="col-2" style="float: none; margin: 0 auto;">
                                    <img src="{{ asset('movil\images\confused.png') }}" alt="" style="height: 200px;float:center;!important;"/>
                                </div>
                                <div class="col-6" style="margin: auto;">
                                    <label class="col-form-label"><h2><strong>{{ __('text.noExistVocalEdit')}}</strong></h2></label>
                                </div>
                            </div>
                            <div class="row text-center" style="justify-self: end;">
                                <div class="col" style="float: none; margin: auto; margin-bottom: 2px">
                                    <a href="{{ route('configura') }}"><input type="button" class="btn btn-outline-danger" value="{{ __('text.btnConfiguracion')}}" /></a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
