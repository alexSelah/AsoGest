<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<html lang="es">
	<head>
	    <meta charset="UTF-8">
	    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
	    <title>{{ $obj->asunto}}</title>
	</head>
	<body>
		Hola <i>{{ $obj->receiver }}</i>,
		<p>Este es un email generado desde la página web de ASOGEST. Por favor, no respondas a este correo (nadie lo lee :)).</p>

		<p>Te comunicamos que el Vocal <i>{{ $obj->sender }}</i>, después de estudiar las votaciones al respecto, ha decidido comprar uno de los artículos.</p>

		<p><b>Estos son los resultados:</b></p>
		<div>
		<p>Artículos que había propuestos y sus votos: {{ $obj->opciones}} </p>
		<p>Artículo que se va a comprar: {{ $obj->resultado }}</p>
		<p>Fecha: {{ $obj->fecha }}</p>
		</div>

		<p> {{ $obj->borrar}} </p>

		<p>Si quieres ponerte en contacto con el vocal, para consultar cualquier cosa, puedes hacerlo escribiéndole a él directamente a:</p>
		<a href="mailto:{{ $obj->emailSender }}">{{ $obj->emailSender }}</a>
		<br>

		Un saludo de parte de <i>{{ $obj->sender }}</i></p>

		<br><br>
		<hr>
		<h3 style="color:rgb(120,0,1);">Gestor de Asociaciones ASOGEST.</h3>
		<a href="">www.ASOGEST.com</a>
	</body>
</html>
