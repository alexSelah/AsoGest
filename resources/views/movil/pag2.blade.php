@extends('movil.layout')

@section('content')

<style type="text/css">
	.responsiveCal {
		position: relative; padding-bottom: 100%; height: 100%; overflow: hidden;
	}

	.responsiveCal iframe {
		position: absolute; top:0; left: 0; width: 100%; height: 100%;
	}
</style>

<div class="container">

	<div class="responsiveCal">
		<?php echo $cal4 ?>
	</div>

</div>

<noscript>Please, enable JavaScript to see this page. Por favor, habilita JavaScript para ver esta página.</noscript>
@endsection
