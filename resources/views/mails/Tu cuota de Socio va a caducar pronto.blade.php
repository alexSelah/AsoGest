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

		<p><h2>Tu cuota de Socio va a caducar pronto. La cuota cumple con fecha: {{ $obj->fechaVencimiento }}</h2></p>

		<p><b>Estos son datos de tu última cuota:</b></p>
		<div>
		<p>Fecha de Pago: {{ $obj->fecha}} </p>
		<p>Tipo de cuota: {{ $obj->tipo }}</p>
		<p>Cantidad: {{ $obj->cantidad }}</p>
		</div>

		<p>Lo normal es que la renovación de cuota sea ANUAL, ya que tiene numerosas ventajas para los socios, como descuentos en la tienda y mayor número de invitaciones. La cuota anual está actualmente establecida en: {{ $obj->cuotaAnual }} €</p>

		<p>Te recordamos que, de no renovar tu cuota, no podrás acceder a ningún sitio de la web salvo a tu ficha de socio, y no podrás disfrutar de las ventajas de ser Socio, como disfrutar de la Ludoteca, asistir a eventos organizados por la Asociación, asistir a las noches del socio, etc.</p>

		<p>Si quieres renovar tu cuota, ponte en contacto con el Tesorero y el te dirá como proceder</p>
		Email del tesorero: <a href="mailto:{{ $obj->emailTesorero }}">{{ $obj->emailTesorero }}</a>
		<br>

		<p>Un saludo</p>

		<br>
		<hr>
		<h3 style="color:rgb(0, 8, 120);">Gestor de Asociaciones ASOGEST.</h3>
		<a href="">www.ASOGEST.com</a>
	</body>
</html>
