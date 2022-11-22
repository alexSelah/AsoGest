<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<html lang="es">
	<head>
	    <meta charset="UTF-8">
	    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
	    <title>{{ $obj->asunto}}</title>
	</head>
	<body>
		<p>Hola <i>{{ $obj->receiver }}</i>,</p>
		<br>
		<p>Este es un email generado desde la página web de Portal Lúdico. Por favor, no respondas a este correo (nadie lo lee :)).</p>

		<p>El Vocal <i>{{ $obj->sender }}</i> te ha enviado un mensaje.</p>

		<p><b>Este es el mensaje:</b></p>
		<div>
			<ul>
		        <li><b>Asunto:</b> {{ $obj->asunto}}</li>
				<li><b>Mensaje:</b> {{ $obj->texto}}</li>
				<li><b>Fecha:</b> {{ $obj->fecha }}</li>
		    </ul>
		</div>

		<p>Si quieres ponerte en contacto con el vocal, para consultar cualquier cosa, puedes hacerlo escribiéndole a él directamente a:</p>
		<p><a href="mailto:{{ $obj->emailSender }}">{{ $obj->emailSender }}</a><p>
		<br><br>

		Un saludo de parte de <i>{{ $obj->sender }}</i></p>

		<br><br>
		<hr>
		<h3 style="color:rgb(0, 8, 120);">Gestor de Asociaciones ASOGEST.</h3>
		<a href="">www.ASOGEST.com</a>

	</body>
</html>
