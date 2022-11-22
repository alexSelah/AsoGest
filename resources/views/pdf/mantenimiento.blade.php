<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
.page-break {
    page-break-after: always;
}
</style>
<html>
  <head>
    <meta charset="utf-8">
    <title>Declaración de Gasto de Mantenimiento</title>
  </head>
  <body>
    <img src="data:image/png;base64, {{$logo}}">
    <br>
    <h1>Gastos de mantenimiento durante el mes de {{$mes}}</h1>
    <br>
    <br>
    <p>En ASOGEST hay, a fecha del mes de {{$mes}}, activos {{$socios}} socios. </p>
    <p>Actualmente la cuota de mantenimiento por socio y mes está establecida (por Asamblea General) en {{$cuotaMant}} €</p>
    <br>
    <p>Como tesorero de la Asociación comunico que, para este mes, el total a abonar a en concepto de mantenimiento (alquiler, luz, agua, etc) es de <strong>{{$cantidad}} €</strong></p>
    <br>
    <p style="text-align:right">En Collado Villaba, a {{$fecha}}</p>
    <br><br><br>
    <p style="text-align:right">fdo. el tesorero: {{$tesorero}}</p>
  </body>
</html>
