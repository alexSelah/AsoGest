<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<html lang="es">
	<head>
	    <meta charset="UTF-8">
	    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
	</head>
	<body>
		Hola <i>{{ $obj->receiver }}</i>,
		<p>Este es un email generado desde la página web de ASOGEST. Por favor, no respondas a este correo (nadie lo lee :)).</p>

		<p><h2>ESTE ES UN EMAIL GENERADO AUTOMÁTICAMENTE DE MANERA MENSUAL</h2></p>

		<p><b>Estos son datos del email:</b></p>
		<div>
		<p><b>Fecha:</b>{{ $obj->fecha}} </p>
		<p><b>Mensaje:</b> {{ $obj->texto }}</p>
		</div>

		<p>Un saludo</p>

		<br>
		<hr>
		<h3 style="color:rgb(0, 8, 120);">Gestor de Asociaciones ASOGEST.</h3>
		<a href="">www.ASOGEST.com</a>
	</body>
</html>
