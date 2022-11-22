@extends('layouts.app')

@section('content')

<link rel="stylesheet" type="text/css" href="{{ asset('css/summernote.min.css') }}"/>

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

<body>

<nav class="navbar navbar-light bg-primary">
  <a class="navbar-brand" style="color: white">&nbsp;{{__('text.Tesoreria')}}</a>
  <form class="form-inline">
    <label for="daterange" class="col-form-label" style="color: white">{{__('text.rangoFechas')}}&nbsp; <small>{{__('text.enIngles')}}:&nbsp;</small></label>
    <input type="text" class="form-control" id="daterange" name="daterange" style="width:250px; text-align: center;"/>
    <span style="margin-left: 15px !important">
    	<a href="{{ route('home') }}"><input type="button" class="btn btn-outline-dark btn-sm" value="{{ __('text.back')}}" /></a>
    </span>
  </form>
</nav>



<div class="container-fluid">
  @if(Auth::user()->hasRole(['admin','tesorero']))
	  <div class="row">
		@include('tesorero.nav')

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">

		  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
			<h1 class="h2">{{__('text.dashboard')}}</h1>
			<div class="btn-toolbar mb-2 mb-md-0">
			   <div class="dropdown no-arrow">
					<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						&#128198; &nbsp;{{__('text.fechasPredef')}}
					</a>
					<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
					  <div class="dropdown-header">{{__('text.elijeFecha')}}</div>
					  <a class="dropdown-item" href="{{url('/tesoreroFechas/')."/".$dia1mes."/".$hoy}}">{{__('text.ultimoMes')}}</a>
					  <a class="dropdown-item" href="{{url('/tesoreroFechas/')."/".$dia1mesAnt."/".$dia31mesAnt}}">{{__('text.mesAnterior')}}</a>
					  <a class="dropdown-item" href="{{url('/tesoreroFechas/')."/".$dia1ano."/".$hoy}}">{{__('text.anoPresente')}}</a>
					  <a class="dropdown-item" href="{{url('/tesoreroFechas/')."/".$dia1eneroAnoAnt."/".$dia31dicAnoAnt}}">{{__('text.anoPasado')}}</a>
					  <div class="dropdown-divider"></div>
					  <a class="dropdown-item" href="{{url('/tesoreroFechas/')."/2017-07-22/".$hoy}}">{{__('text.desdeElInicio')}}</a>
					</div>
				  </div>
			</div>
		  </div>

		  <!-- Content Row -->
			  <div class="row">
				<!-- INGRESADO -->
				<div class="col-xl-3 col-md-6 mb-4">
				  <div class="card border-left-success shadow h-100 py-2">
					<div class="card-body">
					  <div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-success text-uppercase mb-1" data-toggle="tooltip" data-placement="top" title="{{__('text.totalDeIngresostxt') }}">
							{{ __('text.totalDeIngresos') }}
							</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">{{$ingresos}} {{__('text.simbDin')}}</div>
						</div>
						<div class="col-auto">
						  <h2>&#128181;</h2>
						</div>
					  </div>
					</div>
				  </div>
				</div>
				<!-- GASTADO -->
				<div class="col-xl-3 col-md-6 mb-4">
				  <div class="card border-left-danger shadow h-100 py-2">
					<div class="card-body">
					  <div class="row no-gutters align-items-center">
						<div class="col mr-2">
						  <div class="text-xs font-weight-bold text-danger text-uppercase mb-1" data-toggle="tooltip" data-placement="top" title="{{__('text.totaldeGastostxt')}}">
							{{ __('text.totaldeGastos')}}
						 </div>
						  <div class="h5 mb-0 font-weight-bold text-gray-800"> {{$gastos}} {{__('text.simbDin')}}</div>
						</div>
						<div class="col-auto">
						  <h2>&#128184;</h2>
						</div>
					  </div>
					</div>
				  </div>
				</div>
				<!-- INGRESO POR CUOTAS ENTRE ESTAS FECHAS -->
				<div class="col-xl-3 col-md-6 mb-4">
				  <div class="card border-left-dark shadow h-100 py-2">
					<div class="card-body">
					  <div class="row no-gutters align-items-center">
						<div class="col mr-2">
						  <div class="text-xs font-weight-bold text-fark text-uppercase mb-1" data-toggle="tooltip" data-placement="top" title="{{ __('text.remanenteAsociacionTxt')}}"><strong>{{ __('text.remanenteAsociacion')}}</strong></div>
						  @if ($total>0)
                            <div class="h5 mb-0 font-weight-bold text-gray-800" style="color: blue">{{$total}} {{__('text.simbDin')}}</div>
                        @elseif($total<0)
                          <div class="h5 mb-0 font-weight-bold text-gray-800" style="color: red">{{$total}} {{__('text.simbDin')}}</div>
                        @else
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$total}} {{__('text.simbDin')}}</div>
                        @endif
						</div>
						<div class="col-auto">
						  <h2>&#128178;</h2>
						</div>
					  </div>
					</div>
				  </div>
				</div>
              <!-- CUOTAS ATRASADAS DE SOCIOS -->
				<div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                      <div class="card-body">
                        <div class="row no-gutters align-items-center">
                          <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1"  data-toggle="tooltip" data-placement="top" title="{{ __('text.cuotasCaducastxt') }}">{{ __('text.cuotasCaducas') }}</div>
                            <div class="row no-gutters align-items-center">
                              <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{$sociosCuotasAtrasadas}}</div>
                              </div>
                              <div class="col">
                                <div class="progress progress-sm mr-2">
                                  <div class="progress-bar bg-info" role="progressbar" style="width: {{$porctCuotasAtrasadas}}%" aria-valuenow="{{$sociosCuotasAtrasadas}}" aria-valuemin="0" aria-valuemax="{{$sociosActivos}}" data-toggle="tooltip" data-placement="top" title="Hay que renovar {{$sociosCuotasAtrasadas}} cuotas de {{$sociosActivos}} socios"></div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-auto">
                            <h2>&#128406;</h2>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

			<div class="row">
				<!-- CHART LINEAS -->
				<div class="col-xl-8 col-lg-7">
				  <div class="card shadow mb-4">
					<!-- Card Header - Dropdown -->
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					  <h6 class="m-0 font-weight-bold text-primary">{{__('text.graficoEsteaAno')}}</h6>
					</div>
					<!-- Card Body -->
					<div class="card-body">
					  <div class="chart-area">
						<canvas class="my-4" id="myChart" ></canvas>
					  </div>
					</div>
				  </div>
				</div>
				<!-- CHART REDONDO -->
				<div class="col-xl-4 col-lg-5">
				  <div class="card shadow mb-4">
					<!-- Card Header - Dropdown -->
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					  <h6 class="m-0 font-weight-bold text-primary">{{__('text.estadoSocios')}}</h6>
					  <div class="dropdown no-arrow">
						<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            &#128101;
						</a>
						<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
						  <div class="dropdown-header">{{__('text.opciones')}}:</div>
						  <a class="dropdown-item" href="{{route('secretario')}}">{{__('text.estadoSociosMenu')}}</a>
						  <div class="dropdown-divider"></div>
						  <a class="dropdown-item" href="{{ url('/cuotas/null') }}">{{__('text.irCuotas')}}</a>
						</div>
					  </div>
					</div>
					<!-- Card Body -->
					<div class="card-body">
					  <div class="chart-pie pt-4 pb-2">
						<canvas id="myPieChart"></canvas>
					  </div>
					</div>
					<div class="card-foot">
					  <p style="text-align: center"><strong>{{__('text.totalSocios')}}: {{$sociosActivos+$sociosNoActivos}}</strong> &#8669;{{__('text.activos')}}: {{$sociosActivos}} {{__('text.baja')}} {{$sociosNoActivos}} </p>
					</div>
				  </div>
				</div>
			</div>

		  <h3>{{__('text.ultimosGastos')}} {{\Helper::dimeFechaCarbon($fecha1,5,'-')}} a {{\Helper::dimeFechaCarbon($fecha2,5,'-')}}</h3>
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
		</main>
	  </div>
	@else
		<div class="card">
            <div class="row justify-content-md-center" style="margin-left: 10px; margin-right: 10px; margin-top: 10px">
            	<div class="alert alert-warning" role="alert">
				   {{__('text.avisoNoTesr')}}
				</div>
			</div>
			</div>
			<div class="row justify-content-md-center" style="margin-left: 10px; margin-right: 10px; margin-top: 10px">
				<a id="btnGraf" class="btn btn-info" role="button" data-toggle="collapse" href="#collapseEX1" aria-expanded="false" aria-controls="collapseEX1" onclick="colapsarGrafSoc()">
					{{__('text.tesVerGraf')}}
				</a>
			</div>
			<div class="row justify-content-md-center" style="margin-left: 10px; margin-right: 10px; margin-top: 10px">
				<div class="collapse" id="collapseEX1">
					<div class="row">
						<!-- CHART LINEAS -->
						<div class="col-xl-8 col-lg-7">
						  <div class="card shadow mb-4">
							<!-- Card Header - Dropdown -->
							<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							  <h6 class="m-0 font-weight-bold text-primary">{{__('text.graficoEsteaAno')}}</h6>
							</div>
							<!-- Card Body -->
							<div class="card-body">
							  <div class="chart-area">
								<canvas class="my-4" id="myChart" ></canvas>
							  </div>
							</div>
						  </div>
						</div>
						<!-- CHART REDONDO -->
						<div class="col-xl-4 col-lg-5">
						  <div class="card shadow mb-4">
							<!-- Card Header - Dropdown -->
							<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							  <h6 class="m-0 font-weight-bold text-primary">{{__('text.estadoSocios')}}</h6>
							</div>
							<!-- Card Body -->
							<div class="card-body">
							  <div class="chart-pie pt-4 pb-2">
								<canvas id="myPieChart"></canvas>
							  </div>
							</div>
							<div class="card-foot">
							  <p style="text-align: center"><strong>{{__('text.totalSocios')}}: {{$sociosActivos+$sociosNoActivos}}</strong> &#8669;{{__('text.activos')}}: {{$sociosActivos}} {{__('text.baja')}} {{$sociosNoActivos}} </p>
							</div>
						  </div>
						</div>
					</div>
				</div>
			</div>
			<div class="row justify-content-md-center" style="margin-left: 10px; margin-right: 10px; margin-top: 10px">
                <h3>{{__('text.ultimosGastos')}} {{\Helper::dimeFechaCarbon($fecha1,5,'-')}} a {{\Helper::dimeFechaCarbon($fecha2,5,'-')}}</h3>
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
        </div>
	@endif


