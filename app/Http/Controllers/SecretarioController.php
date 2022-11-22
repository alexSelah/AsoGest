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
use App\Votacion;
use App\Propuesta;
use App\Invitaciones;
use App\Calendario;
use App\Vocalia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;
use App\Configura;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SociosImport;
use App\TiposCuota;
use Log;


class SecretarioController extends Controller
{
    /*
    #### ##    ## ########  ######## ##     ##
     ##  ###   ## ##     ## ##        ##   ##
     ##  ####  ## ##     ## ##         ## ##
     ##  ## ## ## ##     ## ######      ###
     ##  ##  #### ##     ## ##         ## ##
     ##  ##   ### ##     ## ##        ##   ##
    #### ##    ## ########  ######## ##     ##
    */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	$user = Auth::user();
        if($user == null){
            return view('welcome');
        }

        if(!(Auth::user()->hasRole(['admin','secretario','tesorero']))){
            return view('prohibido');
        }

         $numerosSocios = User::all()->pluck('numSocio')->toArray();
         $lastNumSocio = max($numerosSocios);
         //dd($lastNumSocio);
         $resultado = array(
            'ultimoNumSocio' => $lastNumSocio+1,
            'request' => $request,
         );
    	 return view('secretario/secretarioIndex', $resultado);
    }

    /*
    .dP"Y8  dP"Yb   dP""b8 88  dP"Yb  .dP"Y8 8888b.  888888 888888
    `Ybo." dP   Yb dP   `" 88 dP   Yb `Ybo."  8I  Yb   88     88
    o.`Y8b Yb   dP Yb      88 Yb   dP o.`Y8b  8I  dY   88     88
    8bodP'  YbodP   YboodP 88  YbodP  8bodP' 8888Y"    88     88
    */
    /**
     * DATATABLE
     * @return [type] [description]
     */
    public function sociosDatatables(){

        $socios = User::all();
        foreach ($socios as $socio) {
            if($socio->habilitado){
                $habilit = "Si";
            }
            else{
                $habilit = "No";
            }
            if($socio->bajaSocio != null){
                $bajaSocio = $socio->bajaSocio->format('d/m/Y');
            }else{
                $bajaSocio = "";
            }
            if($socio->foto == null || $socio->foto == ""){
                if($socio->sexo == "mujer"){
                    $foto = asset('images/fotos/')."/anonimoMujer.png";
                }else{
                    $foto = asset('images/fotos/')."/anonimoVaron.png";
                }
            }else{
                $foto = asset('images/fotos/')."/".$socio->foto;
            }
            $Listasocios[] = array(
                'foto' => $foto,
                'id' => $socio->id,
                'numSocio' => $socio->numSocio,
                'alta' => \Helper::dimeFechaCarbon($socio->altaSocio,9,"-"),
                'baja' => $bajaSocio,
                'nombre' => $socio->nombre,
                'primerApellido' => $socio->primerApellido,
                'segundoApellido' => $socio->segundoApellido,
                'fnacimiento' => \Helper::dimeFechaCarbon($socio->fnacimiento,9,"-"),
                'DNI' => $socio->DNI,
                'email' => $socio->email,
                'telefono' => $socio->telefono,
                'habilitado' => $habilit,
                'direccion' => $socio->direccion,
                'localidad' => $socio->localidad,
                'provincia' => $socio->provincia,
                'username' => $socio->username,
                'notas' => $socio->notas,
                'sexo' => $socio->sexo,
                'accs1' => $socio->accesoDrive,
                'accs2' => $socio->accesoJunta,
            );
        }
        //dd($Listasocios);
        return DataTables::of($Listasocios)->make(true);

    }

    /*
    ######## ####  ######  ##     ##    ###     ######   #######   ######  ####  #######  ##    ## ##     ## ######## ##     ##    ###
    ##        ##  ##    ## ##     ##   ## ##   ##    ## ##     ## ##    ##  ##  ##     ## ###   ## ##     ## ##       ##     ##   ## ##
    ##        ##  ##       ##     ##  ##   ##  ##       ##     ## ##        ##  ##     ## ####  ## ##     ## ##       ##     ##  ##   ##
    ######    ##  ##       ######### ##     ##  ######  ##     ## ##        ##  ##     ## ## ## ## ##     ## ######   ##     ## ##     ##
    ##        ##  ##       ##     ## #########       ## ##     ## ##        ##  ##     ## ##  #### ##     ## ##        ##   ##  #########
    ##        ##  ##    ## ##     ## ##     ## ##    ## ##     ## ##    ##  ##  ##     ## ##   ### ##     ## ##         ## ##   ##     ##
    ##       ####  ######  ##     ## ##     ##  ######   #######   ######  ####  #######  ##    ##  #######  ########    ###    ##     ##
    */
    /**
     * OBSOLETA
     * @param  Request $request [description]
     * @param  string  $idSocio [description]
     * @return [type]           [description]
     */
    public function fichaSocioNueva(Request $request, $idSocio = '1')
    {
        $roles = "socio";
        $rutaFoto = "anonimoVaron.png";
        $as = array([
            'idSocio' => $idSocio,
            'idVocalia' => Vocalia::first()->id
            ]);
        $user = array(
            'id' => $idSocio,
            'numSocio' => $idSocio,
            'nombre' => "",
            'primerApellido' => "",
            'segundoApellido' => "",
            'DNI' => "",
            'email' => "",
            'telefono' => "",
            'habilitado' => "",
            'direccion' => "",
            'localidad' => "",
            'provincia' => "",
            'codPostal' => 28400,
            'fnacimiento' => Carbon::now(),
            'username' => "",
            'notas' => "",
            'sexo' => "nodefinido",
            'password' =>bcrypt("Temporal*1"),
            'cuotaTipo' => 'anual',
            'cuotaCantidad' => '120',
            'cuotaFecha' => Carbon::now(),
            'altaSocio' => Carbon::now(),
            'accesoDrive' => false,
            'accesoJunta' => false,
            'habilitado' => true,
            'foto' => "",
            'invitaciones' => 0,
            'recibirCorreos' => 1,
            'privacidad' => 1,
            'bajaSocio' => null,
        );
        $cuota = array(
                'idSocio' => $idSocio,
                'tipoCuota' => TiposCuota::first()->getNombre(),
                'cantidad' => TiposCuota::first()->getCantidad(),
                'fechaCuota' => Carbon::now(),
            );
        $vocalias=Vocalia::all()->toArray();
        $noCuota = true;
        $cuota = collect([
            'idSocio' => $idSocio,
            'idAsiento' => 0,
            'tipoCuota' => TiposCuota::first()->getNombre(),
            'cantidad' => TiposCuota::first()->getCantidad(),
            'fechaCuota' => Carbon::now(),
        ]);
        $posiblesRoles = Role::all();
        $eventosdelsocio = null;
        $resultado = array(
            'posiblesRoles' => $posiblesRoles,
            'vocaliaSelect' => null,
            'fechaRenovacionCuota' => Carbon::now()->addMonths(12),
            'veterano' => false,
            'eventosCal' => $eventosdelsocio,
            'request' => $request,
            'usuario' => $user,
            'foto' => $rutaFoto,
            'as' => $as,
            'roles' => $roles,
            'cuota' => $cuota,
            'fechaRenovacionCuota' => Carbon::now(),
            'veterano' => false,
            'vocalias' => $vocalias,
            'noCuota' => $noCuota,
            'cuota' => $cuota,
        );
        //dd($resultado);
        return view('fichaSocio/gestionficha', $resultado);
    }

    /*
     ######  ########  ########    ###    ######## ########
    ##    ## ##     ## ##         ## ##      ##    ##
    ##       ##     ## ##        ##   ##     ##    ##
    ##       ########  ######   ##     ##    ##    ######
    ##       ##   ##   ##       #########    ##    ##
    ##    ## ##    ##  ##       ##     ##    ##    ##
     ######  ##     ## ######## ##     ##    ##    ########
    */
    /**
     * Creación rápida de usuario.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = User::create([
            'numSocio' => $request->inputNumSocio,
            'altaSocio' => Carbon::now(),
            'nombre' => $request->inputNombre,
            'primerApellido' => $request->input1apellido,
            'segundoApellido' => $request->input2apellido,
            'DNI' => $request->inputDNI,
            'sexo' => "nodefinido",
            'fnacimiento' => Carbon::createFromFormat('Y-m-d', $request->inputFnacimiento),
            'email' => $request->inputEmail,
            'telefono' => $request->inputTelefono,
            'direccion' => "",
            'localidad' => "",
            'provincia' => "",
            'codPostal' => 00000,
            'username' => $request->inputUsername,
            'notas' => "",
            'password' => bcrypt($request->passwordText),
            'invitaciones' => '1',
            'habilitado' => true,
            'accesoDrive' => false,
            'accesoJunta' => false,
            'recibirCorreos' => true,
            'privacidad' => true,
            'foto' => "",
        ]);
        Log::info(Lang::get('logtext.L_UsCreatRap'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        Log::info(Lang::get('logtext.L_UsName'). $user->aString());
        $user->save();

        //Le asigno el Rol
        $user->assignRole('socio');

        //Le pongo sus asignaciones de cuota
        //$asignacionSocio = "todos";
        $asig = AsignacionSocio::where('idSocio', $user->id)->first();
        if ($asig == null){
            foreach(Vocalia::all() as $vocalia)
            {
                $asig = AsignacionSocio::create([
                    'idSocio' => $user->id,
                    'idVocalia' => $vocalia->id,
                ]);
            }
        }

        if($request->cuotaAnual == true){
            $cuota = Cuota::create([
                'idSocio' => $user->id,
                'tipoCuota' => TiposCuota::first()->getNombre(),
                'cantidad' => TiposCuota::first()->getCantidad(),
                'fechaCuota' => Carbon::createFromFormat('d/m/Y', Carbon::now()),
            ]);
        }

        //vuelvo a la pantalla con un mensaje OK
        $request->session()->flash('alert-info', Lang::get('errortext.OKguardado'));
        return $this->index($request);
    }

    /*
    .dP"Y8 888888  dP""b8 88""Yb 888888 888888    db    88""Yb 88  dP"Yb  88 8b    d8 88""Yb  dP"Yb  88""Yb 888888
    `Ybo." 88__   dP   `" 88__dP 88__     88     dPYb   88__dP 88 dP   Yb 88 88b  d88 88__dP dP   Yb 88__dP   88
    o.`Y8b 88""   Yb      88"Yb  88""     88    dP__Yb  88"Yb  88 Yb   dP 88 88YbdP88 88"""  Yb   dP 88"Yb    88
    8bodP' 888888  YboodP 88  Yb 888888   88   dP""""Yb 88  Yb 88  YbodP  88 88 YY 88 88      YbodP  88  Yb   88
    */
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function secretarioImport(Request $request)
    {
        $result = Excel::import(new SociosImport, $request->excel);
        //vuelvo a la pantalla con un mensaje OK
        Log::info(Lang::get('logtext.L_ImportMasUs'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        $request->session()->flash('alert-info', Lang::get('errortext.OKimportacion'));
        return redirect()->route('secretario', ['request' => $request]);
    }

    /*
    8888b.  888888 .dP"Y8  dP""b8    db    88""Yb  dP""b8    db    888888 Yb  dP  dP""b8 888888 88     .dP"Y8  dP"Yb   dP""b8 88  dP"Yb  .dP"Y8
     8I  Yb 88__   `Ybo." dP   `"   dPYb   88__dP dP   `"   dPYb   88__    YbdP  dP   `" 88__   88     `Ybo." dP   Yb dP   `" 88 dP   Yb `Ybo."
     8I  dY 88""   o.`Y8b Yb       dP__Yb  88"Yb  Yb  "88  dP__Yb  88""    dPYb  Yb      88""   88  .o o.`Y8b Yb   dP Yb      88 Yb   dP o.`Y8b
    8888Y"  888888 8bodP'  YboodP dP""""Yb 88  Yb  YboodP dP""""Yb 888888 dP  Yb  YboodP 888888 88ood8 8bodP'  YbodP   YboodP 88  YbodP  8bodP'
    */
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function descargaExcelSocios(Request $request)
    {
        $ruta = storage_path("app\public\documentos\Plantilla_Importacion_Socios.xlsx");
       // dd($ruta);
        //verificamos si el archivo existe y lo retornamos
        if (Storage::disk('documentos')->exists('Plantilla_Importacion_Socios.xlsx'))
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

    /*
    ########  ########  ######  ######## ########   #######  ##    ##
    ##     ## ##       ##    ##    ##    ##     ## ##     ##  ##  ##
    ##     ## ##       ##          ##    ##     ## ##     ##   ####
    ##     ## ######    ######     ##    ########  ##     ##    ##
    ##     ## ##             ##    ##    ##   ##   ##     ##    ##
    ##     ## ##       ##    ##    ##    ##    ##  ##     ##    ##
    ########  ########  ######     ##    ##     ##  #######     ##
    */
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        if(Auth::user()->hasRole(['secretario','admin'])){
            $socio = User::where('id',$id)->first();
            $cuotas = Cuota::where('idSocio',$id)->get();
            if(count($cuotas) >0){
                foreach ($cuotas as $key => $value) {
                    $value->delete();
                }
            }
            $asignaciones = AsignacionSocio::where('idSocio',$id)->get();
            if(count($asignaciones) >0){
                foreach ($asignaciones as $key => $value) {
                    $value->delete();
                }
            }
            $invitaciones = Invitaciones::where('idSocio',$id)->get();
            if(count($invitaciones) >0){
                foreach ($invitaciones as $key => $value) {
                $value->delete();
                }
            }
            $propuestas = Propuesta::where('idSocio',$id)->get();
            if(count($propuestas) >0){
                foreach ($propuestas as $key => $value) {
                    $value->delete();
                }
            }
            $votaciones = Votacion::where('idSocio',$id)->get();
            if(count($votaciones) >0){
                foreach ($votaciones as $key => $value) {
                    $value->delete();
                }
            }
            $eventos = Calendario::where('idSocio',$id)->get();
            if(count($eventos) >0){
                foreach ($eventos as $key => $value) {
                    $value->delete();
                }
            }
            $socio->delete();
            $request->session()->flash('alert-success', Lang::get('errortext.OKSocioBorrado'));
            return $this->index($request);

        }else{
            $request->session()->flash('alert-danger', Lang::get('errortext.ERRORNoAutorizado'));
            return $this->index($request);
        }
    }


}
