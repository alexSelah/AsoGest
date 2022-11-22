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
		<p>Este es un email generado desde la página web de Portal Lúdico. Por favor, no respondas a este correo (nadie lo lee :)).</p>

		{{ $obj->reservaSala }}

		<p><b>{{ $obj->sender }}</b> ha reservado la Sala de ROL para un evento, y te ha invitado, en la Asociación.</p>

		<p>Estos son los datos de la actividad:</p>
		<div>
		<p><b>Asunto:</b>&nbsp;{{ $obj->asunto }}</p>
		<p><b>Descipción:</b>&nbsp;{{ $obj->descripcion }}</p>
		<p><b>Fecha de inicio:</b>&nbsp;{{ $obj->fecha }}</p>
		<p><b>Hora de inicio:</b>&nbsp;{{ $obj->hora }}</p>
		</div>


		<p>Si quieres ponerte en contacto con el organizador del evento, puedes hacerlo escribiéndole a él directamente a:</p>
		<a href="mailto:{{ $obj->emailSender }}">{{ $obj->emailSender }}</a>
		<br>

		<p>Esperamos que puedas asistir. <br>
		Un saludo de parte de <i>{{ $obj->sender }}</i></p>

		<br><br>
		<hr>
		<h3 style="color:rgb(0, 8, 120);">Gestor de Asociaciones ASOGEST.</h3>
		<a href="">www.ASOGEST.com</a>
	</body>
</html>
