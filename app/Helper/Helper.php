<?php

namespace App\Helper;

use Illuminate\Support\Facades\DB;
use App\Mail\Email;
use Illuminate\Support\Facades\Mail;
use App\Configura;
use Carbon\Carbon;
use Log;
use Illuminate\Support\Facades\Lang;
use App\User;
use App\Cuota;
use App\Vocalia;

class Helper
{
	public static function instance()
	{
		return new Helper();
	}


	/**
	 * Función que devuelve, en formato texto, la fecha actual. Si se introduce un 0 o null en cualquiera de las fechas (dia, mes o año) devolverá la fecha actual
	 * @param  int|null $dia    día en formato número (01- 02 ... - 29- 30 -31)
	 * @param  int|null $mes    mes en formato número (01- 02 ... 12)
	 * @param  int|null $year   año en formato número (1981 , 2020 ...)
	 * @param  int|null $opcion $opcion Tipo de conversión:
	 *                          DEFAULT-fecha completa(Jueves, 18 de Junio de 2020),
	 *                          1-dia (01),
	 *                          2-dia de la semana (Martes),
	 *                          3-mes (Junio),
	 *                          4-año,
	 *                          5- fecha completa (01/junio/2020),
	 *                          6-mes/año(Junio/2020),
	 *                          7-para firmas(18 de Junio de 2020),
	 *                          8-Mes corto (10/Jun/2020),
	 *                          9-Inverso, año-mes-dia (2020/6/25),
	 *                          10- Mes corto (Jun)
	 *                          11- ordenado por año y mes largo (2020-Junio-25)
	 *  @param  char|null $separador Separador de los elementos de fecha. puede ser -, / o null. Si se deja null pone la barra (/)
	 * @return [string]           Un string con la fecha formateada según la opción escogida
	 * *
	 * USO EN BLADE: {{ \Helper::dimeFecha(01,05,2020,5,"-") }}
	 * USO EN CONTROLLER: \Helper::dimeFecha(null,0,0, null)
	 */
	public static function dimeFecha (int $dia = null, int $mes = null, int $year = null, int $opcion = null, $separador=null)
	{
	    if($separador == null){
	    	$s = "/";
	    }else{
	    	$s = $separador;
	    }
	    $week_days = array ("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
	    $months = array ("00" => "", "01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre");
	    $monthsCortos = array ("00" => "", "01" => "Ene", "02" => "Feb", "03" => "Mar", "04" => "Abr", "05" => "May", "06" => "Jun", "07" => "Jul", "08" => "Ago", "09" => "Sept", "10" => "Oct", "11" => "Nov", "12" => "Dic");
	    if(!is_numeric($dia) || ($dia < 1 || $dia > 31)){
	    	$f = Carbon::now();
	    }elseif(!is_numeric($mes) || ($mes < 1 || $mes > 12)){
	    	$f = Carbon::now();
	    }elseif(!is_numeric($year) || ($year < 1 || $year > 9999)){
	    	$f = Carbon::now();
	    }else{
	    	$f = Carbon::createFromFormat('d-m-Y', $dia.'-'.$mes.'-'.$year);
	    }
	    $year = $f->format('Y');
	    $month = $f->format('m');
	    $day = $f->format('d');
	    $week_day = $f->dayOfWeek;
	    switch ($opcion) {
		    case 1:
	    		$fechaTexto = $day;
		        break;
		    case 2:
		        $fechaTexto = $week_days[$week_day];
		        break;
		    case 3:
		        $fechaTexto = $months[$month];
		        break;
		    case 4:
		        $fechaTexto = $year;
		        break;
		    case 5:
		        $fechaTexto = $day . $s . $months[$month] . $s . $year;
		        break;
		    case 6:
		        $fechaTexto = $months[$month] . $s . $year;
		        break;
		    case 7:
		    	$fechaTexto = $day . " de " . $months[$month] . " de " . $year;
		    	break;
		    case 8:
		        $fechaTexto = $year . $s . $monthsCortos[$month] . $s . $day;
		        break;
		    case 9:
		        $fechaTexto = $year . $s . $month . $s . $day;
		        break;
		    case 10:
		        $fechaTexto = $monthsCortos[$month];
		        break;
		    case 11:
		        $fechaTexto = $year . $s . $months[$month] . $s . $day;
		        break;
		    default:
		    	$fechaTexto = $week_days[$week_day] . ", " . $day . " de " . $months[$month] . " de " . $year;
		    	break;
		}
	    return $fechaTexto;
	}

	/**
	 * Igual que la anterior, pero acepta un elemento Carbon para gestionar la fecha
	 * @param  Carbon   $f         [description]
	 * @param  int|null $opcion    [description]
	 * @param  [type]   $separador [description]
	 * @return [type]              [description]
	 */
	public static function dimeFechaCarbon (Carbon $f, int $opcion = null, $separador=null)
	{
	       return \Helper::dimeFecha($f->format('d'),$f->format('m'),$f->format('Y'),$opcion,$separador);
	}

	/**
	 * Envia un email a los socios que tienen cuotas atrasadas
	 * @return [type] [description]
	 */
	public static function emailCuotas ()
	{
		$hoy = Carbon::now();
        $socios = User::where('habilitado', true)->get();
        foreach ($socios as $socio) {
            $cuota = Cuota::where('idSocio', $socio->id)->get()->last();
            if($cuota == null){
            	Log::error('ERROR. Se ha encontrado un socio activo que no tiene ninguna cuota asociada: ('.$socio->id.") ". $socio->dimeNombre());
            }
            else
            {
	            if($hoy->eq($cuota->venceCuota()->addDays(-30))){
	                Log::info(Lang::get('logtext.L_venceCuotames'). '('.$socio->id.") ". $socio->dimeNombre());
	                $obj = new \stdClass();
			        $obj->plantilla = "Tu cuota de Socio va a caducar pronto";
			        $obj->receiver = $socio->nombre;
			        $fu = $cuota->venceCuota();
			        $obj->fecha = \Helper::dimeFecha($fu->format('d'),$fu->format('m'),$fu->format('Y'),5,"-");
			        $fu = $cuota->venceCuota();
			        $obj->fechaVencimiento = \Helper::dimeFecha($fu->format('d'),$fu->format('m'),$fu->format('Y'),5, "-");
			        $obj->tipo = strtoupper($cuota->tipoCuota);
			        $obj->cantidad = $cuota->cantidad . Lang::get('text.simbDin');
			        $obj->emailTesorero = Lang::get('text.emailTesorero');
			        $obj->emailReceiver = $socio->email;
			        Mail::to($obj->emailReceiver)->send(new Email($obj));
	            }
	            if($hoy->eq($cuota->venceCuota())){
	                Log::info(Lang::get('logtext.L_venceCuota').'('.$socio->id.") ". $socio->dimeNombre());
	                $obj = new \stdClass();
			        $obj->plantilla = "Tu cuota de Socio ha caducado";
			        $obj->receiver = $socio->nombre;
			        $fu = $cuota->venceCuota();
			        $obj->fecha = \Helper::dimeFecha($fu->format('d'),$fu->format('m'),$fu->format('Y'),5,"-");
			        $fu = $cuota->venceCuota();
			        $obj->fechaVencimiento = \Helper::dimeFecha($fu->format('d'),$fu->format('m'),$fu->format('Y'),5, "-");
			        $obj->tipo = strtoupper($cuota->tipoCuota);
			        $obj->cantidad = $cuota->cantidad . Lang::get('text.simbDin');
			        $obj->cuotaAnual = Configura::getValor('CASV');
			        $obj->emailTesorero = Lang::get('text.emailTesorero');
			        $obj->emailReceiver = $socio->email;
			        Mail::to($obj->emailReceiver)->send(new Email($obj));
			        $socio->habilitado = false;
			        $socio->save();
	            }
	        }
        }
        return true;
	}

	/**
     * FUNCIÓN QUE SE EJECUTA UNA VEZ AL MES. PARTE DE APP/CONSOLE/KERNEL.PHP.
     * Sustituye a los comandos de consola, que no se pueden ejecutar en el servidor debido a restricciones de seguridad.
     *
     * @return mixed
     */
    public static function monthlyPortal()
    {
        // TAREAS QUE SE REALIZARAN CADA MES
        // Hay que crear las siguientes tareas:
        // envio de correos electronicos a los que las cuotas caduquen al mes siguiente.
        //
        $socios = User::where('habilitado',true)->get();
        $hoy = Carbon::now();
        foreach ($socios as $socio) {
            $asignaciones = $socio->asignaciones()->get();
            $cuota = Cuota::where('idSocio', $socio->id)->get()->last();
            foreach ($asignaciones as $asignacion) {
                $vocalia = Vocalia::where('id', $asignacion->idVocalia)->first();
                if($cuota == null){
                    Log::error('ERROR en la asignación mensual a las Vocalías. No existe una cuota asociada al Socio: ('.$socio->id.") ". $socio->dimeNombre());
                }else{
                    $cantidad = $cuota->participacion() / count($asignaciones);
                    $vocalia->presupuesto += $cantidad;
                    $vocalia->save();
                }
            }
        }
        Log::info('SE HAN ACTUALIZADO LAS ASIGNACIONES MENSUALES DE LAS VOCALÍAS Y SE HAN ENVIADO LOS EMAILS SOBRE LAS CUOTAS');
    }

    public static function logoBase64(){
    	return Configura::getValor('logoBase64');
    }
}
