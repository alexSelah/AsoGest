@extends('movil.layout')

@section('content')

<div class="container">

    {{--Grupos de Chat--}}
        <label class="label-info">
            {{__('text.txtogruposchat')}}
        </label>
          <hr>
        <div class="accordion" id="accordionChats">
            @foreach($grupochat as $keych=>$grc)
              <div class="card">
                <div class="card-header" id="{{$keych}}">
                  <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse{{$keych}}" aria-expanded="true" aria-controls="collapseOne">
                          {{$grc['nombre']}}
                    </button>
                  </h2>
                </div>

                <div id="collapse{{$keych}}" class="collapse" aria-labelledby="heading{{$keych}}" data-parent="#accordionChats">
                  <div class="card-body">
                      {{$grc['desc']}}
                      <br><br>
                      <a href="{{$grc['URL']}}" target="_blank">
                        {!! QrCode::size(200)->generate($grc['URL']) !!}
                      </a>
                  </div>
                </div>
              </div>
            @endforeach
        </div>
        <br>
    {{-- FIN DEL PANEL Grupos de Chat--}}

    <label class="label-info">{{ __('text.documentos') }}</label>
	<table class="table table-striped table-bordered text-center display compact nowrap" id="tablaDocumentos">
        <thead class="thead-light">
            <tr>
                <th data-priority="1">{{ __('text.fecha')}}</th>
                <th data-priority="3">{{ __('text.tipo')}}</th>
                <th data-priority="2">{{ __('text.colDescripcion')}}</th>
                <th data-priority="1">{{ __('text.colDescargar')}}</th>
            </tr>
        </thead>
    </table>


</div>

<script type="text/javascript">
	 $(document).ready(function() {
        $.ajax({
                url: "{{url('/documentosDataTable')}}",
                success : function(data) {
                    var tabla = data.data;//.list.item;
                    $('#tablaDocumentos').DataTable( {
                        responsive: true,
                        processing: true,
                        colReorder: false,
                        serverSide: true,
                        ajax: '{{url('/documentosDataTable')}}',
                        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                        //dom: 'Bfrtip',
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
                            {"data" : "fecha"},
                            {"data" : "tipo"},
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
                        ],
                        buttons: {
                            button: {
                                extend: 'pageLength',
                                className: "btn-sm btn-info",
                            },
                        }
                    });
                },
            });
    });
</script>

<noscript>Please, enable JavaScript to see this page. Por favor, habilita JavaScript para ver esta página.</noscript>
@endsection
