<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Arr;
use yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Image;
use App\AsignacionSocio;
use App\User;
use App\Cuota;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;
use App\Cuenta;
use App\Vocalia;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CuentasImport;
use App\Configura;
use Helper;
use PDF;
use File;
use App\Mail\Email;
use App\TiposCuota;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use RuntimeException;

class TesoreroController extends Controller
{

#### ##    ## ########  ######## ##     ##
 ##  ###   ## ##     ## ##        ##   ##
 ##  ####  ## ##     ## ##         ## ##
 ##  ## ## ## ##     ## ######      ###
 ##  ##  #### ##     ## ##         ## ##
 ##  ##   ### ##     ## ##        ##   ##
#### ##    ## ########  ######## ##     ##
    /* Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $f1 = null, $f2 = null)
    {
        $user = Auth::user();
        if($user == null){
            return view('welcome');
        }

        if(!(Auth::user()->hasRole(['admin','secretario','tesorero']))){
            //return view('prohibido');
            return redirect()->route('datosXanio');
        }

        //dd($this->fechaG1, $this->fechaG2);
        if($f1 != null){
            $fecha1 =  new Carbon($f1);
        }else{
            $fecha1 = Carbon::createFromFormat('Y-m-d',Lang::get('text.AsocCreatDate'));
        }
        if($f2 != null){
            $fecha2 = new Carbon($f2);
        }else{
            $fecha2 = Carbon::now();
        }

        $gastos = Cuenta::where('fechaApunte', '>=', $fecha1)
                    ->where('fechaApunte', '<=', $fecha2)
                    ->where('tipo','=', 'Gasto')->sum('cantidad');
        $ingresos = Cuenta::where('fechaApunte', '>=', $fecha1)
                    ->where('fechaApunte', '<=', $fecha2)
                    ->where('tipo','=', 'Ingreso')->sum('cantidad');
        $cuotas = Cuota::where('fechaCuota', '>', $fecha1)
                    ->where('fechaCuota', '<', $fecha2)->sum('cantidad');
        $sociosActivos = User::where('habilitado','=',true)->pluck('id');
        $sociosNoActivos = User::where('habilitado','=',false)->pluck('id');

        $cuotasSocActivos = array();
        foreach ($sociosActivos as $key => $value) {
            $cuotaSocaux = Cuota::where('idSocio', $value)->get()->last();
            if($cuotaSocaux != null){
                $cuotasSocActivos[] =$cuotaSocaux;
            }
        }
        $cuotasAtrasadasSocios = array();
        foreach ($cuotasSocActivos as $key => $value) {
            if($value->venceCuota()->lte(Carbon::now())){
                $cuotasAtrasadasSocios[] = $value;
            }
        }
        $porctCuotasAtrasadas = count($cuotasAtrasadasSocios) * 100 / count($sociosActivos);

        //BUCLE PARA NUTRIR EL GRÁFICO DE LINEAS
        $fechaA =  new Carbon($fecha1);
        $fechaB = new Carbon($fecha1);
        $diff = $fechaA->diff($fecha2);
        if ($diff->days < 13){
            for($i=1;$i<13;$i++){
                $fechaB = $fechaB->addDays($i);
                $datos[] = array(
                    'fecha' => $fechaA->format('d-M-Y'),
                    'gastos' => abs(Cuenta::where('fechaApunte', '>=', $fechaA)
                        ->where('fechaApunte', '<', $fechaB)
                        ->where('tipo','=', 'Gasto')->get()->sum('cantidad')),
                    'ingresos' => Cuenta::where('fechaApunte', '>=', $fechaA)
                        ->where('fechaApunte', '<', $fechaB)
                        ->where('tipo','=', 'Ingreso')->get()->sum('cantidad'),
                );
                $fechaA = new Carbon($fechaB);
                if($fechaB->gt($fecha2)) {$fechaB = new Carbon($fecha2);$i=12;}
            }
        }
        else{

            $divi = ceil($diff->days/12);
            for($i=1;$i<13;$i++){
                $fechaB->addDays($divi);
                $datos[] = array(
                    'fecha' => $fechaA->format('d-M-Y'),
                    'fechaB' => $fechaB->format('d-M-Y'),
                    'gastos' => abs(Cuenta::where('fechaApunte', '>=', $fechaA)
                        ->where('fechaApunte', '<=', $fechaB)
                        ->where('tipo','=', 'Gasto')->get()->sum('cantidad')),
                    'ingresos' => Cuenta::where('fechaApunte', '>=', $fechaA)
                        ->where('fechaApunte', '<', $fechaB)
                        ->where('tipo','=', 'Ingreso')->get()->sum('cantidad'),
                );
                $fechaA = new Carbon($fechaB);
                if($fechaB->gt($fecha2)) {$fechaB = new Carbon($fecha2);$i=12;}
            }

        }
       // dd($datos, $divi, intdiv($diff->days,12));

        $ultimoAsiento = Cuenta::get()->last();
        if($ultimoAsiento == null){
            $ultimoAsiento = 0;
        }
        else{
            $ultimoAsiento = $ultimoAsiento->id;
        }

        $ConceptosAgrupados = DB::table('cuentas')->pluck('conceptoAgrupado');
        //dd($ConceptosAgrupados);
        $ConceptosAgrupados = $ConceptosAgrupados->tobase()->merge(Lang::get('text.conceptAgrupBunch'));

        $vocalias = DB::table('vocalias')->pluck('nombre');
        $vocalias = $vocalias->tobase()->merge(Lang::get('text.NoVocalia'));

        $socios = User::all();

        $sociosMant = count(User::where('habilitado', true)->get());
        $mantXsocio = Configura::getValor("CM");
        $totalMantenimiento = $sociosMant * $mantXsocio;

        $dia1mesAnt = new Carbon('first day of last month');
        $dia31mesAnt = new Carbon('last day of last month');
        $resultado= array(
            'request' => $request,
            'gastos' => $gastos,
            'ingresos' => $ingresos,
            'cuotas' => $cuotas,
            'total' => $ingresos + $gastos,
            'sociosActivos' => count($sociosActivos),
            'sociosNoActivos' => count($sociosNoActivos),
            'sociosCuotasAtrasadas' => count($cuotasAtrasadasSocios),
            'porctCuotasAtrasadas' => $porctCuotasAtrasadas,
            'fecha1' => $fecha1,
            'fecha2' => $fecha2,
            'hoy' => Carbon::now()->format('Y-m-d'),
            'dia1mes' => Carbon::now()->format('Y').Carbon::now()->format('m').'01',
            'dia1ano'  => Carbon::now()->format('Y')."01-01",
            'dia1mesAnt' => $dia1mesAnt->format('Y-m-d'),
            'dia31mesAnt' => $dia31mesAnt->format('Y-m-d'),
            'dia1eneroAnoAnt'  => Carbon::now()->addMonths(-12)->format('Y')."01-01",
            'dia31dicAnoAnt'  => Carbon::now()->addMonths(-12)->format('Y')."12-31",
            'datos' => $datos,
            'ultimoAsiento' => $ultimoAsiento + 1,
            'conceptosAgrupados' => $ConceptosAgrupados->unique(),
            'vocalias' => $vocalias->unique(),
            'socios' => $socios,
            'sociosMant' => $sociosMant,
            'mantenimiento' => $mantXsocio,
            'totalMantenimiento' => $totalMantenimiento,
            'tiposCuota' => TiposCuota::all(),
        );

        //return view('tesorero/tesoreroIndex', $resultado);
        return view('tesorero/tesorero', $resultado);
    }

                                                 ######
  ####  #    # ###### #    # #####   ##    ####  #     #   ##   #####   ##   #####   ##   #####  #      ######  ####
 #    # #    # #      ##   #   #    #  #  #      #     #  #  #    #    #  #    #    #  #  #    # #      #      #
 #      #    # #####  # #  #   #   #    #  ####  #     # #    #   #   #    #   #   #    # #####  #      #####   ####
 #      #    # #      #  # #   #   ######      # #     # ######   #   ######   #   ###### #    # #      #           #
 #    # #    # #      #   ##   #   #    # #    # #     # #    #   #   #    #   #   #    # #    # #      #      #    #
  ####   ####  ###### #    #   #   #    #  ####  ######  #    #   #   #    #   #   #    # #####  ###### ######  ####

    /**
     * DATATABLE
     * @return [type] [description]
     */
    public function cuentasDatatables($f1, $f2)
    {
        if($f1=='null'){$f1 = Lang::get('text.AsocCreatDate');}
        if($f2=='null'){$f2 = Carbon::now()->format('Y-m-d');}
        $fecha1 = new Carbon($f1);
        $fecha2 = new Carbon($f2);
        $listaCuentas = array();
        $cuentas=null;
        $cuentas = Cuenta::where('fechaApunte', '>=', $fecha1)
                    ->where('fechaApunte', '<=', $fecha2)
                    ->orderBy('fechaApunte')->get();
        if($cuentas!=null && count($cuentas)>0){
            foreach ($cuentas as $cuentas) {
                $listaCuentas[] = array(
                    'id' => $cuentas->id,
                    'fechaApunte' => $cuentas->fechaApunte->toDateString(),
                    'año' => $cuentas->año,
                    'tipo' => $cuentas->tipo,
                    'conceptoAgrupado' => $cuentas->conceptoAgrupado,
                    'detalle' => $cuentas->detalle,
                    'vocalia' => $cuentas->vocalia,
                    'cantidad' => $cuentas->cantidad,
                    'pagcob' => $cuentas->pagcob,
                    'notas' => $cuentas->notas,
                );
            }
        }
        return DataTables::of($listaCuentas)->make(true);
    }


