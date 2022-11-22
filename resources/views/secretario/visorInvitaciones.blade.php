@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @if (Auth::user()->hasPermissionTo('permiso_editar_socios'))
                        <a href="{{ route('resetearTodasInvitaciones') }}" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('text.QuestionSureReset')}}')">
                            {{ __('text.resetearTodasInvitaciones')}}
                        </a>
                        <br>
                    @endif
                    <div class="d-flex justify-content-between align-items-center">
                       <strong>{{ __('text.pagInvitaciones') }}</strong>
                        @if (Auth::user()->hasRole('tesorero'))
                            <a href="{{ route('tesorero') }}"><input type="button" class="btn btn-outline-secondary btn-sm" value="{{ __('text.back')}}" /></a>
                        @else
                            <a href="{{ route('secretario') }}"><input type="button" class="btn btn-outline-secondary btn-sm" value="{{ __('text.back')}}" /></a>
                        @endif
                    </div>
                {{-- <div class="card-body"> --}}
                    {{ __('text.pagInvitacionesTxt') }}
                    <br/>
                    <hr>
                    {{--@if(Auth::user()->hasRole('admin', 'junta', 'secretario', 'tesorero'))--}}
                        <table class="table table-striped table-bordered text-center" id="tablaSocios">
                            <thead class="thead-light">
                                <tr>
                                    <th data-priority="3">{{ __('text.numsocio')}}</th>
                                    <th data-priority="2">{{ __('text.nombreSocio')}}</th>
                                    <th data-priority="1">{{ __('text.fecha')}}</th>
                                    <th data-priority="2">{{ __('text.invitado')}}</th>
                                    <th data-priority="1">{{ __('text.colEliminar')}}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>{{ __('text.numsocio')}}</th>
                                    <th>{{ __('text.nombreSocio')}}</th>
                                    <th>{{ __('text.fecha')}}</th>
                                    <th>{{ __('text.invitado')}}</th>
                                    <th>{{ __('text.colEliminar')}}</th>
                            </tfoot>
                        </table>

                    {{-- @else
                        <script>window.location = "{{url('/home')}}";</script>
                        <h1>No tienes permiso para estar aquí</h1>
                    @endif --}}
                </div>
            </div>

            <!-- Modal Gastar Invitacion-->
                <div id="gastarInvitacion_modal" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="container-fluid">
                            <!-- Modal content-->
                            @if (Auth::user()->hasPermissionTo('permiso_editar_socios'))
                                <div class="modal-content col-md-16">
                                    <div class="modal-body">
                                        <label class="label-info">{{ __('text.gestionInvitaciones') }}</label>
                                        <hr>
                                        <form class="form-horizontal" method="POST" action="{{ route('gastarInvitacionSocio') }}">
                                            {{ csrf_field() }}
                                            <div class="form-group row">
                                                <label for="idSocio" class="col-sm-3 col-form-label">{{ __('text.nombreSocio')}}</label>
                                                <div class="col">
                                                    <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="idSocio" id="idSocio">
                                                        @foreach ($usuarios as $usuario)
                                                            <option class="form-control" value="{{$usuario['idSocio']}}" data-subtext="{{$usuario['apellidos']}}" style="width: 100%">{{$usuario['nombre']}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="fechaInvitacion" class="col-sm-3 col-form-label">{{ __('text.fechaInvitacion')}}</label>
                                                <div class="col">
                                                    <input class="form-control" id="fechaInvitacion" name="fechaInvitacion" type="date" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="invitado" class="col-sm-3 col-form-label">{{ __('text.invitado')}}</label>
                                                <div class="col">
                                                  <input type='text' id="invitado" required class="form-control" name="invitado" placeholder="{{__('text.invitadotxt')}}">
                                                </div>
                                            </div>

                                            <hr>

                                        <div class="form-group row justify-content-md-center">
                                            <div class="col col-md-auto">
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
                                            <button type="button" class="btn btn-secondary form-control" data-dismiss="modal">
                                                {{ __('text.btnClose')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <!--FIN Modal Gastar Invitacion-->


        </div>
    </div>
</div>

<script type="text/javascript">
    // Concept: Render select2 fields after all javascript has finished loading
    var initSelect2 = function(){
        // function that will initialize the select2 plugin, to be triggered later
        var renderSelect = function(){
            $('section#formSection select').each(function(){
                $(this).select2({
                    'dropdownCssClass': 'dropdown-hover',
                    'width': '',
                    'minimumResultsForSearch': -1,
                });
            })
        };
        // create select2 HTML elements
        var style = document.createElement('link');
        var script = document.createElement('script');
        style.rel = 'stylesheet';
        style.href = 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css';
        script.type = 'text/javascript';
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.full.min.js';
        // trigger the select2 initialization once the script tag has finished loading
        script.onload = renderSelect;
        // render the style and script tags into the DOM
        document.getElementsByTagName('head')[0].appendChild(style);
        document.getElementsByTagName('head')[0].appendChild(script);
    };
    initSelect2();

    $(document).ready(function() {
        $(".reveal").on('click',function() {
            var $pwd = $(".pwd");
            if ($pwd.attr('type') === 'password') {
                $pwd.attr('type', 'text');
            } else {
                $pwd.attr('type', 'password');
            }
        });
        $('.js-example-basic-single').select2();

        $.ajax({
                url: "{{url('/invitacionesDataTable')}}",
                success : function(data) {
                    var tabla = data.data;//.list.item;
                    $('#tablaSocios').DataTable( {
                        responsive: true,
                        processing: true,
                        colReorder: true,
                        serverSide: true,
                        ajax: '{{url('/invitacionesDataTable')}}',
                        initComplete: function () {
                            this.api().columns(0).every(function () {
                                var column = this;
                                var input = document.createElement("input");
                                input.setAttribute('type', 'text');
                                input.setAttribute('class', 'form-control')
                                $(input).appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
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
                        },
                        lengthMenu: [[10, 25, 50, -1], ["10 filas", "25 filas", "50 filas", "Todos"]],
                        dom: 'Bfrtip',
                        language: {
                            buttons: {
                                copyTitle: 'Copiar al Portapeles',
                                copyKeys: 'Presione <i> ctrl </i> o <i> \ u2318 </i> + <i> C </i> para copiar los datos de la tabla a su portapapeles. <br> <br> Para cancelar, haga clic en este mensaje o presione Esc.',
                                copySuccess: {
                                    _: '%d líneas copiadas',
                                    1: '1 línea copiada'
                                },
                                pageLength: {
                                    _: "Mostrando %d filas",
                                    '-1': "Mostrar todo"
                                }
                            }
                        },
                        data : tabla,
                        columns: [
                            {"data" : "idSocio"},
                            {"data" : "nombreSocio"},
                            {"data" : "fecha"},
                            {"data" : "invitado"},
                            {
                                "className": 'details-control',
                                "orderable": false,
                                "data": null,
                                "render": function (data, type, row) {
                                    //return '<a class="btn btn-danger" onclick="alert('+ row.offset +')">Deletear</a>'
                                    if(data.caducada == false){
                                        return "<input type=\"button\" class=\"btn btn-outline-secondary btn-sm\" value=\"&#9003;\" disabled  data-toggle=\"tooltip\" data-placement=\"top\" title=\"{{ __('text.noSuprInvit')}}\" />";
                                    }else{
                                        return "<a href=\"{{url('/eliminarInvitacion/')}}/"+ row.id +"\"><input type=\"button\" class=\"btn btn-outline-danger btn-sm\" value=\"&#9003;\"/></a>";
                                    }
                                }
                            },
                        ],
                        buttons: [
                            {
                                text: '{{ __('text.btnInvitacionDeSocio')}}',
                                className: "btn btn-sm btn-success",
                                action: function ( e, dt, node, config ) {
                                    $('#gastarInvitacion_modal').modal('toggle');
                                }
                            },
                            @include('layouts.datatableComun')

                    });
                },
            });
    });
</script>

@endsection
