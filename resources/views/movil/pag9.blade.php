@extends('movil.layout')

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

<div class="container">
	<label class="label-info">{{ __('text.estadoCuentas') }}</label>
	<div class="table-responsive">
		<table class="table table-sm table-striped table-bordered display compact nowrap" style="width:100%" id="tablaCuentas">
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
					<th data-priority="1" >{{ __('text.pagcob')}}</th>
					<th data-priority="6" >{{ __('text.notas')}}</th>
				</tr>
			</thead>
		</table>
	 </div>

</div>

<script type="text/javascript">
	 $(document).ready(function() {
        $.ajax({
                url: "{{url('/cuentas').'/null/null'}}",
                success : function(data) {
                    var tabla = data.data;//.list.item;
                    $('#tablaCuentas').DataTable({
                        responsive: true,
                        processing: true,
                        colReorder: true,
                        serverSide: true,
                        "language": {
                            "emptyTable":"No hay registros para mostrar"
                        },
                        "order": [[ 1, "desc" ]],
                        ajax: "{{url('/cuentas').'/null/null'}}",
                        language: {
			                "sProcessing": "Procesando...",
			                "sLengthMenu": "Mostrar _MENU_ registros",
			                "sZeroRecords": "No se encontraron resultados",
			                "sEmptyTable": "Ningún dato disponible en esta tabla",
			                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
			                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
			                "sInfoPostFix": "",
			                "sSearch": "Buscar:",
			                "sUrl": "",
			                "sInfoThousands": ",",
			                "sLoadingRecords": "Cargando...",
			                "oPaginate": {
			                    "sFirst": "Primero",
			                    "sLast": "Último",
			                    "sNext": "Siguiente",
			                    "sPrevious": "Anterior"
			                },
			                "oAria": {
			                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
			                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
			                }
			            },
                        data : tabla,
                        columns: [
                            {"data" : "id", name: 'id'},
                            {"data" : "fechaApunte", name: 'fechaApunte'},
                            {"data" : "año", name: 'año'},
                            {"data" : "tipo", name: 'tipo'},
                            {"data" : "conceptoAgrupado", name: 'conceptoAgrupado'},
                            {"data" : "detalle", name: 'detalle'},
                            {"data" : "vocalia", name: 'vocalia'},
                            {"data" : "cantidad", name: 'cantidad'},
                            {"data" : "pagcob", name: 'pagcob'},
                            {"data" : "notas", name: 'notas'},
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
    });

</script>

<noscript>Please, enable JavaScript to see this page. Por favor, habilita JavaScript para ver esta página.</noscript>
@endsection
