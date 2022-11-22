@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <strong>{{ __('text.listadoDeDocumentos') }}</strong>
                        <a href="{{ route('home') }}"><input type="button" class="btn btn-outline-secondary btn-sm" value="{{ __('text.back')}}" /></a>
                    </div>
                    <div class="card-body">

                        <table class="table table-striped table-bordered text-center display compact nowrap" id="tablaDocumentos"  style="min-width: 100%;">
                            <thead class="thead-light">
                                <tr>
                                    <th data-priority="1">{{ __('text.fecha')}}</th>
                                    <th data-priority="2">{{ __('text.tipo')}}</th>
                                    <th data-priority="1">{{ __('text.nombre')}}</th>
                                    <th data-priority="3">{{ __('text.colDescripcion')}}</th>
                                    <th data-priority="1">{{ __('text.colDescargar')}}</th>
                                    @if (Auth::user()->hasPermissionTo('permiso_secretaria'))
                                        <th data-priority="1">{{ __('text.colEliminar')}}</th>
                                    @else
                                        <th data-priority="5" style="display:none !important;"></th>
                                    @endif
                                </tr>
                            </thead>
                            <tfoot class="thead-light">
                                <tr>
                                    <th>{{ __('text.fecha')}}</th>
                                    <th>{{ __('text.tipo')}}</th>
                                    <th>{{ __('text.nombre')}}</th>
                                    <th>{{ __('text.colDescripcion')}}</th>
                                    <th style="display:none;">{{ __('text.colDescargar')}}</th>
                                    @if (Auth::user()->hasPermissionTo('permiso_secretaria'))
                                        <th>{{ __('text.colEliminar')}}</th>
                                    @else
                                        <th style="display:none !important;"></th>
                                    @endif
                                </tr>
                            </tfoot>
                        </table>


