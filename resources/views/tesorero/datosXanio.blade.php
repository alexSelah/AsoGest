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
                        <strong>{{ __('text.datosXanio') }}</strong>
                        <a href="{{ route('tesorero') }}"><input type="button" class="btn btn-outline-secondary btn-sm" value="{{ __('text.back')}}" /></a>
                    </div>
                    {{ __('text.datosXanioTxt') }}
                <div class="card-body">
                    <div class="form-inline">
                        <div class="form-group mb-2">
                            <label for="static" class="sr-only"></label>
                            <input type="text" readonly class="form-control-plaintext" id="static" value="Selecciona un año">
                        </div>
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="anioSelect" class="sr-only"></label>
                            <input type="text" class="form-control datepicker" id="anioSelect" name="anioSelect" value="{{$year}}">
                        </div>
                        <button type="submit" class="btn btn-primary mb-2" onclick="verFecha()">{{__('text.verAnio')}}</button>
                    </div>
                    <hr>
                        <table class="table table-striped table-bordered text-center" id="tablaCuentas" style="min-width: 100%;">
                            <thead class="thead-light">
                                <tr>
                                    <th data-priority="1" >{{ __('text.numAsiento')}}</th>
                                    <th data-priority="1" >{{ __('text.fechaApunte')}}</th>
                                    <th data-priority="2" >{{ __('text.año')}}</th>
                                    <th data-priority="1" >{{ __('text.tipo')}}</th>
                                    <th data-priority="3" >{{ __('text.conceptoAgrupado')}}</th>
                                    <th data-priority="5" >{{ __('text.detalle')}}</th>
                                    <th data-priority="3" >{{ __('text.vocalia')}}</th>
                                    <th data-priority="4" >{{ __('text.cantidad')}}</th>
                                    <th data-priority="2" >{{ __('text.pagcob')}}</th>
                                    <th data-priority="6" >{{ __('text.notas')}}</th>
                                    @if(Auth::user()->hasAnyRole(['admin','tesorero']))
                                        <th data-priority="1" >{{ __('text.editar')}}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th data-priority="1" >{{ __('text.numAsiento')}}</th>
                                    <th data-priority="1" >{{ __('text.fechaApunte')}}</th>
                                    <th data-priority="2" >{{ __('text.año')}}</th>
                                    <th data-priority="1" >{{ __('text.tipo')}}</th>
                                    <th data-priority="3" >{{ __('text.conceptoAgrupado')}}</th>
                                    <th data-priority="5" >{{ __('text.detalle')}}</th>
                                    <th data-priority="3" >{{ __('text.vocalia')}}</th>
                                    <th data-priority="4" >{{ __('text.cantidad')}}</th>
                                    <th data-priority="2" >{{ __('text.pagcob')}}</th>
                                    <th data-priority="6" >{{ __('text.notas')}}</th>
                                    @if(Auth::user()->hasAnyRole(['admin','tesorero']))
                                        <th data-priority="1" >{{ __('text.editar')}}</th>
                                    @endif
                                </tr>
                            </tfoot>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function verFecha(){
        $year= document.getElementById('anioSelect').value;
        window.location.href = "{{url('datosXanio/')}}"+"/"+$year;
    }

    $("#anioSelect").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years"
    });

    $(document).ready(function() {
        $year = document.getElementById('anioSelect').value;
        $.ajax({
            url: "{{url('/datosXanioDatatable'.'/'.$year)}}",
            success : function(data) {
                var tabla = data.data;//.list.item;
                $('#tablaCuentas').DataTable( {
                    responsive: true,
                    processing: true,
                    colReorder: true,
                    serverSide: true,
                    "order": [[ 1, "desc" ]],
                    ajax: '{{url('/datosXanioDatatable').'/'.$year}}',
                    initComplete: function () {
                        this.api().columns().every(function () {
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
                        {"data" : "fechaApunte"},
                        {"data" : "año"},
                        {"data" : "tipo"},
                        {"data" : "conceptoAgrupado"},
                        {"data" : "detalle"},
                        {"data" : "vocalia"},
                        {"data" : "cantidad"},
                        {"data" : "pagcob"},
                        {"data" : "notas"},
                        @if(Auth::user()->hasAnyRole(['admin','secretario','tesorero']))
                        {
                            "className": 'details-control',
                            "orderable": false,
                            "data": null,
                            "render": function (data, type, row) {
                                return "\<a href=\"{{url('/editarApunte/')}}/"+ data.id +"\"><input type=\"button\" class=\"btn btn-outline-success btn-sm\" value=\"&#128393;\"/></a>";
                            }
                        },
                        @endif
                    ],
                    "createdRow": function( row, data, dataIndex){
                        if( data['tipo'] ==  'Gasto'){
                            $(row).addClass('redRow');
                        }
                        else{
                            if(data['tipo'] ==  'Ingreso'){
                                $(row).addClass('blueRow');
                            }
                            else{
                                $(row).addClass('normalRow');
                            }
                        }
                    },
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
            }
        });

    }); //Fin de Document Ready
</script>

@endsection