  ####    ##    ####  #####  ####   ####          # #    #  ####  #####  ######  ####   ####   ####
 #    #  #  #  #        #   #    # #              # ##   # #    # #    # #      #      #    # #
 #      #    #  ####    #   #    #  ####          # # #  # #      #    # #####   ####  #    #  ####
 #  ### ######      #   #   #    #      #         # #  # # #  ### #####  #           # #    #      #
 #    # #    # #    #   #   #    # #    #         # #   ## #    # #   #  #      #    # #    # #    #
  ####  #    #  ####    #    ####   ####          # #    #  ####  #    # ######  ####   ####   ####
                                          #######
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function gastos_ingresos(Request $request, String $f1 = null, String $f2 = null)
    {
        $user = Auth::user();
        if($user == null){
            return view('welcome');
        }
        if(!($user->hasRole(['admin','secretario','tesorero']))){
            return view('prohibido');
        }

        if($f1 != null){
            $fecha1 =  new Carbon($f1);
        }else{
            $fecha1 = Carbon::now();
        }
        if($f2 != null){
            $fecha2 = new Carbon($f2);
        }else{
            $fecha2 = Carbon::createFromFormat('Y-m-d',Lang::get('text.AsocCreatDate'));
        }

        $ultimoAsiento = Cuenta::get()->last();
        if($ultimoAsiento == null){
            $ultimoAsiento = 0;
        }
        else{
            $ultimoAsiento = $ultimoAsiento->id;
        }

        $ConceptosAgrupados = DB::table('cuentas')->pluck('conceptoAgrupado');
        //dd($ConceptosAgrupados);
        $ConceptosAgrupados = $ConceptosAgrupados->tobase()->merge(Lang::get('text.conceptAgrupBunch'));

        $gastos = Cuenta::where('tipo','=', 'Gasto')->sum('cantidad');
        $ingresos = Cuenta::where('tipo','=', 'Ingreso')->sum('cantidad');

        $cuotas = Cuota::all()->sum('cantidad');

        $socios = User::all();

        $sociosMant = count(User::where('habilitado', true)->get());
        $mantXsocio = Configura::getValor("CM");
        $totalMantenimiento = $sociosMant * $mantXsocio;

        $vocalias = DB::table('vocalias')->pluck('nombre');
        $vocalias = $vocalias->tobase()->merge(['Sin Vocalía']);

        $sociosActivos = User::where('habilitado','=',true)->pluck('id');
        $cuotasSocActivos = Cuota::whereIn('idSocio', $sociosActivos)->get();
        $sociosCuotasAtrasadas = array();
        foreach ($cuotasSocActivos as $key => $value) {
            if($value->tipoCuota == 'anual'){
                if($value->fechaCuota < Carbon::now()->addMonths(-12)){
                    $sociosCuotasAtrasadas[] = $value;
                }
            }elseif($value->tipoCuota == 'semestral'){
                if($value->fechaCuota < Carbon::now()->addMonths(-6)){
                    $sociosCuotasAtrasadas[] = $value;
                }
            }elseif($value->tipoCuota == 'trimestral'){
                if($value->fechaCuota < Carbon::now()->addMonths(-3)){
                    $sociosCuotasAtrasadas[] = $value;
                }
            }elseif($value->tipoCuota == 'especial'){
                if($value->fechaCuota < Carbon::now()->addMonths(-3)){
                    $sociosCuotasAtrasadas[] = $value;
                }
            }
        }
        $resultado= array(
            'request' => $request,
            'gastos' => $gastos,
            'ingresos' => $ingresos,
            'cuotas' => $cuotas,
            'sociosCuotasAtrasadas' => count($sociosCuotasAtrasadas),
            'ultimoAsiento' => $ultimoAsiento + 1,
            'conceptosAgrupados' => $ConceptosAgrupados->unique(),
            'socios' => $socios,
            'vocalias' => $vocalias->unique(),
            'sociosMant' => $sociosMant,
            'mantenimiento' => $mantXsocio,
            'totalMantenimiento' => $totalMantenimiento,
        );
        return view('tesorero/gastos_ingresos', $resultado);
    }


