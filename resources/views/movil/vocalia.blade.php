@extends('movil.layout')

@section('content')

<style type="text/css">
	.row-striped:nth-of-type(odd){
	  background-color: #efefef;
	  border-left: 4px #000000 solid;
	}

	.row-striped:nth-of-type(even){
	  background-color: #ffffff;
	  border-left: 4px #efefef solid;
	}

	.row-striped {
	    padding: 15px 0;
	}
	body{background-color:#fff;}
	.card-header, .card-link,.card-link:hover{text-align:left;color:blue;}
	.card-body{padding:10px 10px;}
	.card{width:auto;}
	p{position:absolute;top:5px;right:20px;font-size:20px;color:blue;
	-webkit-animation: minus 0.5s;}

	@keyframes minus {
	    from {
	        transform:rotate(0deg);
	    }
	    to {
	        transform:rotate(360deg);
	    }
	}
	[aria-expanded="false"] > .expanded, [aria-expanded="true"] > .collapsed {display: none;}
</style>

<div class="container">

	<div id="accordion">
{{--INFORMACIÓN DE LA VOCALÍA--}}
		<div class="card">
			<div class="card-header">
				<a class="card-link" data-toggle="collapse"  href="#menuone" aria-expanded="true" aria-controls="menuone">{{__('text.informsobreVocal')}}<span class="collapsed"><p><b>></b></p></span>
				<span class="expanded"><p><b><</b></p></span></a>
			</div>
			<div id="menuone" class="collapse show">
				<div class="card-body">
	                <h4 style="color: green;"><strong>{{$vocal['nombre']}}</strong></h4>
	                <img class="card-img-bottom" src="{{ asset('images/fotos/'.$vocal['foto']) }}" alt="Foto Vocal">
	                <br><br>
	                <a href="mailto:{{$vocal['email']}}" class="btn btn-primary">{{__('text.enviaremail')}}</a>
				</div>
			</div>
		</div>

		<br>
{{--PRÓXIMOS EVENTOS--}}
		<div class="card">
			<div class="card-header">
				<a class="card-link" data-toggle="collapse"  href="#menutwo" aria-expanded="false" aria-controls="menutwo">{{__('text.proximosEventos')}}
					<span class="badge badge-light">
						@if($eventos == null)
							0
						@else
							{{count($eventos)}}
						@endif
					</span><span class="collapsed"><p><b>></b></p></span>
				<span class="expanded"><p><b><</b></p></span></a>
			</div>
			<div id="menutwo" class="collapse">
				<div class="card-body">
					@if($eventos == null)
						<h2>&#128542;</h2> {{__('text.MVnoevet')}}
					@else
						@foreach($eventos as $evento)
							<div class="row">
								<div class="col-2 text-left">
									<h2><span class="badge badge-info">{{$evento['fechaInicio']->format('d')}}</span></h2>
									{{\Helper::dimeFechaCarbon($evento['fechaInicio'],10,'-')}}
								</div>
								<div class="col-10">
									<strong>{{$evento['titulo']}}</strong>
									<ul class="list-inline">
									    <li class="list-inline-item">&#128197; {{\Helper::dimeFechaCarbon($evento['fechaInicio'],2,'-')}}</li>
										<li class="list-inline-item">&#128337; {{$evento['fechaInicio']->format('H:i')}} - {{$evento['fechaFin']->format('H:i')}}</li>
										<li class="list-inline-item">&#128681; <a href="{{$evento['link']}}">{{__('text.MVverevent')}}</a></li>
									</ul>
									{{$evento['descripcion']}}
								</div>
							</div>
							<br>
				       	@endforeach
				    @endif
				</div>
			</div>
		</div>

		<br>
{{--PROPUESTAS DE COMPRAS--}}
		<div class="card">
			<div class="card-header">
				<a class="card-link" data-toggle="collapse"  href="#menu3" aria-expanded="false" aria-controls="menu3">{{__('text.MVpropcomp')}}<span class="collapsed"><p><b>></b></p></span>
				<span class="expanded"><p><b><</b></p></span></a>
			</div>
			<div id="menu3" class="collapse">
				<div class="card-body">
                    @if (Auth::user()->hasAnyPermission(['Acceso_total','permiso_vocalia_'.$vocalia['nombre']]))
                        <div class="shadow p-3 mb-5 bg-white rounded justify-content-center">
                            <h5><strong>{{__('text.informxVocal')}}:</strong></h5>
                            <ul class="list-group">
                                <li class="list-group-item d-inline-flex justify-content-between align-items-center">
                                    {{__('text.informxVocalL1')}}
                                    @if(is_numeric($numVotaciones) && $numVotaciones == 0)
                                        <span class="badge badge-success badge-pill">{{$numVotaciones}}</span>
                                    @else
                                        <span class="badge badge-success badge-pill">{{count($numVotaciones)}}</span>
                                    @endif
                                </li>
                                <li class="list-group-item d-inline-flex justify-content-between align-items-center">
                                    {{__('text.informxVocalL2')}}
                                    <span class="badge badge-danger badge-pill">{{$numSocNV}}</span>
                                </li>
                                <li class="list-group-item d-inline-flex justify-content-between align-items-center">
                                        {{__('text.informxVocalL3')}}
                                    <span class="badge badge-warning badge-pill">{{$numSocIVNV}}</span>
                                </li>
                            </ul>
                        </div>
                    @endif
					@if($propuestas != null)
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-sm">
                                {{-- <input type="hidden" name="tablename" value="{{encrypt('users')}}">
                                <input type="hidden" name="tableid" value="{{encrypt('id')}}">  --}}
                                <thead style="text-align: center;">
                                    <tr class="table-primary">
                                        <th scope="col" align="center">{{__('text.orden')}}</th>
                                        <th scope="col" align="center">{{__('text.socioProp')}}</th>
                                        <th scope="col" align="center">{{__('text.propuesta')}}</th>
                                        <th scope="col" align="center">{{__('text.precio')}}</th>
                                        <th scope="col" align="center" data-toggle="tooltip" data-placement="top" title="{{__('text.tipoCompraDesc')}} dependiendo si es mayor a {{$cantPropMen}} {{ __('text.simbDin')}}">{{__('text.tipoCompra')}}</th>
                                        @if($vocalia->id == 2)
                                            <th scope="col" align="center">{{__('text.BGGlink')}}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($propuestas as $propuesta)
                                    <tr class="justify-content-md-center">
                                        <td align="center">{{$propuesta['orden']}}º
                                            @if($propuesta['orden'] == 1)
                                                &#129351;
                                            @elseif($propuesta['orden'] == 2)
                                                &#129352;
                                            @elseif($propuesta['orden'] == 3)
                                                &#129353;
                                            @endif
                                        </td>
                                        <td align="center">{{$propuesta['socio']}}</td>
                                        <td align="center">{{$propuesta['propuesta']}}</td>
                                        <td align="center">{{$propuesta['cantidad']}}</td>
                                        <td align="center">{{$propuesta['mp']}}</td>
                                        @if($vocalia->id == 2)
                                            <td align="center"><a type="button" class="btn btn-light" href="{{$propuesta['BGG']}}" target='_blank'>&#128064;</a></td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
	                @else
	                    <h6>{{__('text.noPropuestas')}}</h6>
	                @endif
	                <div style="margin-top:5%">
	                    <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#nuevaPropuesta_modal" >{{ __('text.crearPropuesta') }}</button>
	                    &nbsp;&nbsp;
	                </div>
				</div>
			</div>
		</div>

		<br>
{{--VOTACIONES--}}
		<div class="card">
			<div class="card-header">
				<a class="card-link" data-toggle="collapse"  href="#menu4" aria-expanded="false" aria-controls="menu4">{{__('text.MVvot')}}<span class="collapsed"><p><b>></b></p></span>
				<span class="expanded"><p><b><</b></p></span></a>
			</div>
			<div id="menu4" class="collapse">
				<div class="card-body">
					@if($propuestas != null)
                        @if ($separaComp == 0)
                            @include('movil.vm1')
                        @else
                            @include('movil.vm2')
                        @endif
	                @else
	                    <h6>{{__('text.noPropuestas')}}</h6>
	                @endif
				</div>
			</div>
		</div>

	</div>
<br>
<br>
<br>

</div>

  <!-- Modal Nueva PROPUESTA DE COMPRA-->
<div id="nuevaPropuesta_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="container-fluid">
            <!-- Modal content-->
            <div class="modal-content col-12">
                <div class="modal-body">
                    <h5 style="text-align: center;">{{ __('text.crearPropuesta') }}</h5>
                    <hr>
                    <form action="{{ route('guardaPropuestaMovil') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                            <input type="text" name="vocaliaActual" id="vocaliaActual" style="display: none !important" value="{{$vocalia['id']}}" />

                            <div class="form-group row">
                               <label for="inputDesc" class="col-sm-2 col-form-label">{{ __('text.nombrePropuesta')}}</label>
                                <div class="col-sm-10">
                                  <input class="form-control" type="text" maxlength="191" name="inputDesc" id="informeDescripción" placeholder="{{ __('text.nombrePropuestaDesc')}}" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputCant" class="col-sm-2 col-form-label">{{ __('text.propPrecio')}}</label>
                                <div class="col-sm-10">
                                  <div class="input-group mb-3">
                                    <input class="form-control" type="text" maxlength="10" name="inputCant" id="inputCant" placeholder="{{ __('text.propPrecioDesc')}}" onkeypress="return esNumero(event)"/>
                                    <div class="input-group-append">
                                      <span class="input-group-text" id="basic-addon2">{{__('text.simbDin')}}</span>
                                    </div>
                                  </div>
                                </div>
                            </div>
                        <hr>
                        <div class="form-group row justify-content-md-center">
                            <button class="btn btn-success form-control" type="submit" data-toggle="modal" data-target="#trabajando_modal">
                                {{ __('text.btnGuardar')}}
                            </button>
                        </div>
                        <div class="form-group row justify-content-md-center">
                            <button type="button" class="btn btn-secondary form-control" data-dismiss="modal">
                                {{ __('text.btnClose')}}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</div> {{--FIN DEL CONTAINER--}}

<script type="text/javascript">
    // Show appropriate pill based on #anchor in URL
    var url = window.location.href;
    if (url.indexOf("#") > 0){
        var activeTab = url.substring(url.indexOf("#") + 1);
        $('.nav[role="tablist"] a[href="#'+activeTab+'"]').tab('show');
    }

    @if($vocalia['separaComp'] == 0)
    {
        var ctxR = document.getElementById("myPieChart");
        var myPieChart = new Chart(ctxR, {
        type: 'pie',
        data: {
            labels: @json($props),
            datasets: [{
            data: @json($vots),
            backgroundColor: @json($fillColors),
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
        myPieChart.render();
    }
    @else
    {
        var ctxRM = document.getElementById("myPieChartM");
        var myPieChartM = new Chart(ctxRM, {
        type: 'pie',
        title: "{{__('text.M_mayores')}}",
        data: {
            labels: @json($propsM),
            datasets: [{
            data: @json($votsM),
            backgroundColor: @json($fillColors),
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
        var ctxRP = document.getElementById("myPieChartP");
        var myPieChartP = new Chart(ctxRP, {
        type: 'pie',
        title: "{{__('text.M_menores')}}",
        data: {
            labels: @json($propsP),
            datasets: [{
            data: @json($votsP),
            backgroundColor: @json($fillColors),
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
        myPieChartM.render();
        myPieChartP.render();
    }
    @endif

</script>

<noscript>Please, enable JavaScript to see this page. Por favor, habilita JavaScript para ver esta página.</noscript>
@endsection