<!-- Modal Nuevo Informe-->
<div id="nuevoDocumento_modal" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg">
    <div class="container-fluid">
        <!-- Modal content-->
        <div class="modal-content col-md-12">
            <div class="modal-body">
                <p class="">{{ __('text.nuevoDocumento') }}</p>
                <hr>
                @if (Auth::user()->hasPermissionTo('permiso_secretaria'))
                <form action="{{ route('subeDocumento') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                        <div class="form-group row">
                            <label for="selectTipo" class="col-sm-2 col-form-label">{{ __('text.tipoDeDocumento')}}</label>
                            <div class="col-sm-10">
                                <select class="form-control select" id="selectTipo" name="selectTipo" data-live-search="true" onclick="colapsarDocumento()" onchange="colapsarDocumento()">
                                    <option disabled="disabled" selected>{{ __('text.ElijeTipoDoc')}}</option>
                                    <option value="opcionCustom" data-toggle="collapse" data-target="#tipoNuevoCollapse" onclick="colapsarDocumento()">{{__('text.addnewTipo')}} &nbsp; &#x2B;</option>
                                    <option disabled="disabled">------------------</option>
                                    @foreach ($tipos as $tipo)
                                        <option value="{{$tipo}}">{{$tipo}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="tipoNuevoCollapse" class="form-group row collapse">
                           <label for="inputTipoNuevo" class="col-sm-2 col-form-label">{{ __('text.colNuevoTipo')}}</label>
                            <div class="col-sm-10">
                              <input class="form-control" type="text" name="inputTipoNuevo" id="inputTipoNuevo" placeholder="{{ __('text.newTipoDesc')}}" />
                            </div>
                        </div>


                        <div class="form-group row">
                           <label for="inputDesc" class="col-sm-2 col-form-label">{{ __('text.colDescripcion')}}</label>
                            <div class="col-sm-10">
                              <input class="form-control" type="text" name="inputDesc" id="informeDescripción" placeholder="{{ __('text.newDocumentoDesc')}}" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputNombre" class="col-sm-2 col-form-label">{{ __('text.colNombre')}}</label>
                            <div class="col-sm-10">
                              <input class="form-control" type="text" name="inputNombre" id="inputNombre" placeholder="{{ __('text.newDocumentoNombre')}}" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="file" class="col-sm-2 col-form-label">
                                {{ __('text.archivo')}}
                                <br>
                                {{__("text.mejorPDF")}}</label>
                            <div class="col-sm-10">
                              <input id="file" type="file" class="form-control @error('file') is-invalid @enderror" name="file" value="{{ old('file') }}">
                            </div>
                        </div>
                    <br/>
                    <hr>
                    <div class="form-group row justify-content-md-center">
                        <div class="col col-lg-2">
                            <button class="btn btn-success form-control" type="submit">
                                {{ __('text.btnGuardar')}}
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
<!--FIN Modal Nuevo Informe-->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function colapsarDocumento() {
        var sel = document.getElementById('selectTipo');
        if(sel!=null){
            if(sel.selectedIndex == 1){
                $("#tipoNuevoCollapse").collapse('show');
            }
            else{
                $("#tipoNuevoCollapse").collapse('hide');
            }
        }
    }


    function addSelectItem(t,ev)
    {
       ev.stopPropagation();

       var bs = $(t).closest('.bootstrap-select')
       var txt=bs.find('.bss-input').val().replace(/[|]/g,"");
       var txt=$(t).prev().val().replace(/[|]/g,"");
       if ($.trim(txt)=='') return;

       // Changed from previous version to cater to new
       // layout used by bootstrap-select.
       var p=bs.find('select');
       var o=$('option', p).eq(-2);
       o.before( $("<option>", { "selected": true, "text": txt}) );
       p.selectpicker('refresh');
    }

    function addSelectInpKeyPress(t,ev)
    {
       ev.stopPropagation();

       // do not allow pipe character
       if (ev.which==124) ev.preventDefault();

       // enter character adds the option
       if (ev.which==13)
       {
          ev.preventDefault();
          addSelectItem($(t).next(),ev);
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
        colapsarDocumento();
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
                url: "{{url('/documentosDataTable')}}",
                success : function(data) {
                    var tabla = data.data;//.list.item;
                    $('#tablaDocumentos').DataTable( {
                        responsive: true,
                        processing: true,
                        colReorder: true,
                        serverSide: true,
                        ajax: '{{url('/documentosDataTable')}}',
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
                        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                        dom: 'Bfrtip',
                        language: {
                            buttons: {
                                copyTitle: 'Copiar al Portapeles',
                                copyKeys: 'Presione <i> ctrl </i> o <i> \ u2318 </i> + <i> C </i> para copiar los datos de la tabla a su portapapeles. <br> <br> Para cancelar, haga clic en este mensaje o presione Esc.',
                                copySuccess: {
                                    _: '%d líneas copiadas',
                                    1: '1 línea copiada'
                                }
                            }
                        },
                        data : tabla,
                        columns: [
                            {"data" : "fecha"},
                            {"data" : "tipo"},
                            {"data" : "nombre"},
                            {"data" : "descripcion"},
                            {
                                "className": 'details-control',
                                "orderable": false,
                                "data": null,
                                "searchable": false,
                                "render": function (data, type, row) {
                                    //return '<a class="btn btn-danger" onclick="alert('+ row.offset +')">Deletear</a>'
                                    return "<a href=\"{{url('/descargaDocumento/')}}/"+ row.id +"\"><input id=\"desc/"+row.id+"\" type=\"button\" class=\"btn btn-success\" value=\"&#8681\" /></a>";
                                }
                            },
                            {
                                "className": 'details-control',
                                "orderable": false,
                                "data": null,
                                "searchable": false,
                                "render": function (data, type, row) {
                                    //return '<a class="btn btn-danger" onclick="alert('+ row.offset +')">Deletear</a>'
                                    return "<a href=\"{{url('/eliminaDocumento/')}}/"+ row.id +"\"><input id=\"elim/"+row.id+"\" @if (Auth::user()->hasPermissionTo('permiso_secretaria')) @else style=\"display:none\" @endif type=\"button\" class=\"btn btn-danger\" value=\"&#10007\" onclick=\"return confirm('{{ __('text.QuestionSuredelete')}}')\"/></a>";
                                }
                            },
                        ],

                        buttons: [
                            {
                                text: '{{ __('text.nuevoDocumento')}}',
                                className: "btn btn-sm btn-success",
                                action: function ( e, dt, node, config ) {
                                    $('#nuevoDocumento_modal').modal('toggle');
                                }
                            },
                            @include('layouts.datatableComun')

                    });
                },
            });
    });
</script>

<noscript>Please, enable JavaScript to see this page. Por favor, habilita JavaScript para ver esta página.</noscript>
@endsection

