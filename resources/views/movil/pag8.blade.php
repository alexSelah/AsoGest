@extends('movil.layout')

@section('content')

<div class="container">
	<label class="label-info">{{ __('text.estadoCuentas') }}</label>
	<div class="table-responsive">
		<table class="table table-striped table-bordered text-center display no-wrap" id="tablaSocios">
	        <thead class="thead-light">
	            <tr>
	                <th data-priority="1">{{ __('text.numsocio')}}</th>
	                <th data-priority="5">{{ __('text.alta')}}</th>
	                <th data-priority="5">{{ __('text.baja')}}</th>
	                <th data-priority="2">{{ __('text.nombre')}}</th>
	                <th data-priority="4">{{ __('text.1apellido')}}</th>
	                <th data-priority="4">{{ __('text.2apellido')}}</th>
	                <th data-priority="2">{{ __('text.DNI')}}</th>
	                <th data-priority="3">{{ __('text.email')}}</th>
	                <th data-priority="3">{{ __('text.telefono')}}</th>
	                <th data-priority="3">{{ __('text.activo')}}</th>
	                <th data-priority="6">{{ __('text.direccion')}}</th>
	                <th data-priority="6">{{ __('text.localidad')}}</th>
	                <th data-priority="6">{{ __('text.provincia')}}</th>
	                <th data-priority="3">{{ __('text.username')}}</th>
	                <th data-priority="4">{{ __('text.sexo')}}</th>
	                <th data-priority="6">{{ __('text.notas')}}</th>
	                <th data-priority="1">{{ __('text.modificar')}}</th>
	            </tr>
	        </thead>
	    </table>
	</div>
</div>

<!-- Modal Nuevo Socio-->
<div id="nuevoSocio_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="container-fluid">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <h5><strong>{{ __('text.nuevoSocio') }}</strong></h5> <br>
                    <p class="">{{ __('text.nuevoSocioDescipcion') }}</p>
                    <hr>
                     <form action="{{ route('nuevoSocio') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="inputNumSocio">{{ __('text.numsocio')}}</label>
                                <input class="form-control" type="text" name="inputNumSocio" required readonly id="inputNumSocio" value="{{$ultimoNumSocio}}" />
                            </div>
                            <div class="form-group col-12">
                                <label for="inputDNI"><strong>{{ __('text.DNI')}}</strong></label>
                                <input class="form-control" type="text" name="inputDNI" required id="inputDNI" >
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="inputNombre" class="form-label text-md-right"><strong>{{ __('text.nombre')}}</strong></label>
                                <input class="form-control" type="text" name="inputNombre" required id="inputNombre"/>
                            </div>
                            <div class="form-group col-12">
                                <label for="inputUsername" class="form-label text-md-right"><strong>{{ __('text.username')}}</strong></label>
                                <input class="form-control" type="text" name="inputUsername" required id="inputUsername" />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="input1apellido" class="form-label text-md-right">{{ __('text.1apellido')}}</label>
                                <input class="form-control" type="text" name="input1apellido" id="input1apellido"/>
                            </div>
                            <div class="form-group col-12">
                                <label for="input2apellido" class="form-label text-md-right">{{ __('text.2apellido')}}</label>
                                <input class="form-control" type="text" name="input2apellido" id="input2apellido" />
                            </div>
                            <div class="form-group col-12">
                                <label for="inputFnacimiento" class="form-label text-md-right"><strong>{{ __('text.fnacimiento')}}</strong></label>
                                <input class="form-control" type="date"  name="inputFnacimiento" required id="inputFnacimiento"/>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="inputTelefono" class="form-label text-md-right">{{ __('text.telefono')}}</label>
                                <input class="form-control" type="text" name="inputTelefono" id="inputTelefono" />
                            </div>
                            <div class="form-group col-12">
                                <label for="inputEmail" class="form-label text-md-right"><strong>{{ __('text.email')}}</strong></label>
                                <input class="form-control" type="email" name="inputEmail" id="inputEmail"/>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="passwordText" class="form-label text-md-right"><strong>{{ __('text.password')}}</strong></label>
                                <div class="input-group">
                                    <input id="passwordText" required name="passwordText" type="password" class="form-control pwd" placeholder="{{ __('text.nuevaPassword')}}">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default reveal" type="button">&#x1f441;</button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group col-12">
                                <input class="form-check-input" type="checkbox" id="cuotaAnual" name="cuotaAnual" checked>
                                <label class="form-label" for="cuotaAnual">{{ __('text.darAltaCuotaAnual')}}</label>
                            </div>
                        </div>
                        <hr>

                        <div class="form-row justify-content-center">
                            <div class="form-group col-md-2 justify-content-center">
                                <button class="btn btn-success form-control" type="submit">
                                    {{ __('text.btnGuardar')}}
                                </button>
                            </div>
                            <div class="form-group col-md-2  justify-content-center">

                            </div>
                            <div class="form-group col-md-2  justify-content-center">
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
<!--FIN Modal Nuevo Socio-->

<script type="text/javascript">

	$(document).ready(function() {
        $.ajax({
            url: "{{url('/listaSocios')}}",
            success : function(data) {
                var tabla = data.data;//.list.item;
                $('#tablaSocios').DataTable( {
                    responsive: true,
                    processing: true,
                    colReorder: true,
                    serverSide: true,
                    ajax: '{{url('/listaSocios')}}',
                    lengthMenu: [[10, 25, 50, -1], ["10 filas", "25 filas", "50 filas", "Todos"]],
                    "order": [[ 1, "desc" ]],
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
                        {"data" : "numSocio"},
                        {"data" : "alta"},
                        {"data" : "baja"},
                        {"data" : "nombre"},
                        {"data" : "primerApellido"},
                        {"data" : "segundoApellido"},
                        {"data" : "DNI"},
                        {"data" : "email"},
                        {"data" : "telefono"},
                        {"data" : "habilitado"},
                        {"data" : "direccion"},
                        {"data" : "localidad"},
                        {"data" : "provincia"},
                        {"data" : "username"},
                        {"data" : "sexo"},
                        {"data" : "notas"},
                        {
                            "className": 'details-control',
                            "orderable": false,
                            "data": null,
                            "render": function (data, type, row) {
                                //return '<a class="btn btn-danger" onclick="alert('+ row.offset +')">Deletear</a>'
                                return "<a href=\"{{url('/pag1/')}}/"+ row.id +"\"><input type=\"button\" class=\"btn btn-outline-info btn-sm\" value=\"&#129497;\"/></a>";
                            }
                        },
                    ],
                    buttons: [
                        {
                            extend: 'pageLength',
                            className: "btn-sm btn-info",
                        },
                        {
                            text: '{{ __('text.nuevoSocio')}}',
                            className: "btn btn-sm btn-success",
                            action: function ( e, dt, node, config ) {
                                $('#nuevoSocio_modal').modal('toggle');
                            }
                        },
                    ],  //Finde Buttons
                });
            },
        });
    });

</script>

<noscript>Please, enable JavaScript to see this page. Por favor, habilita JavaScript para ver esta página.</noscript>
@endsection
