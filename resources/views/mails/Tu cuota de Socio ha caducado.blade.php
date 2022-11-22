<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<html lang="es">
	<head>
	    <meta charset="UTF-8">
	    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
	</head>
	<body>
		Hola <i>{{ $obj->receiver }}</i>,
		<p>Este es un email generado desde la página web de Portal Lúdico. Por favor, no respondas a este correo (nadie lo lee :)).</p>

		<p><h1>Tu cuota de Socio ha caducado.</h1>
		<h3> La cuota cumple con fecha: {{ $obj->fechaVencimiento }}, y ya ha pasado esa fecha.</h3></p>

		<p><b>Estos son datos de tu última cuota:</b></p>
		<div>
		<p>Fecha de Pago: {{ $obj->fecha}} </p>
		<p>Tipo de cuota: {{ $obj->tipo }}</p>
		<p>Cantidad: {{ $obj->cantidad }}</p>
		</div>

		<p><h3><strong> Al no haber renovado tu cuota, es posible que tu socio se ha desactivado</strong></h3>, solo podrás acceder a tu ficha de socio de la página web, pero no podrás disfrutar de las ventajas de ser Socio, como disfrutar de la Ludoteca, asistir a eventos organizados por la Asociación, asistir a las noches del socio, etc.</p>

		<p>Si quieres renovar tu cuota, ponte en contacto con el Tesorero y el te dirá como proceder</p>
		Email del tesorero: <a href="mailto:{{ $obj->emailTesorero }}">{{ $obj->emailTesorero }}</a>
		<br>

        <p>Hasta que se confirme tu baja por parte del secretario, seguirás recibiendo este email cada cierto tiempo. Te recomendamos que, si no deseas seguir en la asociación, curses tu baja para no seguir recibiendo estas comunicaciones

		<p>Es posible que no quieras seguir en la Asociación, en ese caso no te molestaremos más con email ni comunicaciones y solo qeuda decirte que ha sido un placer tenerte entre nosotros, y, como sabes, las puertas de Portal Lúdico siempre estarán abiertas si quisieras volver.</p>
		<br>

		<p>Un saludo</p>

		<br>
		<hr>
		<h3 style="color:rgb(0, 8, 120);">Gestor de Asociaciones ASOGEST.</h3>
		<a href="">www.ASOGEST.com</a>
	</body>
</html>
