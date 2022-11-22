<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<html lang="es">
	<head>
	    <meta charset="UTF-8">
	    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
	</head>
	<body>
		{{ $obj->estimad }} <i>{{ $obj->receiver }}</i>,
		<p>Desde la Sección de Tesorería de ASOGEST te envían el siguiente mensaje. Por favor, no respondas a este correo, ponte en contacto con el Tesorero en la dirección más abajo.</p>
		<p><b>Fecha:</b> {{ $obj->fecha }}</p>
		<div>
			<p><b>Mensaje:</b></p>
			<br>
			<p>{!! $obj->texto !!} </p>
		</div>
		<br>
		<p>Mensaje enviado por:
			{{ $obj->sender }}
		</p>
		<p>Email de contacto: <a href="mailto:{{ $obj->emailSender }}">{{ $obj->emailSender }}</a></p>
		<p>Un saludo</p>
		<hr>
		<h3 style="color:rgb(0, 8, 120);">Gestor de Asociaciones ASOGEST.</h3>
		<a href="">www.ASOGEST.com</a>
	</body>
</html>
