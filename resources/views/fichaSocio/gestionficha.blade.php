@extends('layouts.app')

<style type="text/css">
    nav > .nav.nav-tabs{
      border: none;
    color:#fff;
    background:#272e38;
    border-radius:0;
    }
    nav > div a.nav-item.nav-link,
    nav > div a.nav-item.nav-link.active
    {
      border: none;
    padding: 18px 25px;
    color:#fff;
    background:#272e38;
    border-radius:0;
    }

    nav > div a.nav-item.nav-link.active:after
     {
      content: "";
      position: relative;
      bottom: -60px;
      left: -10%;
      border: 15px solid transparent;
      border-top-color: #780000 ;
    }
    .tab-content{
        background: #fdfdfd;
        line-height: 25px;
        border: 1px solid #ddd;
        border-top:5px solid #780000;
        border-bottom:5px solid #e74c3c;
        padding:30px 25px;
        animation: fadeEffect 1s;
    }
    nav > div a.nav-item.nav-link:hover,
    nav > div a.nav-item.nav-link:focus
    {
      border: none;
        background: #780000;
        color:#fff;
        border-radius:0;
        transition:background 0.20s linear;
    }
</style>

@section('content')
    <div class="container"> {{--CONTAINER PRINCIPAL DE PÁGINA--}}
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    {{-- #p1 {background-color: rgba(255, 0, 0, 0.3);}  red with opacity
                    #p2 {background-color: rgba(0, 255, 0, 0.3);}  green with opacity
                    #p3 {background-color: rgba(0, 0, 255, 0.3);}  blue with opacity --}}
                    <div class="card-header" style="background-color: rgba(0,0,255,0.2) !important;">
                        <div class="d-flex justify-content-between align-items-center">
                           <strong>{{ __('text.gestionFicha') }}</strong>
                            @if (Auth::user()->hasRole('tesorero'))
                                <a href="{{ route('tesorero') }}"><input type="button" class="btn btn-outline-secondary btn-sm" value="{{ __('text.back')}}" /></a>
                            @elseif (Auth::user()->numsocio == $usuario['numSocio'])
                                <a href="{{ route('home') }}"><input type="button" class="btn btn-outline-secondary btn-sm" value="{{ __('text.back')}}" /></a>
                            @else
                                <a href="{{ route('secretario') }}"><input type="button" class="btn btn-outline-secondary btn-sm" value="{{ __('text.back')}}" /></a>
                            @endif
                        </div>
                    </div>

                    {{-- CONTAINER de la ficha--}}
                    <div class="container" >
                    <form enctype="multipart/form-data" action="{{ route('usuarioStore') }}" method="POST">
                    {{ csrf_field() }}
                        <div class="card-body" style="border:0px">
                            {{ __('text.descGestionFicha') }}
                            <br/><hr>
                            <!-- Nav tabs -->
                            <nav>
                                <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                  <a class="nav-item nav-link active" id="nav-datosPersonales-tab" data-toggle="tab" href="#nav-datosPersonales" role="tab" aria-controls="nav-datosPersonales" aria-selected="true">{{__('text.datosPersonales')}}</a>
                                  <a class="nav-item nav-link" id="nav-contacto-tab" data-toggle="tab" href="#nav-contacto" role="tab" aria-controls="nav-contacto" aria-selected="false">{{__('text.datosContacto')}}</a>
                                  <a class="nav-item nav-link" id="nav-preferencias-tab" data-toggle="tab" href="#nav-preferencias" role="tab" aria-controls="nav-preferencias" aria-selected="false">{{__('text.datosPreferencias')}}</a>
                                  <a class="nav-item nav-link" id="nav-calendario-tab" data-toggle="tab" href="#nav-calendario" role="tab" aria-controls="nav-calendario" aria-selected="false">{{__('text.datosCalendario')}}</a>
                                  @if (Auth::user()->hasRole(['secretario','admin','tesorero',]))
                                    <a class="nav-item nav-link" id="nav-secretaria-tab" data-toggle="tab" href="#nav-secretaria" role="tab" aria-controls="nav-secretaria" aria-selected="false">{{__('text.administracionSocio')}}</a>
                                  @endif
                                </div>
                            </nav>
                          <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-datosPersonales" role="tabpanel" aria-labelledby="nav-datosPersonales-tab">
                              @include('fichaSocio.datosPersonales')
                            </div>
                            <div class="tab-pane fade" id="nav-contacto" role="tabpanel" aria-labelledby="nav-contacto-tab">
                              @include('fichaSocio.contacto')
                            </div>
                            <div class="tab-pane fade" id="nav-preferencias" role="tabpanel" aria-labelledby="nav-preferencias-tab">
                              @include('fichaSocio.preferencias')
                            </div>
                            <div class="tab-pane fade" id="nav-calendario" role="tabpanel" aria-labelledby="nav-calendario-tab">
                              @include('fichaSocio.calendario')
                            </div>
                            <div class="tab-pane fade" id="nav-secretaria" role="tabpanel" aria-labelledby="nav-secretaria-tab">
                              @include('fichaSocio.administracionSocio')
                            </div>

                          </div>

                          <hr>
                          <br>
                          <div class="form-group row justify-content-md-center">
                            <div class="col col-lg-2">
                                <button class="btn btn-success form-control" type="submit" data-toggle="modal" data-target="#trabajando_modal">
                                    <strong>{{ __('text.btnGuardar')}}</strong>
                                </button>
                            </div>
                        </div> {{--Fin de Boton general de Guardar Ficha--}}

                        </div> {{-- Card-Body--}}
                    </form>
                    </div> {{-- CONTAINER de la ficha--}}
                </div>
            </div>
        </div>

        <!-- Modal Gastar Invitacion-->
            <div id="gastarInvitacion_modal" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="container-fluid">
                        <!-- Modal content-->
                        <div class="modal-content col-md-16">
                            <div class="modal-body">
                                <label class="label-info">{{ __('text.gestionInvitaciones') }}</label>
                                <hr>
                                <form class="form-horizontal" method="POST" action="{{ route('gastarInvitacion') }}">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <input class="form-control" type="text" readonly name="idSocio" id="idSocio" value="{{$usuario['id']}}" style="display: none !important;">
                                    </div>
                                    <div class="form-group row">
                                        <label for="fechaInvitacion" class="col-sm-3 col-form-label">{{ __('text.fechaInvitacion')}}</label>
                                        <div class="col">
                                            <input type='text' data-provide="datepicker" data-date-format="d/m/yyyy" id="fechaInvitacion" name="fechaInvitacion" required class="form-control datepicker" value="{{ date('d/m/Y')}}" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="invitado" class="col-sm-3 col-form-label">{{ __('text.invitado')}}</label>
                                        <div class="col">
                                            <input type='text' id="invitado" maxlength="191" required class="form-control" name="invitado" placeholder="{{__('text.invitadotxt')}}">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row justify-content-md-center">
                                        <div class="col col-lg-2">
                                            <button class="btn btn-success form-control" type="submit">
                                                {{ __('text.btnInvitacion') }}
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
        <!--FIN Modal Gastar Invitacion-->
    </div> {{-- FIN DE CONTAINER PRINCIPAL DE PÁGINA--}}

<script type="text/javascript">

    function colapsar(argument) {
        var sel = document.getElementById('roles');
        if(sel!=null){
            var opt = sel.options[sel.selectedIndex];
            if(sel.value == "6" || opt.text == "├───── Vocal"){
                document.getElementById("vocaliaSelectDiv").style.display = "block";
            }
            else{
                document.getElementById("vocaliaSelectDiv").style.display = "none";
            }
        }
    }

    function colapsarTesoreria(argument) {
        if(document.getElementById("actualizaCuotaCheck").checked == false){
            $('.collapseTesorero').collapse('hide');
        }else{
            $('.collapseTesorero').collapse('show');
        }
    }

    $(".select").change(function () {
        nuevaCant = $(this).children(':selected').data('cant');
        document.getElementById("cantidadCuota").value = nuevaCant;
    });

    $(document).ready(function(){
        colapsar();
        colapsarTesoreria();
        $('.datepicker').datepicker({
           todayBtn: 'linked',
           language: 'es',
           autoclose: true,
           todayHighlight: true,
           format: 'd/m/yyyy'
        });

        $(".reveal").on('click',function() {
            var $pwd = $(".pwd");
            if ($pwd.attr('type') === 'password') {
                $pwd.attr('type', 'text');
            } else {
                $pwd.attr('type', 'password');
            }
        });
        var element = document.getElementById("tipoCuota");
        var nuevaC = $(':selected', element).attr("data-cant");
        document.getElementById("cantidadCuota").value = nuevaC;
    });


</script>

@endsection
