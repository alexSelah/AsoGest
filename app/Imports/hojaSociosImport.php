<?php

namespace App\Imports;

use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class hojaSociosImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        //Saltamos la cabecera y las filas vacías
        if ($row[1]=="" || $row[1]=="Alta" || $row[0]!="") {
            return null;
        }


        //FECHAS EN EXPRESIONES REGULARES
        $fechaFormato1 = '~^(19|20)\d\d([- /.])(0[1-9]|1[012])\2(0[1-9]|[12][0-9]|3[01])$~';//Fecha en formato AÑO-MES-DIA
        $fechaFormato2 = '~^(?:0?[1-9]|1[1-2])([\-/.])(3[01]|[12][0-9]|0?[1-9])\1\d{4}$~';//Fecha en formato MES-DIA-AÑO
        $fechaFormato3 = '~^(?:3[01]|[12][0-9]|0?[1-9])([\-/.])(0?[1-9]|1[1-2])\1\d{4}$~';//Fecha en formato DIA-MES-AÑO

        //FECHA DE ALTA
        $fechaAlta = null;
        if($row[1]!= null && preg_match($fechaFormato1, $row[1])){
            $fecha = str_replace("/",'-',$row[1]);
            $fecha = str_replace('\\','-',$fecha);
            $fechaAlta = Carbon::createFromFormat('Y-m-d',$fecha);

        }elseif($row[1]!= null && preg_match($fechaFormato2, $row[1])){
            $fecha = str_replace("/",'-',$row[1]);
            $fecha = str_replace('\\','-',$fecha);
            $fechaAlta = Carbon::createFromFormat('m-d-Y',$fecha);
        }elseif($row[1]!= null && preg_match($fechaFormato3, $row[1])){
            $fecha = str_replace("/",'-',$row[1]);
            $fecha = str_replace('\\','-',$fecha);
            $fechaAlta = Carbon::createFromFormat('d-m-Y',$fecha);
        }else{
            //Fecha en formato EXCEL
            if($row[1]!= null){
                $fecha = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[1])->format('Y-m-d');
                $fechaAlta = Carbon::createFromFormat('Y-m-d',$fecha);
            }
        }
        if($fechaAlta == null){
            $fechaAlta = Carbon::now();
        }

        //FECHA DE BAJA
        $fechaBaja = null;
        if($row[2]!= null && preg_match($fechaFormato1, $row[2])){
            $fecha = str_replace("/",'-',$row[2]);
            $fecha = str_replace('\\','-',$fecha);
            $fechaBaja = Carbon::createFromFormat('Y-m-d',$fecha);

        }elseif($row[2]!= null && preg_match($fechaFormato2, $row[2])){
            $fecha = str_replace("/",'-',$row[2]);
            $fecha = str_replace('\\','-',$fecha);
            $fechaBaja = Carbon::createFromFormat('m-d-Y',$fecha);
        }elseif($row[2]!= null && preg_match($fechaFormato3, $row[2])){
            $fecha = str_replace("/",'-',$row[2]);
            $fecha = str_replace('\\','-',$fecha);
            $fechaBaja = Carbon::createFromFormat('d-m-Y',$fecha);
        }else{
            //Fecha en formato EXCEL
            if($row[2]!= null){
                $fecha = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[2])->format('Y-m-d');
                $fechaBaja = Carbon::createFromFormat('Y-m-d',$fecha);
            }
        }

        //FECHA DE NACIMIENTO
        $fNacimiento = null;
        if($row[6]!= null && preg_match($fechaFormato1, $row[6])){
            $fecha = str_replace("/",'-',$row[6]);
            $fecha = str_replace('\\','-',$fecha);
            $fNacimiento = Carbon::createFromFormat('Y-m-d',$fecha);

        }elseif($row[6]!= null && preg_match($fechaFormato2, $row[6])){
            $fecha = str_replace("/",'-',$row[6]);
            $fecha = str_replace('\\','-',$fecha);
            $fNacimiento = Carbon::createFromFormat('m-d-Y',$fecha);
        }elseif($row[6]!= null && preg_match($fechaFormato3, $row[6])){
            $fecha = str_replace("/",'-',$row[6]);
            $fecha = str_replace('\\','-',$fecha);
            $fNacimiento = Carbon::createFromFormat('d-m-Y',$fecha);
        }else{
            //Fecha en formato EXCEL
            if($row[6]!= null){
                $fecha = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[6])->format('Y-m-d');
                $fNacimiento = Carbon::createFromFormat('Y-m-d',$fecha);
            }
        }
        if($fNacimiento == null){
            $fNacimiento = (Carbon::now())->addMonths(-216);
        }

        if($row[10] = "Si" || $row[10] = "si" || $row[10] = "sí" || $row[10] = "Sí" || $row[10] = "SI" || $row[10] = "1" || $row[10] = "x" || $row[10] = "X"){
            $habilit = true;
        }else{
            $habilit = false;
        }

        if($row[14] == "" || $row[14]==null){
            $username = $row[3].$row[0];
        }else{
            $username = $row[14];
        }

        if($row[7] == "" || $row[7] == null){
            $dni = Carbon::now()->format('Ymd').chr(rand(65,90));
        }else{
            $dni = $row[7];
        }

        if($row[8] == "" || $row[8] == null){
            $email = $username."@".$dni.".com";
        }else{
            $email = $row[8];
        }

        //Miramos si existe el número de asiento para actualizarlo
        if ($row[0]!="") {
            $socioEncontrado = User::where('numSocio',$row[0])->first();
            if($socioEncontrado != null){
                $socioEncontrado->numSocio = $socioEncontrado->numSocio;
                $socioEncontrado->altaSocio = $fechaAlta;
                $socioEncontrado->bajaSocio = $fechaBaja;
                $socioEncontrado->nombre = $row[3];
                $socioEncontrado->primerApellido = $row[4];
                $socioEncontrado->segundoApellido = $row[5];
                $socioEncontrado->DNI = $dni;
                $socioEncontrado->sexo = $socioEncontrado->sexo;
                $socioEncontrado->fnacimiento = $fNacimiento;
                $socioEncontrado->direccion = $row[11];
                $socioEncontrado->localidad = $row[12];
                $socioEncontrado->provincia = $row[13];
                $socioEncontrado->codPostal = $socioEncontrado->codPostal;
                $socioEncontrado->telefono = $row[9];
                $socioEncontrado->email = $email;
                $socioEncontrado->foto = $socioEncontrado->foto;
                $socioEncontrado->username = $username;
                $socioEncontrado->password = $socioEncontrado->password;
                $socioEncontrado->habilitado = $habilit;
                $socioEncontrado->accesoDrive = $socioEncontrado->accesoDrive;
                $socioEncontrado->recibirCorreos = $socioEncontrado->recibirCorreos;
                $socioEncontrado->privacidad = $socioEncontrado->privacidad;
                $socioEncontrado->save();
            }else{
                $socio = new User([
                    'numSocio' => '99999999',
                    'bajaSocio' => $fechaBaja,
                    'altaSocio' => $fechaAlta,
                    'nombre' => $row[3],
                    'primerApellido' => $row[4],
                    'segundoApellido' => $row[5],
                    'DNI' => $dni,
                    'sexo' => 'nodefinido',
                    'fnacimiento' => $fNacimiento,
                    'direccion' => $row[11],
                    'localidad' => $row[12],
                    'provincia' => $row[13],
                    'codPostal' => 28400,
                    'telefono' => $row[9],
                    'email' => $email,
                    'username' => $username,
                    'password' => "XXX",
                    'habilitado' => $habilit,
                    'foto' => '',
                    'accesoDrive' => false,
                    'accesoJunta' => false,
                    'recibirCorreos' => true,
                    'privacidad' => true,
                ]);
                $socio->password = bcrypt($username.$socio->numSocio);
                $socio->save();
                $socio->numSocio = $socio->id;
                $socio->save();
                return $socio;
            }
            return $socioEncontrado;
        }else{
            $socio = new User([
                'numSocio' => '99999999',
                'bajaSocio' => $fechaBaja,
                'altaSocio' => $fechaAlta,
                'nombre' => $row[3],
                'primerApellido' => $row[4],
                'segundoApellido' => $row[5],
                'DNI' => $dni,
                'sexo' => 'nodefinido',
                'fnacimiento' => $fNacimiento,
                'direccion' => $row[11],
                'localidad' => $row[12],
                'provincia' => $row[13],
                'codPostal' => 28400,
                'telefono' => $row[9],
                'email' => $email,
                'username' => $username,
                'password' => "XXX",
                'foto' => '',
                'habilitado' => $habilit,
                'accesoDrive' => false,
                'accesoJunta' => false,
                'recibirCorreos' => true,
                'privacidad' => true,
            ]);
            $socio->password = bcrypt($username.$socio->numSocio);
            $socio->save();
            $socio->numSocio = $socio->id;
            $socio->save();
            return $socio;
        }
    }
}
