@extends('layouts.app')

@section('content')

<link rel="stylesheet" type="text/css" href="{{ asset('css/summernote.min.css') }}"/>

<div class="container-fluid">
    <br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>{{ __('text.pagCuotas') }}</strong>
                        <a href="{{ route('tesorero') }}"><input type="button" class="btn btn-outline-secondary btn-sm" value="{{ __('text.back')}}" /></a>
                    </div>
                    {{ __('text.pagCuotasTxt') }}
                    <br/>
                    @if($vert == 1)
                        <span class="badge badge-danger">{{ __('text.muestraTodasCuotas') }}</span>
                    @else
                        <span class="badge badge-primary">{{ __('text.muestraSoloHabilitadosCuotas') }}</span>
                    @endif
                <div class="card-body">
                    <hr>
                    @if(Auth::user()->hasAnyPermission('Acceso_total', 'permiso_tesoreria'))
                        <table class="table table-striped table-bordered text-center" id="tablaCuotas" style="min-width: 100%;">
                            <thead class="thead-light">
                                <tr>
                                    <th data-priority="1" >{{ __('text.id')}}</th>
                                    <th data-priority="2" >{{ __('text.numAsiento')}}</th>
                                    <th data-priority="3" >{{ __('text.fechaCuota')}}</th>
                                    <th data-priority="1" >{{ __('text.fechaRenova')}}</th>
                                    <th data-priority="2" >{{ __('text.socio')}}</th>
                                    <th data-priority="2" >{{ __('text.tipoCuota')}}</th>
                                    <th data-priority="2" >{{ __('text.cantidad')}}</th>
                                    <th data-priority="1" >{{ __('text.renovar')}}</th>
                                    <th data-priority="1" >{{ __('text.acciones')}}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                    <th>{{ __('text.id')}}</th>
                                    <th>{{ __('text.numAsiento')}}</th>
                                    <th>{{ __('text.fechaCuota')}}</th>
                                    <th>{{ __('text.fechaRenova')}}</th>
                                    <th>{{ __('text.socio')}}</th>
                                    <th>{{ __('text.tipoCuota')}}</th>
                                    <th>{{ __('text.cantidad')}}</th>
                                    <th>{{ __('text.renovar')}}</th>
                                    <th>{{ __('text.acciones')}}</th>
                            </tfoot>
                        </table>

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
                                    <button type="button" class="btn btn-secondary form-control" data-dismiss="modal">
                                        {{ __('text.btnClose')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('tesorero.modalesCuota')

<!-- SUMMERNOTE EDITOR WYGIWYS -->
<script type="text/javascript" src="{{ asset('js/summernote.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/summernote-es-ES.js') }}"></script>

<script type="text/javascript">
    $(".select").change(function () {
        nuevaCant = $(this).children(':selected').data('cant');
        document.getElementById("cantidadCuota").value = nuevaCant;
    });

    $(document).ready(function() {

        $(document).on('click', '#getCuota', function(e){
            //console.log("EDIT CUOTA");
            e.preventDefault();
            var url = $(this).data('url');
            $('#dynamic-content').html(''); // leave it blank before ajax call
            $('#modal-loader').show();      // load ajax loader
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'html'
            })
            .done(function(data){
                //console.log(data);
                $('#dynamic-content').html('');
                $('#dynamic-content').html(data); // load response
                $('#modal-loader').hide();        // hide ajax loader
            })
            .fail(function(){
                $('#dynamic-content').html('<h1>&#128553;</h1> Algo ha salido mal...');
                $('#modal-loader').hide();
            });
        });

        $(document).on('click', '#getEmail', function(e){
            //console.log($(this).data('url'));
            e.preventDefault();
            var url = $(this).data('url');
            $('#dynamic-content-email').html(''); // leave it blank before ajax call
            $('#modal-loader-email').show();      // load ajax loader
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'html'
            })
            .done(function(data){
                //console.log(data);
                $('#dynamic-content-email').html('');
                $('#dynamic-content-email').html(data); // load response
                $('#modal-loader-email').hide();        // hide ajax loader
            })
            .fail(function(){
                $('#dynamic-content-email').html('<h1>&#128553;</h1> Algo ha salido mal...');
                $('#modal-loader-email').hide();
            });
        });

        $('.datepicker').datepicker({
           todayBtn: 'linked',
           language: 'es',
           autoclose: true,
           todayHighlight: true,
           format: 'd/m/yyyy'
        });

        $.ajax({
                url: "{{url('/cuotasDatatable'.'/'.$vert)}}",
                success : function(data) {
                    var tabla = data.data;//.list.item;
                    $('#tablaCuotas').DataTable( {
                        responsive: true,
                        processing: true,
                        colReorder: true,
                        serverSide: true,
                        "order": [[ 0, "desc" ]],
                        ajax: '{{url('/cuotasDatatable'.'/'.$vert)}}',
                        lengthMenu: [
                            [ 10, 25, 50, -1 ],
                            [ '10 filas', '25 filas', '50 filas', 'Mostrar Todo' ]
                        ],
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
                                    _: "Mostrar %d filas",
                                    '-1': "Mostrar Todo"
                                }
                            }
                        },
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
                            this.api().columns(6).every(function () {
                                var column = this;
                                var input = document.createElement("input");
                                input.setAttribute('type', 'text');
                                input.setAttribute('class', 'form-control')
                                $(input).appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                            this.api().columns(7).every(function () {
                                var column = this;
                                var input = document.createElement("input");
                                input.setAttribute('type', 'text');
                                input.setAttribute('class', 'form-control')
                                $(input).appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                            this.api().columns(8).every(function () {
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
                            {"data" : "id"},
                            {"data" : "idAsiento"},
                            {"data" : "fechaCuota"},
                            {"data" : "fechaRenovacion"},
                            {"data" : "idSocio"},
                            {"data" : "tipoCuota"},
                            {"data" : "cantidad"},
                            {"data" : "renueva"},
                            {
                                "className": 'details-control',
                                "orderable": false,
                                "data": null,
                                "render": function (data, type, row) {
                                    return "<button type=\"button\" data-toggle=\"modal\" data-target=\"#editCuotaModal\" id=\"getCuota\" class=\"btn btn-outline-success btn-sm\" data-url=\"{{url('/editaCuota/')}}/"+ row.id +"\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"{{__('text.editaCuotaHelp') }}\">&#128393;</button>&nbsp;<a href=\"{{url('/eliminaCuota/')}}/"+ row.id +"\"><input id=\"elim/"+row.id+"\" type=\"button\" class=\"btn btn-outline-danger btn-sm\" value=\"&#10007\" onclick=\"return confirm('{{ __('text.QuestionSuredelete')}}')\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"{{__('text.eliminaCuotaHelp') }}\"/></a>&nbsp;<a href=\"{{url('/deshabilitaSocio/')}}/"+ row.id +"\"><input id=\"elim/"+row.id+"\" type=\"button\" class=\"btn btn-outline-info btn-sm\" value=\"&#128472;\" onclick=\"return confirm('{{ __('text.QuestionSuredeshabilita')}}')\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"{{__('text.deshabilitaSocioHelp') }}\"/></a>&nbsp;<button type=\"button\" data-toggle=\"modal\" data-target=\"#enviaEmailCuotaModal\" id=\"getEmail\" class=\"btn btn-outline-dark btn-sm\" data-url=\"{{url('/enviaEmailCuota/')}}/"+ row.id +"\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"{{__('text.enviaEmailHelp') }}\">&#128387;</button>";
                                }
                            },
                        ],
                        buttons: [
                            {
                                text: '{{ $verTodasCuotasONo}}',
                                className: "btn btn-sm btn-warning",
                                action: function ( e, dt, node, config ) {
                                    if({{$vert}} == 1 || {{$vert}} == true){
                                        window.location = "{{url('/cuotas/')}}/false";
                                    }else{
                                        window.location = "{{url('/cuotas/')}}/true";
                                    }

                                }
                            },
                            {
                                text: '{{ __('text.nuevaCuota')}}',
                                className: "btn btn-sm btn-success",
                                action: function ( e, dt, node, config ) {
                                    $('#nuevaCuota_modal').modal('toggle');
                                }
                            },
                            @include('layouts.datatableComun')

                    });
                },
        });

    }); //Fin de Document Ready
</script>

@endsection
