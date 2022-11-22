<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
.page-break {
    page-break-after: always;
}
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <table class="table table-bordered">
      <tr>
        <td>
          {{$user->full_name}}
        </td>
        <td>
          {{$user->street_address}}
        </td>
      </tr>
      <tr>
        <td>
          {{$user->city}}
        </td>
        <td>
          {{$user->zip_code}}
        </td>
      </tr>
    </table>
  </body>
</html>

{{-- 
<style>
.page-break {
    page-break-after: always;
}
</style>
<h1>Page 1</h1>
<div class="page-break"></div>
<h1>Page 2</h1> --}}

/*EN EL CONTROLLER:
use PDF; 
$pdf = PDF::loadView('pdf.invoice', $data);
return $pdf->download('invoice.pdf');*/