</div>

@include('tesorero.modales')


<!-- SUMMERNOTE EDITOR WYGIWYS -->
<script type="text/javascript" src="{{ asset('js/summernote.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/summernote-es-ES.js') }}"></script>


<script type="text/javascript">
    function colapsarGrafSoc() {
    	var sel = document.getElementById('btnGraf');
        if(sel!=null){
        	if($('#collapseEX1').is('.collapse:not(.show)')) {
			    sel.firstChild.data = '{{__('text.tesOcultGraf')}}';
                $("#collapseEX1").collapse('show');
			}else{
				sel.firstChild.data = '{{__('text.tesVerGraf')}}';
                $("#collapseEX1").collapse('hide');
			}
        }
    }

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

  $(function() {
    $('input[name="daterange"]').daterangepicker({
            ranges: {
                'Hoy': [moment(), moment()],
                'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
                'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
                'Último mes': [moment().startOf('month'), moment().endOf('month')],
                'Mes anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Desde el inicio de los tiempos': [moment('20170722', 'YYYYMMDD'), moment()]
            },
            "locale": {
                "dateFormat": "MM/DD/YYYY",
                "headerDateFormat":"MM/DD/YYYY",
                "separator": " - ",
                "applyLabel": "Aplicar",
                "cancelLabel": "Cancelar",
                "fromLabel": "Desde",
                "toLabel": "Hasta",
                "customRangeLabel": "Personalizar",

                "weekLabel": "S",
                "daysOfWeek": [
                    "Do",
                    "Lu",
                    "Ma",
                    "Mi",
                    "Ju",
                    "Vi",
                    "Sa"
                ],
                "monthNames": [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre"
                ],
                "firstDay": 1
            },
            showDropdowns: true,
            "startDate": "{{$fecha1->format('m/d/Y')}}",
            "endDate": "{{$fecha2->format('m/d/Y')}}",
        }, function(start, end, label) {
            //console.log('Start: '+start+"  End: "+end+ "   Label: " +label);
            //document.write('Start: '+start+"  End: "+end);
            var updateDate = "{{url('/tesoreroFechas/')}}/" + start.format('YYYY-MM-DD') + "/" + end.format('YYYY-MM-DD');
            window.location.href = updateDate;
        });
  });

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

    function cuotasSociosAplazado() {
        var checkbox = document.getElementById("checkTodosSocios");
        if(checkbox != null){
	        var state = checkbox.checked;
	        if(state){
	            $("#aplazarTodosSocios").show(400);
	        	$("#aplazarAlgunosSocios").hide(400);
	        }
	        else{
	            $("#aplazarTodosSocios").hide(400);
	        	$("#aplazarAlgunosSocios").show(400);
	        }
	    }
    }

    function colapsardiasAplazamiento() {
    	var sel = document.getElementById('inputMesesAplazamiento');
        if(sel!=null){
            var opt = sel.options[sel.selectedIndex];
            if(opt.text != "Introducir días manualmente"){
                $("#diasSueltos").collapse('hide');
            }
            else{
               $("#diasSueltos").collapse('show');
            }
        }
    }

    $(document).ready(function() {
    	colapsarSocios();
    	cuotasSociosAplazado();
    	colapsardiasAplazamiento();
    	$('#summernote').summernote({
    		lang: 'es-ES',
			placeholder: '',
			tabsize: 2,
			height: 220,
			toolbar: [
			  ['style', ['style']],
			  ['font', ['bold', 'underline', 'italic','strikethrough', 'clear']],
			  ['color', ['color']],
			  ['para', ['ul', 'ol', 'paragraph']],
			  ['table', ['table']],
			  ['insert', ['link', 'picture']],
			  ['view', ['codeview', 'help']]
			  // ['view', ['fullscreen', 'codeview', 'help']]
			]
		});


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
                url: "{{url('/cuentas').'/'.$fecha1->format('Y-m-d').'/'.$fecha2->format('Y-m-d')}}",
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
                        ajax: "{{url('/cuentas').'/'.$fecha1->format('Y-m-d').'/'.$fecha2->format('Y-m-d')}}",
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

        colapsarGrafSoc();
    });

    //CHART LINEAS
    var ctx = document.getElementById("myChart");
    var myChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: [@foreach ($datos as $dato) "{{$dato['fecha']}}", @endforeach],
      datasets: [{
        label: "{{__('text.ingresos')}}",
        data: [@foreach ($datos as $dato) {{$dato['ingresos']}}, @endforeach],
        lineTension: 0,
        backgroundColor: 'transparent',
        borderColor: '#7fff00',
        borderWidth: 4,
        pointBackgroundColor: '#001fff'
      },{
        label: "{{__('text.gastos')}}",
        data: [@foreach ($datos as $dato) {{$dato['gastos']}}, @endforeach],
        lineTension: 0,
        backgroundColor: 'transparent',
        borderColor: '#ff0000',
        borderWidth: 4,
        pointBackgroundColor: '#ff7c00'
      }

      ]
    },
    options: {
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true
          }
        }]
      },
      legend: {
        display: true,
      }
    }
    });

    // CHART REDONDO
    var ctxR = document.getElementById("myPieChart");
    var myPieChart = new Chart(ctxR, {
      type: 'pie',
      data: {
        labels: ["Socios Activos", "Socios de Baja"],
        datasets: [{
          data: ["{{$sociosActivos}}", "{{$sociosNoActivos}}"],
          backgroundColor: ['#2dda0f', '#1737e4', '#696662']
        }],
      },
      options: {
        maintainAspectRatio: true,
        tooltips: {
          backgroundColor: "rgb(255,255,255)",
          bodyFontColor: "#858796",
          borderColor: '#dddfeb',
          borderWidth: 1,
          xPadding: 15,
          yPadding: 15,
          displayColors: false,
          caretPadding: 10,
        },
        legend: {
          display: true
        },
      },
    });
</script>

</body>



@endsection
