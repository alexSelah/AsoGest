@extends('layouts.app')

@section('content')

<style type="text/css">
    .rojo {
      background-color: #FAD0C7 !important;
    }
    .azul {
      background-color: #D4E9FA !important;
    }
    .verde {
      background-color: #D4FADB !important;
    }
    .normal{
      /*background-color: #D4FADB !important;*/
    }
</style>

<div class="container-fluid">
    <br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>{{ __('text.estadoDeCuentas') }}</strong>
                        <a href="{{ route('tesorero') }}"><input type="button" class="btn btn-outline-secondary btn-sm" value="{{ __('text.back')}}" /></a>
                    </div>
                {{-- <div class="card-body"> --}}
                    <div class="row">
                      <div class="button" data-toggle="modal" data-target="#apunteRapido_modal">
                          <a class="nav-link" data-toggle="modal" data-target="#apunteRapido_modal" href="#">
                            &#128394;&nbsp;{{ __('text.apunteRapido') }}
                          </a>
                      </div>
                      <div class="button" data-toggle="modal" data-target="#importarExcel_modal">
                          <a class="nav-link" href="#">
                            &#128182;&nbsp;{{ __('text.importarExcel') }}
                          </a>
                      </div>
                      <div class="button">
                          <a class="nav-link" href="{{ url('/descargaExcelSocios') }}">
                              &#128317;&nbsp;{{ __('text.descargarPlantilla') }}
                          </a>
                      </div>

                    </div>

                    <hr>
                    @if(Auth::user()->hasAnyPermission('Acceso_total', 'permiso_tesoreria'))
                        <table class="table table-striped table-bordered text-center" id="tablaCuentas"  style="min-width: 100%;">
                            <thead class="thead-light">
                                <tr>
                                    <th data-priority="1" >{{ __('text.numAsiento')}}</th>
                                    <th data-priority="1" >{{ __('text.fechaApunte')}}</th>
                                    <th data-priority="2" >{{ __('text.año')}}</th>
                                    <th data-priority="1" >{{ __('text.tipo')}}</th>
                                    <th data-priority="3" >{{ __('text.conceptoAgrupado')}}</th>
                                    <th data-priority="5" >{{ __('text.detalle')}}</th>
                                    <th data-priority="4" >{{ __('text.vocalia')}}</th>
                                    <th data-priority="3" >{{ __('text.cantidad')}}</th>
                                    <th data-priority="2" >{{ __('text.pagcob')}}</th>
                                    <th data-priority="6" >{{ __('text.notas')}}</th>
                                    <th data-priority="1" >{{ __('text.editar')}}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>{{ __('text.numAsiento')}}</th>
                                    <th>{{ __('text.fechaApunte')}}</th>
                                    <th>{{ __('text.año')}}</th>
                                    <th>{{ __('text.tipo')}}</th>
                                    <th>{{ __('text.conceptoAgrupado')}}</th>
                                    <th>{{ __('text.detalle')}}</th>
                                    <th>{{ __('text.vocalia')}}</th>
                                    <th>{{ __('text.cantidad')}}</th>
                                    <th>{{ __('text.pagcob')}}</th>
                                    <th>{{ __('text.notas')}}</th>
                                    <th>{{ __('text.editar')}}</th>
                                </tr>
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
                                    <a href="{{ url()->previous() }}"><input type="button" class="btn btn-outline-danger" value="{{ __('text.back')}}" /></a>
                                </div>
                            </div>
                        </div>
                    @endif
                {{-- </div> --}}
            </div>
        </div>
    </div>
</div>

@include('tesorero.modales')

<script type="text/javascript">
    function colapsarSocios(argument) {
        var sel = document.getElementById('conceptoAgrupado');
        if(sel!=null){
            var opt = sel.options[sel.selectedIndex];
            if(opt.text == "Ingreso cuotas"){
                $('.collapse').collapse('show');
                document.getElementById("divTipo").className = "col-3";
                document.getElementById("divCA").className = "col-5";
            }
            else{
                $('.collapse').collapse('hide');
                document.getElementById("divTipo").className = "col-4";
                document.getElementById("divCA").className = "col-8";
            }
        }
    }

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
        colapsarSocios();

        $.ajax({
                url: "{{url('/cuentas/null/null')}}",
                success : function(data) {
                    var tabla = data.data;//.list.item;
                    $('#tablaCuentas').DataTable( {
                        responsive: true,
                        processing: true,
                        colReorder: false,
                        serverSide: true,
                        "order": [[ 0, "desc" ]],
                        ajax: '{{url('/cuentas/null/null')}}',
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
                            this.api().columns(9).every(function () {
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
                        data : tabla,
                        columns: [
                            {
                                "data" : "id",
                                "visible": true,
                            },
                            {
                                "data" : "fechaApunte",
                                "visible": true,
                            },
                            {
                                "data" : "año",
                                "visible": false,
                            },
                            {
                                "data" : "tipo",
                                "visible": true,
                            },
                            {
                                "data" : "conceptoAgrupado",
                                "visible": false,
                            },
                            {
                                "data" : "detalle",
                                "visible": true,
                            },
                            {
                                "data" : "vocalia",
                                "visible": false,
                            },
                            {
                                "data" : "cantidad",
                                "visible": true,
                            },
                            {
                                "data" : "pagcob",
                                "visible": false,
                            },
                            {
                                "data" : "notas",
                                "visible": false,
                            },
                            {
                                "className": 'details-control',
                                "orderable": false,
                                "visible": true,
                                "data": null,
                                "render": function (data, type, row) {
                                    return "<a href=\"{{url('/editarApunte/')}}/"+ row.id +"\"><input type=\"button\" class=\"btn btn-outline-success btn-sm\" value=\"&#128393;\"/></a>";
                                }
                            },
                        ],
                        buttons: [
                            @include('layouts.datatableComun')

                        "createdRow": function( row, data, dataIndex){
                            if( data['tipo'] ==  'Gasto'){
                                $(row).addClass('rojo');
                            }
                            else{
                                if(data['conceptoAgrupado'] ==  'Ingreso cuotas'){
                                    $(row).addClass('azul');
                                }
                                else{
                                    if(data['tipo'] ==  'Ingreso'){
                                        $(row).addClass('verde');
                                    }else{
                                        $(row).addClass('normal');
                                    }
                                }
                            }
                        },
                    });
                },
        });

    }); //Fin de Document Ready
</script>

@endsection