                                                        ###
 ##### ######  ####   ####  #####  ###### #####   ####   #  #    # #####   ####  #####  #####
   #   #      #      #    # #    # #      #    # #    #  #  ##  ## #    # #    # #    #   #
   #   #####   ####  #    # #    # #####  #    # #    #  #  # ## # #    # #    # #    #   #
   #   #           # #    # #####  #      #####  #    #  #  #    # #####  #    # #####    #
   #   #      #    # #    # #   #  #      #   #  #    #  #  #    # #      #    # #   #    #
   #   ######  ####   ####  #    # ###### #    #  ####  ### #    # #       ####  #    #   #

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tesoreroImport(Request $request)
    {
        $result = Excel::import(new CuentasImport, $request->excel);
        //vuelvo a la pantalla con un mensaje OK
        Log::info(Lang::get('logtext.L_ImportExcel'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        $request->session()->flash('alert-info', Lang::get('errortext.OKimportacion'));
        return redirect()->route('tesorero', ['request' => $request]);
    }



  ####  #####  ######   ##   ##### ######
 #    # #    # #       #  #    #   #
 #      #    # #####  #    #   #   #####
 #      #####  #      ######   #   #
 #    # #   #  #      #    #   #   #
  ####  #    # ###### #    #   #   ######

    /**
     * CREA UN NUEVO APUNTE
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $fecha = Carbon::createFromFormat('Y-m-d', $request->fecha);

        if(preg_match( '/^[12][0-9]{3}$/', $request->year)){
            $anio = Carbon::now()->format('Y');
        }else{
            $anio = $request->year;
        }

        //Recogemos la cantidad, la pasamos a número con su formato correcto (positivo o negativo según sea gasto o ingreso)
        if($request->tipo == 'Gasto' && $request->cantidad < 0){
            $cantidad = $request->cantidad * (1);
        }elseif ($request->tipo == 'Gasto' && $request->cantidad > 0) {
            $cantidad = $request->cantidad * (-1);
        }else{
            $cantidad = $request->cantidad * (1);
        }

        $nuevoApunte = Cuenta::create([
            'fechaApunte' => $fecha,
            'año'=> $anio,
            'tipo'=> $request->tipo,
            'conceptoAgrupado' => $request->conceptoAgrupado,
            'detalle' => $request->descripcion,
            'vocalia' => $request->vocalia,
            'cantidad' => $cantidad,
            'notas' => $request->notas,
            'pagcob'=> $request->pagcob,
        ]);
        $nuevoApunte->save();
        Log::info(Lang::get('logtext.L_NewApunt'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        Log::info(Lang::get('logtext.L_ApuntName'). $nuevoApunte->aString());

        if($request->conceptoAgrupado == Lang::get('text.ConceptCuota')){
            $tiposCuota = TiposCuota::all();
            $tipoCuota = null;
            $aux = false;
            foreach($tiposCuota as $key=>$value){
                if($cantidad == $value->cantidad){
                    $aux = true;
                    $tipoCuota = $value;
                }
            }
            if($aux == false){
                //Por defecto se pondrá la cuota mensual si no encuentra una que cuadre
                $tipoCuota = $tiposCuota->where('meses',1)->first();
            }
            $cuota = Cuota::create([
                'idSocio' => $request->socioSelect,
                'tipoCuota' => $tipoCuota->id,
                'cantidad' => $cantidad,
				'idAsiento' => $nuevoApunte->id,
                'fechaCuota' => $fecha,
            ]);
            $nuevoApunte->descripcion = $nuevoApunte->descripcion . "Cuota " . $tipoCuota->nombre . " de socio: " . $request->socioSelect;
            $request->session()->flash('alert-info', Lang::get('errortext.OKNuevoApunteConCuota'));
            return $this->index($request);
            //return redirect()->route('tesorero', ['request' => $request]);
        }

        //vuelvo a la pantalla con un mensaje OK
        $request->session()->flash('alert-info', Lang::get('errortext.OKNuevoApunte'));
        //return redirect()->route('tesorero', $request);
        return $this->index($request);
    }



  ####  #    #  ####  #    #
 #      #    # #    # #    #
  ####  ###### #    # #    #
      # #    # #    # # ## #
 #    # #    # #    # ##  ##
  ####  #    #  ####  #    #

    /**
     * Descargar plantilla de importación.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $ruta = storage_path("app\public\documentos\Plantilla_Importacion.xlsx");
       // dd($ruta);
        //verificamos si el archivo existe y lo retornamos
        if (Storage::disk('documentos')->exists('Plantilla_Importacion.xlsx'))
        {
            $response = \Response::download($ruta);
            //Limpiamos caché para documentos, para que se descarguen correctamente
            ob_end_clean();
            return $response;
            //return response()->download($ruta);
        }
        else{
            $request->session()->flash('alert-danger', Lang::get('errortext.ERRORnoArchivo'));
        }
        return $this->index($request);
    }



 ###### #####  # #####
 #      #    # #   #
 #####  #    # #   #
 #      #    # #   #
 #      #    # #   #
 ###### #####  #   #
    /**
     * Editar un apunte.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Cuenta $apunte)
    {
        //dd($apunte);
        $ConceptosAgrupados = DB::table('cuentas')->pluck('conceptoAgrupado');
        //dd($ConceptosAgrupados);
        $ConceptosAgrupados = $ConceptosAgrupados->tobase()->merge(Lang::get('text.conceptAgrupBunch'));
        $socios = User::all();
        $vocalias = DB::table('vocalias')->pluck('nombre');
        $vocalias = $vocalias->tobase()->merge(['Sin Vocalía']);


        $resultado = array(
            'socios' => $socios,
            'conceptosAgrupados' => $ConceptosAgrupados->unique(),
            'vocalias' => $vocalias,
            'asiento' =>  $apunte,
        );
        return view('tesorero/editarApunte', $resultado);
    }



 #    # #####  #####    ##   ##### ######
 #    # #    # #    #  #  #    #   #
 #    # #    # #    # #    #   #   #####
 #    # #####  #    # ######   #   #
 #    # #      #    # #    #   #   #
  ####  #      #####  #    #   #   ######
    /**
     * Actualiza el apunte contable.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //dd($request);
        //Recogemos la cantidad, la pasamos a número con su formato correcto (positivo o negativo según sea gasto o ingreso)
        $apunte = Cuenta::where('id', $request->id)->first();
        //dd($apunte);
        $fecha = Carbon::createFromFormat('Y-m-d', $request->fecha);
        if($request->tipo == 'Gasto' && $request->cantidad < 0){
            $cantidad = $request->cantidad * (1);
        }elseif ($request->tipo == 'Gasto' && $request->cantidad > 0) {
            $cantidad = $request->cantidad * (-1);
        }else{
            $cantidad = $request->cantidad * (1);
        }
        if($request->year == null){
            $anio = Carbon::now()->format('Y');
        }else{
            $anio = $request->year;
        }
        Log::info(Lang::get('logtext.L_AsienUpdate'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        Log::info(Lang::get('logtext.L_NumAs').$apunte->id);
        Log::info(Lang::get('logtext.L_antes').$apunte->aString());
        $apunteBool = $apunte->update([
            'fechaApunte' => $fecha,
            'año'=> $anio,
            'tipo'=> $request->tipo,
            'conceptoAgrupado' => $request->conceptoAgrupado,
            'detalle' => $request->descripcion,
            'vocalia' => $request->vocalia,
            'cantidad' => $cantidad,
            'notas' => $request->notas,
            'pagcob'=> $request->pagcob,
        ]);
        Log::info(Lang::get('logtext.L_desp').$apunte->aString());

        if($request->conceptoAgrupado == Lang::get('text.ConceptCuota')){
            $tiposCuota = TiposCuota::all();
            $tipoCuota = null;
            $aux = false;
            foreach($tiposCuota as $key=>$value){
                if($cantidad == $value->cantidad){
                    $aux = true;
                    $tipoCuota = $value;
                }
            }
            if($aux == false){
                //Por defecto se pondrá la cuota mensual si no encuentra una que cuadre
                $tipoCuota = $tiposCuota->where('meses',1)->first();
            }
            $cuota = Cuota::create([
                'idSocio' => $request->socioSelect,
                'tipoCuota' => $tipoCuota->id,
                'cantidad' => $cantidad,
				'idAsiento' => $apunte->id,
                'fechaCuota' => $fecha,
            ]);
            $request->session()->flash('alert-info', Lang::get('errortext.OKNuevoApunteConCuota'));
            return redirect()->back();
        }

        if($apunte){
            $request->session()->flash('alert-info', Lang::get('errortext.OKNuevoApunte'));
            return redirect()->back();
        }else{
            $request->session()->flash('alert-info', Lang::get('errortext.ERRORNuevoApunte'));
            return redirect()->back();
        }
    }


  ####  #    #  ####  #####   ##    ####
 #    # #    # #    #   #    #  #  #
 #      #    # #    #   #   #    #  ####
 #      #    # #    #   #   ######      #
 #    # #    # #    #   #   #    # #    #
  ####   ####   ####    #   #    #  ####

    /**
     * Vista de la página CUOTAS
     * @return [type] [description]
     */
    public function cuotas(Request $request, $vt)
    {
        //dd($vt);
        if(!(Auth::user()->hasRole(['admin','secretario','tesorero']))){
            return view('prohibido');
        }

        if($vt == 'null' || $vt == false || $vt == "false"){
            $verTodasCuotasONo = Lang::get('text.verTodasCuotas');
            $vert = 0;
        }else{
            $verTodasCuotasONo = Lang::get('text.verHabilitadosCuotas');
            $vert = 1;
        }
        //dd($vt, $verTodasCuotasONo, $vert);

        $user = Auth::user();
        if($user == null){
            return view('welcome');
        }
        $socios = User::all()->toArray();

        $datos = array(
            'request' => $request,
            'tiposCuota' => TiposCuota::all(),
            'socios' => $socios,
            'verTodasCuotasONo' => $verTodasCuotasONo,
            'vert' => $vert,
        );
        //dd($datos, $vt);
        return view('tesorero/cuotas',$datos);
    }

                                     #####
 #    # #    # ###### #    #   ##   #     # #    #  ####  #####   ##
 ##   # #    # #      #    #  #  #  #       #    # #    #   #    #  #
 # #  # #    # #####  #    # #    # #       #    # #    #   #   #    #
 #  # # #    # #      #    # ###### #       #    # #    #   #   ######
 #   ## #    # #       #  #  #    # #     # #    # #    #   #   #    #
 #    #  ####  ######   ##   #    #  #####   ####   ####    #   #    #

     /**
     * Vista de la página CUOTAS
     * @return [type] [description]
     */
    public function nuevaCuota(Request $request)
    {
        $cantidad = $request->cantidadCuota;
        $tipoCuota = TiposCuota::where('id',$request->tipoCuota)->first();
        $fecha = Carbon::createFromFormat('d/m/Y',$request->fechaCuota);
        $socio = User::where('id',$request->socioSelect)->first();
        $apunte = Cuenta::create([
            'fechaApunte' => $fecha,
            'año'=> $fecha->format('Y'),
            'tipo'=> "Ingreso",
            'conceptoAgrupado' => "Ingreso cuotas",
            'detalle' => "Cuota " . $tipoCuota->nombre . " de socio: " . $socio->id,
            'vocalia' => "Sin Vocalía",
            'cantidad' => $tipoCuota->cantidad,
            'notas' => "",
            'pagcob'=> "Si",
        ]);
        $cuota = Cuota::create([
            'idSocio' => $socio->id,
            'tipoCuota' => $tipoCuota->id,
            'idAsiento' => $apunte->id,
            'cantidad' => $cantidad,
            'fechaCuota' => $fecha,
        ]);
        Log::info(Lang::get('logtext.L_cuotaYApunteNew'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        Log::info(Lang::get('logtext.L_NumCuota').$cuota->id.Lang::get('logtext.L_NumAs').$apunte->id);
        $request->session()->flash('alert-info', Lang::get('errortext.OKNuevoApunteConCuota'));
        return redirect()->back();
    }

                                          ######
  ####  #    #  ####  #####   ##    ####  #     #   ##   #####   ##   #####   ##   #####  #      ######
 #    # #    # #    #   #    #  #  #      #     #  #  #    #    #  #    #    #  #  #    # #      #
 #      #    # #    #   #   #    #  ####  #     # #    #   #   #    #   #   #    # #####  #      #####
 #      #    # #    #   #   ######      # #     # ######   #   ######   #   ###### #    # #      #
 #    # #    # #    #   #   #    # #    # #     # #    #   #   #    #   #   #    # #    # #      #
  ####   ####   ####    #   #    #  ####  ######  #    #   #   #    #   #   #    # #####  ###### ######

    /**
     * DATATABLE
     * @return [type] [description]
     */
    public function cuotasDatatable($vt = null)
    {
        if($vt == 'null' || $vt == false || $vt == 'false'){
            $vt = false;
        }
        else{
            $vt = true;
        }
        $listaCuotas = array();
        // VEMOS SOLO LAS CUOTAS DE LOS SOCIOS HABILITADOS Y SOLO LA ÚLTIMA CUOTA
         if($vt == false){
            $socios = User::where('habilitado',true)->get();
            foreach ($socios as $key => $socio) {
                $cuota = $socio->ultimaCuota();
                if($cuota != null){
                    $f = new Carbon($cuota->fechaCuota);
                    $r = $cuota->venceCuota();
                    if($r->lte(Carbon::now())){
                        $ren = 'Si';
                    }else{
                        $ren = 'No';
                    }
                    $listaCuotas[] = array(
                        "id" => $cuota->id,
                        "idSocio" => (User::where('id',$cuota->idSocio)->first())->dimeNombre(),
                        "idAsiento" => $cuota->idAsiento,
                        "tipoCuota" => TiposCuota::where('id',$cuota->tipoCuota)->first()->nombre,
                        "cantidad" => $cuota->cantidad,
                        "fechaCuota" => \Helper::dimeFecha($f->format('d'),$f->format('m'),$f->format('Y'),9,"-"),
                        "fechaRenovacion" => \Helper::dimeFecha($r->format('d'),$r->format('m'),$r->format('Y'),9,"-"),
                        "renueva" => $ren,
                    );
                }
            }
        }
        //VEMOS TODAS LAS CUOTAS
        else{
            $cuotas = Cuota::all();
            foreach ($cuotas as $cuota) {
                $f = new Carbon($cuota->fechaCuota);
                $r = $cuota->venceCuota();
                if($r->lte(Carbon::now())){
                    $ren = 'Si';
                }else{
                    $ren = 'No';
                }
                $listaCuotas[] = array(
                    "id" => $cuota->id,
                    "idSocio" => (User::where('id',$cuota->idSocio)->first())->dimeNombre(),
                    "idAsiento" => $cuota->idAsiento,
                    "tipoCuota" => $cuota->tipoCuota,
                    "cantidad" => $cuota->cantidad,
                    "fechaCuota" => \Helper::dimeFecha($f->format('d'),$f->format('m'),$f->format('Y'),9,"-"),
                    "fechaRenovacion" => \Helper::dimeFecha($r->format('d'),$r->format('m'),$r->format('Y'),9,"-"),
                    "renueva" => $ren,
                );
            }
        }
        //dd($listaCuotas);
        return DataTables::of($listaCuotas)->make(true);

    }

                               #####
 ###### #####  # #####   ##   #     # #    #  ####  #####   ##
 #      #    # #   #    #  #  #       #    # #    #   #    #  #
 #####  #    # #   #   #    # #       #    # #    #   #   #    #
 #      #    # #   #   ###### #       #    # #    #   #   ######
 #      #    # #   #   #    # #     # #    # #    #   #   #    #
 ###### #####  #   #   #    #  #####   ####   ####    #   #    #
    /**
     * Editar la cuota
     * @param mixed $id
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function editaCuota($id)
    {
        $c = Cuota::where('id', $id)->first();
        $socios = User::all();
        $socioEdit = User::where('id',$c->idSocio)->first();
        $datos = array(
            'cuota' => $c,
            'socios' => $socios,
            'tiposCuota' => TiposCuota::all(),
            'socioEdit' => $socioEdit,
        );
        return view('tesorero/modalEditaCuota',$datos);
    }

                                                           #####
   ##    ####  ##### #    #   ##   #      # ######   ##   #     # #    #  ####  #####   ##
  #  #  #    #   #   #    #  #  #  #      #     #   #  #  #       #    # #    #   #    #  #
 #    # #        #   #    # #    # #      #    #   #    # #       #    # #    #   #   #    #
 ###### #        #   #    # ###### #      #   #    ###### #       #    # #    #   #   ######
 #    # #    #   #   #    # #    # #      #  #     #    # #     # #    # #    #   #   #    #
 #    #  ####    #    ####  #    # ###### # ###### #    #  #####   ####   ####    #   #    #
    /**
     * Actualiza la cuota
     * @param Request $request
     * @return RedirectResponse
     * @throws RuntimeException
     * @throws BindingResolutionException
     */
    public function actualizaCuota(Request $request)
    {
        //dd($request);
        $cuota = Cuota::where('id', $request->id)->first();
        $asiento = Cuenta::where('id',$cuota->idAsiento)->first();
        $socio = User::where('id', $request->socioSelect)->first();
        $fecha = Carbon::createFromFormat('d/m/Y', $request->fechaCuota);

        //Actualizamos Cuota
        $cuota->idSocio = $socio->id;
        $cuota->tipoCuota = $request->tipoCuota;
        $cuota->cantidad = $request->cantidadCuota;
        $cuota->fechaCuota = $fecha;
        $cuota->save();

        //Actualizamos el Apunte asociado
        $asiento->detalle = "Cuota " . $request->tipoCuota . " de socio: " . $socio->dimeNombre();
        $asiento->cantidad = $request->cantidadCuota;
        $asiento->fechaApunte = $fecha;
        $asiento->save();
        Log::info(Lang::get('logtext.L_cuotaYApunteNew'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        Log::info(Lang::get('logtext.L_NumCuota').$cuota->id.Lang::get('logtext.L_NumAs').$apunte->id);

        $request->session()->flash('alert-info', Lang::get('errortext.OKCuotaActualizada'));
        return redirect()->back();
    }
                                         #####
 ###### #      # #    # # #    #   ##   #     # #    #  ####  #####   ##
 #      #      # ##  ## # ##   #  #  #  #       #    # #    #   #    #  #
 #####  #      # # ## # # # #  # #    # #       #    # #    #   #   #    #
 #      #      # #    # # #  # # ###### #       #    # #    #   #   ######
 #      #      # #    # # #   ## #    # #     # #    # #    #   #   #    #
 ###### ###### # #    # # #    # #    #  #####   ####   ####    #   #    #
    /**
     *
     * @param Request $request
     * @param mixed $id
     * @return RedirectResponse
     * @throws RuntimeException
     * @throws BindingResolutionException
     */
    public function eliminaCuota(Request $request,$id)
    {
        $cuota = Cuota::where('id', $id)->first();
        $asiento = Cuenta::where('id',$cuota->idAsiento)->first();

        $cuota->delete();
        $asiento->delete();

        Log::info(Lang::get('logtext.L_cuotaYApunteDel'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        Log::info(Lang::get('logtext.L_NumCuota').$cuota->id.Lang::get('logtext.L_NumAs').$apunte->id);

        $request->session()->flash('alert-info', Lang::get('errortext.OKCuotaEliminada'));
        return redirect()->back();
    }

                               #######                         #####
 ###### #    # #    # #   ##   #       #    #   ##   # #      #     # #    #  ####  #####   ##
 #      ##   # #    # #  #  #  #       ##  ##  #  #  # #      #       #    # #    #   #    #  #
 #####  # #  # #    # # #    # #####   # ## # #    # # #      #       #    # #    #   #   #    #
 #      #  # # #    # # ###### #       #    # ###### # #      #       #    # #    #   #   ######
 #      #   ##  #  #  # #    # #       #    # #    # # #      #     # #    # #    #   #   #    #
 ###### #    #   ##   # #    # ####### #    # #    # # ######  #####   ####   ####    #   #    #
    /**
     *
     * @param mixed $id
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function enviaEmailCuota($id)
    {
        $c = Cuota::where('id', $id)->first();
        if($c == null){
            $c = new Cuota(array(
                'id' => '',
                'fechaCuota' => '',
                'tipoCuota' => Lang::get('errortext.ERRORNoCuota'),
                'cantidad' => '0',
            ));
            $socioEdit = User::where('id',substr($id,4))->first();
        }else{
            $socioEdit = User::where('id',$c->idSocio)->first();
        }
        $datos = array(
            'cuota' => $c,
            'socio' => $socioEdit,
        );
        //dd($c, $datos);
        return view('tesorero/modalEnviaEmailCuota',$datos);
    }

                               #######
 ###### #    # #    # #   ##   #       #    #   ##   # #
 #      ##   # #    # #  #  #  #       ##  ##  #  #  # #
 #####  # #  # #    # # #    # #####   # ## # #    # # #
 #      #  # # #    # # ###### #       #    # ###### # #
 #      #   ##  #  #  # #    # #       #    # #    # # #
 ###### #    #   ##   # #    # ####### #    # #    # # ######
    /**
     * Enviar email generico por parte del tesorero
     * @param Request $request
     * @return RedirectResponse
     * @throws RuntimeException
     * @throws BindingResolutionException
     */
    public function enviaEmail(Request $request)
    {
        //dd($request);

        $cuota = Cuota::where('id', $request->id)->first();
        $socio = User::where('id',$request->idSocio)->first();
        $r = Carbon::now();

        $obj = new \stdClass();
        $obj->texto = $request->mensaje;
        $obj->plantilla = "Tesoreria Email";
        $obj->fecha = \Helper::dimeFecha($r->format('d'),$r->format('m'),$r->format('Y'),7,"-");
        $obj->emailSender = Auth::user()->email;
        $obj->sender = Auth::user()->dimenombre();
        $obj->receiver = $socio->dimeNombre();
        if($socio->sexo == 'varon'){
            $obj->estimad = "Estimado";
        }elseif($socio->sexo == 'mujer'){
            $obj->estimad = "Estimada";
        }else{
            $obj->estimad = "Estimade";
        }
        Mail::to($socio->email)->send(new Email($obj));
        Log::info(Lang::get('logtext.L_NewEmailTes'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        Log::info(Lang::get('logtext.L_EmailTo'). $socio->dimeNombre());

        $request->session()->flash('alert-info', Lang::get('errortext.OKemailEnviado'));
        return redirect()->back();
    }

                                                                    #####
 #####  ######  ####  #    #   ##   #####  # #      # #####   ##   #     #  ####   ####  #  ####
 #    # #      #      #    #  #  #  #    # # #      #   #    #  #  #       #    # #    # # #    #
 #    # #####   ####  ###### #    # #####  # #      #   #   #    #  #####  #    # #      # #    #
 #    # #           # #    # ###### #    # # #      #   #   ######       # #    # #      # #    #
 #    # #      #    # #    # #    # #    # # #      #   #   #    # #     # #    # #    # # #    #
 #####  ######  ####  #    # #    # #####  # ###### #   #   #    #  #####   ####   ####  #  ####
    /**
     *
     * @param Request $request
     * @param mixed $id
     * @return RedirectResponse
     * @throws RuntimeException
     * @throws BindingResolutionException
     */
    public function deshabilitaSocio(Request $request,$id)
    {
        Log::info(Lang::get('logtext.L_SocDisab'). Auth::user()->id.') '. Auth::user()->dimeNombre());

        $cuota = Cuota::where('id', $id)->first();
        $socio = User::where('id', $cuota->idSocio)->first();

        if($socio->habilitado == true){
            $socio->habilitado = false;
        }else{
            $socio->habilitado = true;
        }

        $socio->save();

        $request->session()->flash('alert-info', Lang::get('errortext.OKSocioDesHab'));
        return redirect()->back();
    }

                                                    #######
 #    #  ####   ####    ##   #      #   ##    ####     #    ######  ####   ####  #####  ###### #####   ####
 #    # #    # #    #  #  #  #      #  #  #  #         #    #      #      #    # #    # #      #    # #    #
 #    # #    # #      #    # #      # #    #  ####     #    #####   ####  #    # #    # #####  #    # #    #
 #    # #    # #      ###### #      # ######      #    #    #           # #    # #####  #      #####  #    #
  #  #  #    # #    # #    # #      # #    # #    #    #    #      #    # #    # #   #  #      #   #  #    #
   ##    ####   ####  #    # ###### # #    #  ####     #    ######  ####   ####  #    # ###### #    #  ####
    /**
     * Gestion de las vocalias
     * @param Request $request
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function vocaliasTesorero(Request $request)
    {
        $vocalias = Vocalia::all();
        $colores = array (
            "1" => "#7986cb",
            "2" => "#33b679",
            "3" => "#8e24aa",
            "4" => "#e67c73",
            "5" => "#f6c026",
            "6" => "#f5511d",
            "7" => "#039be5",
            "8" => "#616161",
            "9" => "#3f51b5",
            "10" => "#0b8043"
        );
        //dd($vocalias);
        //
        $resultado = array(
            'vocalias' => $vocalias,
            'request' => $request,
            'coloresValidos' => $colores,
        );
        //dd($resultado);
        return view('tesorero/vocalias',$resultado);
    }

                                           #     #                                             #######
  ####  #    #   ##   #####  #####    ##   #     #  ####   ####    ##   #      #   ##    ####     #    ######  ####   ####  #####  ###### #####   ####
 #    # #    #  #  #  #    # #    #  #  #  #     # #    # #    #  #  #  #      #  #  #  #         #    #      #      #    # #    # #      #    # #    #
 #      #    # #    # #    # #    # #    # #     # #    # #      #    # #      # #    #  ####     #    #####   ####  #    # #    # #####  #    # #    #
 #  ### #    # ###### #####  #    # ######  #   #  #    # #      ###### #      # ######      #    #    #           # #    # #####  #      #####  #    #
 #    # #    # #    # #   #  #    # #    #   # #   #    # #    # #    # #      # #    # #    #    #    #      #    # #    # #   #  #      #   #  #    #
  ####   ####  #    # #    # #####  #    #    #     ####   ####  #    # ###### # #    #  ####     #    ######  ####   ####  #    # ###### #    #  ####
    /**
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws RuntimeException
     * @throws BindingResolutionException
     */
    public function guardaVocaliasTesorero(Request $request)
    {
        //dd($request);
        Log::info(Lang::get('logtext.L_ActVocalia'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        $ids = $request->id;
        $nombre = $request->nombre;
        $desc = $request->desc;
        $inputImagenVocalia = $request->inputImagenVocalia;
        $selectColor = $request->selectColor;
        $idCalendario = $request->idCalendario;
        $presupuesto = $request->presupuesto;

        foreach ($ids as $key => $id) {
            $vocalia = Vocalia::where('id', $id)->first();
            if($inputImagenVocalia != null){
                if(array_key_exists($key, $inputImagenVocalia)){
                    if($request->hasFile('inputImagenVocalia')){
                        $imagenVocalia = $inputImagenVocalia[$key];
                        $ext = $imagenVocalia->extension();
                        $validExt = collect(['bmp', 'jpg', 'jpeg', 'png', 'BMP', 'JPG', 'JPEG', 'PNG', 'gif', 'GIF']);
                        if(!$validExt->contains($ext)){
                            $request->session()->flash('alert-info', Lang::get('errortext.ERRORnoImagen'));
                            return redirect()->back();
                        }else{
                            $imageOld = $vocalia->imagen;
                            $path = public_path() . "\images\\" . $imageOld;
                            File::delete($path);
                            $nombreFichero = $imagenVocalia->getClientOriginalName();
                            Image::make($imagenVocalia)->resize(null,340, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            })->save(public_path('/images/'.$nombreFichero));
                            Storage::disk('public')->delete('images/' . $nombreFichero);
                            $vocalia->imagen = $nombreFichero;
                        }
                    }
                }
            }
            $vocalia->nombre = $nombre[$key];
            $vocalia->descripcion = $desc[$key];
            $vocalia->color = $selectColor[$key];
            $vocalia->idCalendario = $idCalendario[$key];
            $vocalia->presupuesto = $presupuesto[$key];
            $vocalia->save();
        }


        $request->session()->flash('alert-info', Lang::get('errortext.OKVocaliaGuardada'));
        return redirect()->back();
    }

/****************************************************************************************************************************
 #######  ##     ## ######## ########  #### ########  ######
##     ## ##     ## ##       ##     ##  ##  ##       ##    ##
##     ## ##     ## ##       ##     ##  ##  ##       ##
##     ## ##     ## ######   ########   ##  ######    ######
##  ## ## ##     ## ##       ##   ##    ##  ##             ##
##    ##  ##     ## ##       ##    ##   ##  ##       ##    ##
 ##### ##  #######  ######## ##     ## #### ########  ######  */
/*****************************************************************************************************************************/


                                   #     #
 #####    ##   #####  ####   ####   #   #    ##   #    # #  ####
 #    #  #  #    #   #    # #        # #    #  #  ##   # # #    #
 #    # #    #   #   #    #  ####     #    #    # # #  # # #    #
 #    # ######   #   #    #      #   # #   ###### #  # # # #    #
 #    # #    #   #   #    # #    #  #   #  #    # #   ## # #    #
 #####  #    #   #    ####   ####  #     # #    # #    # #  ####

    /**
     * Querie de Datos por Año.
     *
     * @return \Illuminate\Http\Response
     */
    public function datosXanio(Request $request, $year = null)
    {
        $user = Auth::user();
        if($user == null){
            return view('welcome');
        }
        if($year == null){
            $year = Carbon::now()->format('Y');
        }
        return view('tesorero/datosXanio',['request' => $request, 'year' => $year]);
    }

                                   #     #                        ######
 #####    ##   #####  ####   ####   #   #    ##   #    # #  ####  #     #   ##   #####   ##   #####   ##   #####  #      ######
 #    #  #  #    #   #    # #        # #    #  #  ##   # # #    # #     #  #  #    #    #  #    #    #  #  #    # #      #
 #    # #    #   #   #    #  ####     #    #    # # #  # # #    # #     # #    #   #   #    #   #   #    # #####  #      #####
 #    # ######   #   #    #      #   # #   ###### #  # # # #    # #     # ######   #   ######   #   ###### #    # #      #
 #    # #    #   #   #    # #    #  #   #  #    # #   ## # #    # #     # #    #   #   #    #   #   #    # #    # #      #
 #####  #    #   #    ####   ####  #     # #    # #    # #  ####  ######  #    #   #   #    #   #   #    # #####  ###### ######
    /**
     *
     * @param string|null $f1
     * @return mixed
     * @throws Exception
     */
    public function datosXanioDatatable(String $f1 = null)
    {

        if($f1 != null){
            $fecha1 =  Carbon::createFromFormat('Y',$f1)->startOfYear();
        }else{
            $fecha1 = Carbon::now()->copy()->startOfYear();
        }
        $fecha2 = $fecha1->copy()->endOfYear();
        $listaCuentas = array();
        $cuentas=null;

        $cuentas = Cuenta::where('fechaApunte', '>', $fecha1)
                    ->where('fechaApunte', '<', $fecha2)
                    ->orderBy('fechaApunte')->get();
        if($cuentas!=null){
            foreach ($cuentas as $cuentas) {
                $listaCuentas[] = array(
                    'id' => $cuentas->id,
                    'fechaApunte' => $cuentas->fechaApunte->toDateString(),
                    'año' => $cuentas->año,
                    'tipo' => $cuentas->tipo,
                    'conceptoAgrupado' => $cuentas->conceptoAgrupado,
                    'detalle' => $cuentas->detalle,
                    'vocalia' => $cuentas->vocalia,
                    'cantidad' => $cuentas->cantidad,
                    'pagcob' => $cuentas->pagcob,
                    'notas' => $cuentas->notas,
                );
            }
        }else{
            if($listaCuentas == null){
                $nulo[] = array(
                    'id' => null,
                    'fechaApunte' => null,
                    'año' => null,
                    'tipo' =>null,
                    'conceptoAgrupado' => null,
                    'detalle' => null,
                    'vocalia' => null,
                    'cantidad' => null,
                    'pagcob' => null,
                    'notas' => null,
                );
                return DataTables::of($nulo)->make(true);
            }
        }
        return DataTables::of($listaCuentas)->make(true);
    }

                                                                                ######  ######  #######
 #    #   ##   #    # ##### ###### #    # # #    # # ###### #    # #####  ####  #     # #     # #
 ##  ##  #  #  ##   #   #   #      ##   # # ##  ## # #      ##   #   #   #    # #     # #     # #
 # ## # #    # # #  #   #   #####  # #  # # # ## # # #####  # #  #   #   #    # ######  #     # #####
 #    # ###### #  # #   #   #      #  # # # #    # # #      #  # #   #   #    # #       #     # #
 #    # #    # #   ##   #   #      #   ## # #    # # #      #   ##   #   #    # #       #     # #
 #    # #    # #    #   #   ###### #    # # #    # # ###### #    #   #    ####  #       ######  #
    /**
     * Querie de Datos por Año.
     *
     * @return \Illuminate\Http\Response
     */
    public function mantenimientoPDF()
    {
        $user = Auth::user();
        Log::info(Lang::get('logtext.L_ImpMant'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        if($user == null){
            return view('welcome');
        }
        $fecha = Carbon::now();
        $sociosMant = count(User::where('habilitado', true)->get());
        $mantXsocio = Configura::getValor("CM");
        $totalMantenimiento = $sociosMant * $mantXsocio;
        $tesorero = User::Role('tesorero')->get()->first();
        if($tesorero == null){
            $tesorero = Auth::user();
        }
        $datos = array(
            "mes" => \Helper::dimeFechaCarbon($fecha,3,"-"),
            "socios" => $sociosMant,
            "cuotaMant" => $mantXsocio,
            "cantidad" => $totalMantenimiento,
            "fecha" =>\Helper::dimeFechaCarbon($fecha,7,"-"),
            "tesorero" => $tesorero->nombre . " " . $tesorero->primerApellido . " " . $tesorero->segundoApellido,
            "logo" => \Helper::logoBase64(),
        );
        $pdf = PDF::loadView('pdf.mantenimiento', $datos);
        return $pdf->download(\Helper::dimeFechaCarbon($fecha,9,"-").'_Informe de mantenimiento.pdf');
    }

                                            #####
   ##   #####  #        ##   ######   ##   #     # #    #  ####  #####   ##    ####
  #  #  #    # #       #  #      #   #  #  #       #    # #    #   #    #  #  #
 #    # #    # #      #    #    #   #    # #       #    # #    #   #   #    #  ####
 ###### #####  #      ######   #    ###### #       #    # #    #   #   ######      #
 #    # #      #      #    #  #     #    # #     # #    # #    #   #   #    # #    #
 #    # #      ###### #    # ###### #    #  #####   ####   ####    #   #    #  ####
    /**
     * Querie de Datos por Año.
     *
     * @return \Illuminate\Http\Response
     */
    public function aplazaCuotas(Request $request)
    {
        //dd($request);
        if(!isset($request->checkTodosSocios)){
            $socios = User::where('habilitado',true)->get();
        }
        else{
           if(!isset($request->socioSelect)){
                foreach ($request->socioSelect as $key => $value) {
                    $socios[] = User::where('id',$value)->first();
                }
           }else{
                //No ha seleccionado ningun socio
                $request->session()->flash('alert-danger', Lang::get('errortext.ERRORAplazamientoNoSocioSel'));
                return redirect()->back();
           }
        }

        if($request->inputMesesAplazamiento == 0){
            //Ha seleccionado días sueltos
            Log::info(Lang::get('logtext.L_AplCuot'). Auth::user()->id.') '. Auth::user()->dimeNombre());
            $dias = $request->diasSueltosInput;
            foreach ($socios as $key => $socio) {
                $cuota = Cuota::where('idSocio',$socio->id)->get()->last();
                if($cuota != null){
                    Log::info('Socio:'.$socio->id . " ANTES: " . \Helper::dimeFechaCarbon($cuota->fechaCuota,5,"-") . "  / DESPUES: ". \Helper::dimeFechaCarbon((new Carbon($cuota->fechaCuota))->add($dias, 'days'),5,"-"));
                    $cuota->fechaCuota = (new Carbon($cuota->fechaCuota))->addDays($dias);
                    $cuota->save();
                }
            }
        }else{
            $meses = $request->inputMesesAplazamiento;
            Log::info(Lang::get('logtext.L_AplCuotMes'). Auth::user()->dimeNombre());
            foreach ($socios as $key => $socio) {
                $cuota = Cuota::where('idSocio',$socio->id)->get()->last();
                if($cuota != null){
                    Log::info(Lang::get('logtext.L_SocName').$socio->id . Lang::get('logtext.L_antes') . \Helper::dimeFechaCarbon($cuota->fechaCuota,5,"-") . "  /  ". Lang::get('logtext.L_desp'). \Helper::dimeFechaCarbon((new Carbon($cuota->fechaCuota))->addMonths($meses),5,"-"));
                    $cuota->fechaCuota = (new Carbon($cuota->fechaCuota))->addMonths($meses);
                    $cuota->save();
                }
            }
        }


        $request->session()->flash('alert-info', Lang::get('errortext.OKAplazamientoCuotas'));
        return redirect()->back();
    }

                                             ######                       #######
 # #    # ######  ####  #####  #    # ###### #     # ###### #####   ####     #    ######  ####   ####  #####  ###### #####   ####
 # ##   # #      #    # #    # ##  ## #      #     # #      #    # #         #    #      #      #    # #    # #      #    # #    #
 # # #  # #####  #    # #    # # ## # #####  ######  #####  #    #  ####     #    #####   ####  #    # #    # #####  #    # #    #
 # #  # # #      #    # #####  #    # #      #       #      #####       #    #    #           # #    # #####  #      #####  #    #
 # #   ## #      #    # #   #  #    # #      #       #      #   #  #    #    #    #      #    # #    # #   #  #      #   #  #    #
 # #    # #       ####  #    # #    # ###### #       ###### #    #  ####     #    ######  ####   ####  #    # ###### #    #  ####
    /**
     * Crea informes personalizados para el tesorero
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function informePersTesorero (Request $request){
        //dd($request);

        $user = Auth::user();
        Log::info(Lang::get('logtext.L_InforPers'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        if($user == null){
            return view('welcome');
        }
        $fecha = Carbon::now();
        $tesorero = User::Role('tesorero')->get()->first();
        if($tesorero == null){
            $tesorero = Auth::user();
        }
        $datos = array(
            "fecha" => \Helper::dimeFechaCarbon($fecha,7,"-"),
            "tesorero" => $tesorero->dimeNombre(),
            "texto" => $request->editordata,
            "nombreInf" => $request->nombreInf,
            "logo" => \Helper::logoBase64(),
        );
        $pdf = PDF::loadView('pdf.informePers', $datos);
        return $pdf->download(\Helper::dimeFechaCarbon($fecha,9,"-").'_Informe '.Lang::get('text.nombreAsoc').'.pdf');
    }

}
