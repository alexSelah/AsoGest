<?php

namespace App\Imports;

use App\Cuenta;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class hojaImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        //Saltamos la cabecera y las filas vacías
        if ($row[1]=="" || $row[1]=="Fecha" || $row[0]=="Asogest") {
            return null;
        }

        //dd($row[1],$row[0]);
        $fechaFormato1 = '~^\d{4}([\-/.])(0?[1-9]|1[1-2])\1(3[01]|[12][0-9]|0?[1-9])$~';
        $fechaFormato2 = '~^(?:0?[1-9]|1[1-2])([\-/.])(3[01]|[12][0-9]|0?[1-9])\1\d{4}$~';
        $fechaFormato3 = '~^(?:3[01]|[12][0-9]|0?[1-9])([\-/.])(0?[1-9]|1[1-2])\1\d{4}$~';
        if(preg_match($fechaFormato1, $row[1])){
            //Fecha en formato AÑO-MES-DIA
            $fecha = str_replace('/','-',$row[1]);
            $fecha = str_replace('\\','-',$row[1]);
            $fechaApunte = Carbon::createFromFormat('Y-m-d',$fecha);

        }elseif(preg_match($fechaFormato2, $row[1])){
            //Fecha en formato MES-DIA-AÑO
            $fecha = str_replace('/','-',$row[1]);
            $fecha = str_replace('\\','-',$row[1]);
            $fechaApunte = Carbon::createFromFormat('m-d-Y',$fecha);
        }elseif(preg_match($fechaFormato3, $row[1])){
            //Fecha en formato DIA-MES-AÑO
            $fecha = str_replace('/','-',$row[1]);
            $fecha = str_replace('\\','-',$row[1]);
            $fechaApunte = Carbon::createFromFormat('d-m-Y',$fecha);
        }else{
            //Fecha en formato EXCEL
            $fecha = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[1])->format('Y-m-d');
            $fechaApunte = Carbon::createFromFormat('Y-m-d',$fecha);
        }

        //Miramos si existe el número de asiento para actualizarlo
        if ($row[0]!="") {
            $cuentaEncontrada = Cuenta::where('id',$row[0])->first();
            if($cuentaEncontrada != null){
                $cuentaEncontrada->fechaApunte = $fechaApunte;
                $cuentaEncontrada->año = $row[2];
                $cuentaEncontrada->tipo = $row[3];
                $cuentaEncontrada->conceptoAgrupado = $row[4];
                $cuentaEncontrada->detalle = $row[5];
                $cuentaEncontrada->vocalia = $row[6];
                $cuentaEncontrada->cantidad = $row[7];
                $cuentaEncontrada->pagcob = $row[8];
                $cuentaEncontrada->notas = $row[9];
                $cuentaEncontrada->save();
            }else{
                return new Cuenta([
                    'fechaApunte'   => $fechaApunte,
                    'año'   => $row[2],
                    'tipo'   => $row[3],
                    'conceptoAgrupado' => $row[4],
                    'detalle'   => $row[5],
                    'vocalia'   => $row[6],
                    'cantidad'   => $row[7],
                    'pagcob' => $row[8],
                    'notas'   => $row[9],
                ]);
            }
            return $cuentaEncontrada;
        }else{
            return new Cuenta([
                'fechaApunte'   => $fechaApunte,
                'año'   => $row[2],
                'tipo'   => $row[3],
                'conceptoAgrupado' => $row[4],
                'detalle'   => $row[5],
                'vocalia'   => $row[6],
                'cantidad'   => $row[7],
                'pagcob' => $row[8],
                'notas'   => $row[9],
            ]);
        }
    }
}
