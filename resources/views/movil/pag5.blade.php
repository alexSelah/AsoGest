@extends('movil.layout')

@section('content')

<div class="container">
	<div id="accordion">
		<h2>{{__('text.MVvocalias')}}</h2>
        @foreach($vocalias as $vocalia)
            <div class="card">
                <div class="card-header" id="header<?php echo $vocalia['nombre'] ?>">
                  <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapse<?php echo $vocalia['nombre'] ?>" aria-expanded="false" aria-controls="collapse<?php echo $vocalia['nombre'] ?>">
                        <?php echo $vocalia['nombre'] ?>
                    </button>
                  </h5>
                </div>

                <div id="collapse<?php echo $vocalia['nombre'] ?>" class="collapse hidden" aria-labelledby="<?php echo $vocalia['nombre'] ?>" data-parent="#accordion" >
                  <div class="row no-gutters">
                        <div class="col" style="width: 100%">
                          <img src="images/<?php echo $vocalia['imagen'] ?>" class="card-img" alt="<?php echo $vocalia['imagen'] ?>">
                        </div>
                        <div class="col" style="width: 100%">
                            <div class="card-body">
                                <h5 class="card-title">{{__('text.tituloTextoVocalia')}} <?php echo $vocalia['nombre'] ?></h5>
                                <a type="button" class="btn btn-primary btn-sm" onclick="irAVocalia(<?php echo $vocalia['id'] ?>)">{{__('text.btnVocalia')}}</a>
                            </div>
                        </div>
                      </div>
                </div>
              </div>
        @endforeach
    </div>
</div>

<script type="text/javascript">
    function irAVocalia ($num){
        var $ruta = "{{url('/vocaliaMovil')}}/"+$num ;
        window.location.href = $ruta;
    }

</script>

<noscript>Please, enable JavaScript to see this page. Por favor, habilita JavaScript para ver esta página.</noscript>
@endsection
