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
    <title>Informe {{__('text.nombreAsoc')}}</title>
  </head>
  <body>
    <br>
    <table style="width:100%">
        <tr>
            <th><img src="data:image/png;base64, {{$logo}}" style="max-width: 100px !important;"></th>
            <th><p style="text-align:center"><h1>{{$nombreInf}}</h1></p></th>
        </tr>
    </table>

    <br>
    {!! $texto !!}
    <br><br>
    <p style="text-align:right">En Internet, a {{$fecha}}</p>
    <br><br><br>
    <p style="text-align:right">fdo. el tesorero: {{$tesorero}}</p>
  </body>
</html>
