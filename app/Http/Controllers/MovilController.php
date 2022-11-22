<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\GoogleCalendar\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use App\Charts\VotacionesChart;
use App\Mail\Email;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use yajra\Datatables\Datatables;
use App\Cuenta;
use App\Cuota;
use App\Configura;
use App\Propuesta;
use App\Compra;
use App\Votacion;
use App\AsignacionSocio;
use App\Vocalia;
use App\User;
use App\Calendario;
use App\Documento;
use App\Invitaciones;
use App\TiposCuota;
use Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class MovilController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
    	//dd($request,$idSocio,$idVocalia);
        $user = Auth::user();
        if($user == null){
            return view('welcome');
        }

    	return view('movil/homeMovil');
    }

    /*
    ########     ###     ######         ##
    ##     ##   ## ##   ##    ##      ####
    ##     ##  ##   ##  ##              ##
    ########  ##     ## ##   ####       ##
    ##        ######### ##    ##        ##
    ##        ##     ## ##    ##        ##
    ##        ##     ##  ######       ######
    */
    public function pag1(Request $request, $idSocio=null)
    {
         if($idSocio != null && Auth::user()->hasRole(['admin','secretario','tesorero'])){
            $user = User::where('id',$idSocio)->first();
            if($user == null){
                $request->session()->flash('alert-danger', Lang::get('errortext.ERRORnoSocio'));
                return redirect()->route('movil')->with([null,null]);
            }
        }
        else{
            $user = Auth::user();
            $idSocio = Auth::user()->id;
        }
        if($user == null){
            return view('welcome');
        }
        if(!(Auth::user()->hasRole(['admin','secretario','tesorero'])) && $idSocio != Auth::user()->id){
            return view('prohibido');
        }
        $asignacionSocio = AsignacionSocio::where('idSocio', $user->id)->first();
        $roles = $user->where('id', $idSocio)->first()->getRoleNames()->first();
        if($roles == 'vocal'){
            $vocaliaSelect = substr($user->permissions->first()->name, 16);
        }else{
            $vocaliaSelect = null;
        }
        $posiblesRoles = Role::all();
        if($user->foto == null || $user->foto ==""){
            if($user->sexo == "mujer"){
                $rutaFoto = "defaultmujer".rand(1,9).".jpg";
            }
            else{
                $rutaFoto = "default".rand(1,9).".jpg";
            }
        }
        else{
            $rutaFoto = $user->foto;
        }
        $as = $user->asignaciones()->get();
        $vocalias=Vocalia::all();
        $cuota = Cuota::where('idSocio',$user->id)->latest()->first();
        $noCuota = false;
        if($cuota == null){
            $noCuota = true;
            $cuota = collect([
                'idSocio' => $user->id,
                'idAsiento' => 0,
                'tipoCuota' => 'especial',
                'cantidad' => '0',
                'fechaCuota' => Carbon::now(),
            ]);
        }
        if($noCuota == false){
            $renovacionCuota = $cuota->venceCuota();
        }else{
            $renovacionCuota = Carbon::now();
        }

        if($user->altaSocio->lte(Carbon::now()->addMonths(-12))){
            $veretaro = Lang::get('text.si');
        }else{
            $veretaro = Lang::get('text.no');
        }

        $resultado = array(
            'request' => $request,
            'usuario' => $user,
            'foto' => $rutaFoto,
            'as' => $as,
            'roles' => $roles,
            'posiblesRoles' => $posiblesRoles,
            'vocaliaSelect' => $vocaliaSelect,
            'cuota' => $cuota,
            'fechaRenovacionCuota' => $renovacionCuota,
            'veterano' => $veretaro,
            'vocalias' => $vocalias,
            'noCuota' => $noCuota,
            'tiposCuota' => TiposCuota::all(),
        );
        //dd($resultado);
        return view('movil/pag1', $resultado);

    }

    /*
    ########     ###     ######    #######
    ##     ##   ## ##   ##    ##  ##     ##
    ##     ##  ##   ##  ##               ##
    ########  ##     ## ##   ####  #######
    ##        ######### ##    ##  ##
    ##        ##     ## ##    ##  ##
    ##        ##     ##  ######   #########
    */
    public function pag2(Request $request)
    {

        return view('movil/pag2', ['cal4' => Configura::getValor('CAL4')]);

    }

    /*
    ########     ###     ######    #######
    ##     ##   ## ##   ##    ##  ##     ##
    ##     ##  ##   ##  ##               ##
    ########  ##     ## ##   ####  #######
    ##        ######### ##    ##         ##
    ##        ##     ## ##    ##  ##     ##
    ##        ##     ##  ######    #######
    */
    public function pag3()
    {
        $socios = User::where('habilitado',true)->where('privacidad',true)->get();
        $vocalias = DB::table('vocalias')->pluck('nombre');
        $vocalias = $vocalias->tobase()->merge(['Evento de Asociación']);


        $resultado = array(
            'vocalias' => $vocalias,
            'socios' => $socios,
        );


        return view('movil/pag3', $resultado);
    }

    /*
    ########     ###     ######   ########
    ##     ##   ## ##   ##    ##  ##
    ##     ##  ##   ##  ##        ##
    ########  ##     ## ##   #### #######
    ##        ######### ##    ##        ##
    ##        ##     ## ##    ##  ##    ##
    ##        ##     ##  ######    ######
    */
    public function pag5()
    {
        $vocalias = Vocalia::all()->toArray();
        return view('movil/pag5', ['vocalias' => $vocalias]);
    }


    /*
    ########     ###     ######    #######
    ##     ##   ## ##   ##    ##  ##     ##
    ##     ##  ##   ##  ##        ##
    ########  ##     ## ##   #### ########
    ##        ######### ##    ##  ##     ##
    ##        ##     ## ##    ##  ##     ##
    ##        ##     ##  ######    #######
    */
    public function pag6()
    {
        //DATOS PARA LOS GRUPOS DE CHAT
        $gchat = Configura::getValor('CHATS');
        $stringSeparado = explode('>', $gchat);
        $grupochat = null;
        $i = 0;
        $j = count($stringSeparado);
        while ($i <= $j)
        {
            if($i+2 >= $j){break;}
            $grupochat[] = array(
                "nombre" => $stringSeparado[$i],
                "desc" => $stringSeparado[$i+1],
                "URL" => $stringSeparado[$i+2]
            );
            $i=$i+3;
        }
        return view('movil/pag6', ['grupochat' => $grupochat]);
    }

    /*
    ########     ###     ######   ########
    ##     ##   ## ##   ##    ##  ##    ##
    ##     ##  ##   ##  ##            ##
    ########  ##     ## ##   ####    ##
    ##        ######### ##    ##    ##
    ##        ##     ## ##    ##    ##
    ##        ##     ##  ######     ##
    */
    public function pag7()
    {
        $ultimoAsiento = Cuenta::get()->last();
        if($ultimoAsiento == null){
            $ultimoAsiento = 0;
        }
        else{
            $ultimoAsiento = $ultimoAsiento->id;
        }
        $socios = User::all();

        $ConceptosAgrupados = DB::table('cuentas')->pluck('conceptoAgrupado');
        $ConceptosAgrupados = $ConceptosAgrupados->tobase()->merge(Lang::get('text.conceptAgrupBunch'));

        $vocalias = DB::table('vocalias')->pluck('nombre');
        $vocalias = $vocalias->tobase()->merge(Lang::get('text.NoVocalia'));

        $resultado= array(
            'ultimoAsiento' => $ultimoAsiento + 1,
            'socios' => $socios,
            'conceptosAgrupados' => $ConceptosAgrupados->unique(),
            'vocalias' => $vocalias,

        );

        return view('movil/pag7', $resultado);
    }

    /*
    ########     ###     ######    #######
    ##     ##   ## ##   ##    ##  ##     ##
    ##     ##  ##   ##  ##        ##     ##
    ########  ##     ## ##   ####  #######
    ##        ######### ##    ##  ##     ##
    ##        ##     ## ##    ##  ##     ##
    ##        ##     ##  ######    #######
    */
    public function pag8()
    {
        $numerosSocios = User::all()->pluck('numSocio')->toArray();
         $lastNumSocio = max($numerosSocios);
         //dd($lastNumSocio);
         $resultado = array(
            'ultimoNumSocio' => $lastNumSocio+1,
         );
        return view('movil/pag8', $resultado);
    }

    /*
    ########     ###     ######    #######
    ##     ##   ## ##   ##    ##  ##     ##
    ##     ##  ##   ##  ##        ##     ##
    ########  ##     ## ##   ####  ########
    ##        ######### ##    ##         ##
    ##        ##     ## ##    ##  ##     ##
    ##        ##     ##  ######    #######
    */
    public function pag9()
    {
        return view('movil/pag9');
    }

    /*
    ##     ##  #######   ######     ###    ##       ####    ###
    ##     ## ##     ## ##    ##   ## ##   ##        ##    ## ##
    ##     ## ##     ## ##        ##   ##  ##        ##   ##   ##
    ##     ## ##     ## ##       ##     ## ##        ##  ##     ##
     ##   ##  ##     ## ##       ######### ##        ##  #########
      ## ##   ##     ## ##    ## ##     ## ##        ##  ##     ##
       ###     #######   ######  ##     ## ######## #### ##     ##
    */
    /**
     * PAGINA QUE MUETRA A LA VOCALÍA
     * @param  [type] $idVocalia [description]
     * @return [type]            [description]
     */
    public function vocalia(Request $request, $idVocalia){

        $user = Auth::user();
        if($user == null){
            return view('welcome');
        }

        //VOCALIAS ACTUALES
        if($idVocalia == null){
            $request->session()->flash('alert-danger', Lang::get('errortext.ERRORVocaliaNoEncontrada'));
            return redirect()->route('home');
        }
        $vocaliaActual = Vocalia::where('id',$idVocalia)->first();
        $usuarioVocal = User::permission('permiso_vocalia_'.$vocaliaActual->nombre)->get()->first();
        //dd('permiso_vocalia_'.$id->nombre, $usuarioVocal);
        if($usuarioVocal == "" || $usuarioVocal == null){
            $vocal = array(
                'nombre' => Lang::get('errortext.ERRORNoVocal'),
                'foto' => 'sinFoto.png',
                'email' => Lang::get('text.emailSecretario')
            );
        }else{
            $vocal = array(
                'nombre' => $usuarioVocal->dimeNombre(),
                'foto' => $usuarioVocal->foto,
                'email' => $usuarioVocal->email
            );
        }
        if($vocal['foto'] == ""){
            $vocal['foto'] = 'sinFoto.png';
        }

        $votaciones = $user->votaciones()->get()->toArray();
        //PROPUESTAS DE COMPRA Y VOTACIONES
        $propuestasLista = $vocaliaActual->propuestas()->get();
        //$votaciones = null;
        $propuestas = null;
        foreach ($propuestasLista as $value) {
            $numVotos = 0;
            $numVotos = Votacion::where('idPropuesta', $value->id)->count();
            $vot = false;
            foreach ($votaciones as $key => $votacion) {
                if($votacion['idPropuesta'] == $value['id']){
                    $vot = true;
                }
            }
            if($value->cantidad<= Configura::getValor('CMEN')){
                $MP = 'P';
            }else{
                $MP = 'M';
            }
            $simpleXmlObject = simplexml_load_file('https://www.boardgamegeek.com/xmlapi/search?search=' .  $value->propuesta);
            $array = json_decode(json_encode($simpleXmlObject), true);
            if(!isset($array['0'])){
                $url=route('juegonoencontrado');
            }
            else{
                if(!isset($array['boardgame']) && count($array['boardgame']) == 3){
                    if(isset($array['boardgame']['@attributes']))
                    {
                        $juegos = 'https://boardgamegeek.com/geeksearch.php?action=search&objecttype=boardgame&q=' .  $value->propuesta;
                    }
                    else{
                        $juegos = "https://boardgamegeek.com/boardgame/" . $array['boardgame']['@attributes']['objectid'];
                    }
                }else{
                    $juegos = 'https://boardgamegeek.com/geeksearch.php?action=search&objecttype=boardgame&q=' .  $value->propuesta;
                }
                $url = $juegos;
            }
            $propuestas[] =array(
                    'id' => $value->id,
                    'socio' => User::where('id',$value->idSocio)->first()->nombre ." ". User::where('id',$value->idSocio)->first()->primerApellido,
                    'propuesta' => $value->propuesta,
                    'cantidad' => $value->cantidad,
                    'votado' => $vot,
                    'mp' => $MP,
                    'numVotos' => $numVotos,
                    'BGG' => $url,
            );
        }

       // Obtener una lista de columnas
        $numVotaciones = 0;
        $cantidad = 0;
        $numVotos = 0;
        $numSocNV = 0;
        $numSocIVNV = 0;
        if($propuestas!=null){
            $cantidad  = array_column($propuestas, 'cantidad');
            $numVotos = array_column($propuestas, 'numVotos');
            // Ordenar los datos con volumen descendiente, edición ascendiente
            // Agregar $datos como el último parámetro, para ordenar por la clave común
            array_multisort($numVotos, SORT_DESC, $cantidad, SORT_DESC, $propuestas);
            for($i=0; $i<count($propuestas); $i++){
                $propuestas[$i]+= ["orden"=>$i+1];
            }
            $numVotaciones = Votacion::all()->whereIn('idPropuesta', Arr::pluck($propuestas,'id'))->groupBy('idSocio');
            $numSocNV = User::where('habilitado', true)->get()->count() - count($numVotaciones);
            foreach(User::where('habilitado', true)->get() as $key=> $value)
            {
                $aux = $value->asignaciones()->where('idVocalia',$vocaliaActual->id)->get();
                if($aux->count() > 0){
                    $numSocIVNV++;
                }
            }
            $numSocIVNV = $numSocIVNV - count($numVotaciones);
        }

        //COMPRAS
        $compras = $vocaliaActual->compras()->get();

        //SOCIOS CON PRIVACIDAD HABILITADA
        $socios = User::where('habilitado',true)->where('privacidad',true)->get();
        $sociosEmail = User::where('habilitado',true)->where('recibirCorreos',true)->get();

        $fillColors = [
            "rgba(255, 99, 132, 0.2)",
            "rgba(22,160,133, 0.2)",
            "rgba(255, 205, 86, 0.2)",
            "rgba(51,105,232, 0.2)",
            "rgba(244,67,54, 0.2)",
            "rgba(34,198,246, 0.2)",
            "rgba(153, 102, 255, 0.2)",
            "rgba(255, 159, 64, 0.2)",
            "rgba(233,30,99, 0.2)",
            "rgba(205,220,57, 0.2)"

        ];
        $compMen = Configura::getValor('CMEN');
        if ($propuestas == null){
            $props = null;
            $vots = 0;
            $propsM = null;
            $votsM = null;
            $propsP = null;
            $votsP = null;
        }else{
            $props = Arr::pluck($propuestas,'propuesta');
            $vots = Arr::pluck($propuestas,'numVotos');

            $propsM = Arr::pluck(
                (Collect($propuestas)->where('cantidad','>',$compMen))->toArray()
                ,'propuesta'
            );
            $votsM = Arr::pluck(
                (Collect($propuestas)->where('cantidad','>',$compMen))->toArray()
                ,'numVotos'
            );

            $propsP = Arr::pluck(
                (Collect($propuestas)->where('cantidad','<=',$compMen))->toArray()
                ,'propuesta'
            );
            $votsP = Arr::pluck(
                (Collect($propuestas)->where('cantidad','<=',$compMen))->toArray()
                ,'numVotos'
            );
        }

        $AllEvents = Event::getEventsOnCalendar($vocaliaActual->idCalendario);

        $colores = array (
            "1" => "#7986cb",
            "2" => "#33b679",
            "3" => "#8e24aa",
            "4" => "#e67c73",
            "5" => " #f6c026",
            "6" => "#f5511d",
            "7" => "#039be5",
            "8" => "#616161",
            "9" => "#3f51b5",
            "10" => "#0b8043",
            "11" => "#d60000"
        );

        if($AllEvents == null || count($AllEvents) == 0){
            $eventos = null;
        }else{
            $CONT = 1;
            foreach ($AllEvents as $key => $event) {
                if($vocaliaActual == null){
                    $colorCal = 11;
                }
                else{
                    $colorCal = $vocaliaActual->color;
                }
                $eventos[] = array(
                    'num' => $CONT,
                    'fechaInicio' => $event->startDateTime,
                    'fechaFin' => $event->endDateTime,
                    'titulo' => $event->name,
                    'descripcion' => $event->description,
                    'link' => $event->htmlLink,
                    'color' => $colores[$colorCal],
                );
                if($CONT >10){
                    break;
                }else{
                    $CONT = $CONT+1;
                }
            }
        }

        $resultado = array(
            'vocalia' => $vocaliaActual,
            'eventos' => $eventos,
            'vocal' => $vocal,
            'request' => $request,
            'propuestas' => $propuestas,
            'compras' => $compras,
            'votaciones' => $votaciones,
            'numVotaciones' => $numVotaciones,
            'fillColors' => $fillColors,
            'socios' => $socios,
            'sociosEmail' => $sociosEmail,
            'props' => $props,
            'vots' => $vots,
            'propsM' => $propsM,
            'votsM' => $votsM,
            'propsP' => $propsP,
            'votsP' => $votsP,
            'colores' => $colores,
            'separaComp' => $vocaliaActual->separaComp,
            'cantPropMen' => $compMen,
            'numSocNV' => $numSocNV,
            'numSocIVNV' => $numSocIVNV,
        );
        //dd($resultado);
        return view('movil/vocalia',$resultado);

    }

    /*
     ######   ##     ##    ###    ########  ########     ###    ######## ####  ######  ##     ##    ###    ##     ##  #######  ##     ## #### ##
    ##    ##  ##     ##   ## ##   ##     ## ##     ##   ## ##   ##        ##  ##    ## ##     ##   ## ##   ###   ### ##     ## ##     ##  ##  ##
    ##        ##     ##  ##   ##  ##     ## ##     ##  ##   ##  ##        ##  ##       ##     ##  ##   ##  #### #### ##     ## ##     ##  ##  ##
    ##   #### ##     ## ##     ## ########  ##     ## ##     ## ######    ##  ##       ######### ##     ## ## ### ## ##     ## ##     ##  ##  ##
    ##    ##  ##     ## ######### ##   ##   ##     ## ######### ##        ##  ##       ##     ## ######### ##     ## ##     ##  ##   ##   ##  ##
    ##    ##  ##     ## ##     ## ##    ##  ##     ## ##     ## ##        ##  ##    ## ##     ## ##     ## ##     ## ##     ##   ## ##    ##  ##
     ######    #######  ##     ## ##     ## ########  ##     ## ##       ####  ######  ##     ## ##     ## ##     ##  #######     ###    #### ########
    */
    public function guardaFichaMovil(Request $request){
        //dd($request);
        //Busco al usuario y le actualizo los datos
        $timezone = new \DateTimeZone('Europe/Madrid');
        $user = User::where('id',$request->inputIdSocio)->first();

        $usernameCogido = User::where('username', $request->inputUsername)->first();
        if($usernameCogido != null && $usernameCogido->id != $request->inputIdSocio){
            $request->session()->flash('alert-danger', Lang::get('errortext.ERRORUsernameCogido'));
            return redirect()->route('gestionficha', ['idSocio' => $request->inputIdSocio]);
        }
        $emailCogido = User::where('email', $request->inputEmail)->first();
        if($emailCogido != null && $emailCogido->id != $request->inputIdSocio){
            $request->session()->flash('alert-danger', Lang::get('errortext.ERROREmailCogido'));
            return redirect()->route('gestionficha', ['idSocio' => $request->inputIdSocio]);
        }

        Log::info(Lang::get('logtext.L_DesdeMovil').Lang::get('logtext.L_FichaAct'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        Log::info('ANTES: '. $user->aString());


        if($request->hasFile('fotoPerfil')){
            $avatar = $request->file('fotoPerfil');
            $nombreFichero = $request->inputUsername . time() . '.' . $avatar->getClientOriginalExtension();
            Image::make($avatar)->resize(null,300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save(public_path('/images/fotos/'.$nombreFichero));
            File::delete(public_path('/images/fotos/'. $user->foto));
            //Storage::disk('fotos')->delete('/' . $user->foto);
            $user->foto = $nombreFichero;
        }

        //$altaSocio = Carbon::createFromFormat('d/m/Y',$request->altaSocio, $timezone);
        //$user->altaSocio = $altaSocio;
        $user->numSocio = $request->numSocio;
        $user->DNI = $request->inputDNI;
        $user->nombre = $request->inputName;
        $user->primerApellido = $request->inputApellido1;
        $user->segundoApellido = $request->inputApellido2;
        $user->fnacimiento = Carbon::createFromFormat('d/m/Y',$request->fnacimiento, $timezone);
        if($request->passwordText != null){
            $user->password = bcrypt($request->passwordText);
        }
        else{
            $user->password = $request->password;
        }
        $user->sexo = $request->inputsexo;
        $user->direccion = $request->inputDireccion;
        $user->localidad = $request->inputLocalidad;
        $user->provincia = $request->inputProvincia;
        $user->telefono = $request->inputTelefono;
        $user->codPostal = $request->inputCodPostal;
        $user->email = $request->inputEmail;
        $user->username = $request->inputUsername;
        //$user->notas = $request->txtNotas;
        $user->recibirCorreos = $request->recibirCorreos;
        $user->privacidad = $request->privacidad;
        if(Auth::user()->hasRole(['secretario', 'tesorero', 'admin'])){
            $user->habilitado = $request->inputHabilitado;
            $user->accesoDrive = $request->inputaccesoDrive;
            $user->accesoJunta = $request->inputAccesoJunta;
        }else{
            $user->habilitado = $user->habilitado;
            $user->accesoDrive = $user->accesoDrive;
            $user->accesoJunta = $user->accesoJunta;
        }

        $user->save();

        Log::info(Lang::get('logtext.L_desp'). $user->aString());

        //Le pongo sus asignaciones de cuota
        AsignacionSocio::where('idSocio',$request->inputIdSocio)->delete();
        $asignacion = array();
        if($request->asignacionSocio){
            foreach ($request->asignacionSocio as $key => $value) {
                $asignacion[] = AsignacionSocio::create([
                    'idSocio' => $request->inputIdSocio,
                    'idVocalia' => $value,
                ]);
            }
        }

        //Se guarda la cuota de socio si está el check seleccionado
        if(Auth::user()->hasRole(['tesorero', 'admin'])){
            if($request->actualizaCuotaCheck){
                $tipoCuota = TiposCuota::where('id',$request->tipoCuota)->first();
                $fechaInput = Carbon::createFromFormat('d/m/Y',$request->fechaCuota);
                $cuenta = Cuenta::create([
                    'fechaApunte' => $fechaInput,
                    'año'=> $fechaInput->format('Y'),
                    'tipo'=> 'Ingreso',
                    'conceptoAgrupado' => Lang::get('text.ConceptCuota'),
                    'detalle' => Lang::get('text.cuota')." ".$tipoCuota->nombre.Lang::get('text.ofSocio')." ". $user->id,
                    'vocalia' => Lang::get('text.NoVocalia'),
                    'cantidad' => $tipoCuota->cantidad,
                    'notas' => '',
                    'pagcob'=> Lang::get('text.si'),
                ]);
                $cuota = Cuota::create([
                    'idSocio' => $user->id,
                    'idAsiento' => $cuenta->id,
                    'tipoCuota' => $tipoCuota->id,
                    'cantidad' => $tipoCuota->cantidad,
                    'fechaCuota' => $fechaInput,
                ]);
                Log::info(Lang::get('logtext.L_DesdeMovil').Lang::get('logtext.L_CuotaNew')." ".$cuota->id.Lang::get('logtext.L_NumAs').$cuenta->id);
            }
        }

        $request->session()->flash('alert-info', Lang::get('errortext.OKdatosActualizados'));
        return app('App\Http\Controllers\MovilController')->pag1($request,$request->inputIdSocio);
    }

    /*
     dP""b8    db    .dP"Y8 888888    db    88 88b 88 Yb    dP 88 888888    db     dP""b8 88  dP"Yb  88b 88
    dP   `"   dPYb   `Ybo."   88     dPYb   88 88Yb88  Yb  dP  88   88     dPYb   dP   `" 88 dP   Yb 88Yb88
    Yb  "88  dP__Yb  o.`Y8b   88    dP__Yb  88 88 Y88   YbdP   88   88    dP__Yb  Yb      88 Yb   dP 88 Y88
     YboodP dP""""Yb 8bodP'   88   dP""""Yb 88 88  Y8    YP    88   88   dP""""Yb  YboodP 88  YbodP  88  Y8
    */
    public function gastarInvitacionMovil($idSocio){
        $socio = User::where('id',$idSocio)->first();
        $invitaciones = Invitaciones::where('idSocio', $idSocio)->get();
        $resultado = array(
            'socio' => $socio,
            'invitaciones' => $invitaciones,
        );
        //dd($resultado);
        return view('movil/gastarInvitacionMovil', $resultado);

    }

    /*
    8b    d8  dP"Yb  Yb    dP 88 88     88 88b 88 Yb    dP 88 888888    db     dP""b8 88  dP"Yb  88b 88
    88b  d88 dP   Yb  Yb  dP  88 88     88 88Yb88  Yb  dP  88   88     dPYb   dP   `" 88 dP   Yb 88Yb88
    88YbdP88 Yb   dP   YbdP   88 88  .o 88 88 Y88   YbdP   88   88    dP__Yb  Yb      88 Yb   dP 88 Y88
    88 YY 88  YbodP     YP    88 88ood8 88 88  Y8    YP    88   88   dP""""Yb  YboodP 88  YbodP  88  Y8
    */
    public function movilInvitacion(Request $request){

        //dd($request);
        $usuario = User::where('id', '=', $request->idSocio)->first();
        $fecha = Carbon::createFromFormat('Y-m-d',$request->fecha);
        if( $usuario->invitaciones > 0){
            $usuario->restaInvitacion();
            $invitado = Invitaciones::create([
                'fecha' => $fecha,
                'idSocio' => $request->idSocio,
                'invitado' => $request->invitado
            ]);
            $invitado->save();
            Log::info(Lang::get('logtext.L_DesdeMovil').Lang::get('logtext.L_GastInv'). Auth::user()->id.') '. Auth::user()->dimeNombre().Lang::get('logtext.L_InvName').$request->invitado);
            $request->session()->flash('alert-info', Lang::get('errortext.OKgastaInvitacion'));
            $invitaciones = Invitaciones::where('idSocio', $usuario->id)->get();
            return view('movil/gastarInvitacionMovil', ['socio' => $usuario, 'invitaciones' => $invitaciones, 'request' => $request]);
        }
        else{
            $request->session()->flash('alert-danger', Lang::get('errortext.ERRORnoInvitaciones'));
            $invitaciones = Invitaciones::where('idSocio', $usuario->id)->get();
            return view('movil/gastarInvitacionMovil', ['socio' => $usuario, 'invitaciones' => $invitaciones, 'request' => $request]);
        }

    }


    /*
    Yb    dP  dP"Yb  888888    db    88""Yb 88""Yb 88""Yb  dP"Yb  88""Yb 88   88 888888 .dP"Y8 888888    db    8b    d8  dP"Yb  Yb    dP 88 88
     Yb  dP  dP   Yb   88     dPYb   88__dP 88__dP 88__dP dP   Yb 88__dP 88   88 88__   `Ybo."   88     dPYb   88b  d88 dP   Yb  Yb  dP  88 88
      YbdP   Yb   dP   88    dP__Yb  88"Yb  88"""  88"Yb  Yb   dP 88"""  Y8   8P 88""   o.`Y8b   88    dP__Yb  88YbdP88 Yb   dP   YbdP   88 88  .o
       YP     YbodP    88   dP""""Yb 88  Yb 88     88  Yb  YbodP  88     `YbodP' 888888 8bodP'   88   dP""""Yb 88 YY 88  YbodP     YP    88 88ood8
    */
    public function votarPropuestaMovil(Request $request){
        //dd($request);
        $user = Auth::user();
        $votacionesUser = $user->votaciones();
        $votacionesUser->delete();

        if ($request->propuestaSelect != null || $request->propuestaSelect != ""){
           foreach ($request->propuestaSelect as $key => $value) {
                $votacion = Votacion::create([
                    'idSocio' => Auth::user()->id,
                    'idPropuesta' => $value,
                    'valor' => true,
                ]);
            }
        }

        $request->session()->flash('alert-success', Lang::get('errortext.OKvotos'));
        return redirect()->route('vocaliaMovil', ['idVocalia' => $request->vocaliaActual]);
    }

    /*
    Yb    dP  dP"Yb  888888    db    88""Yb 88""Yb 88""Yb  dP"Yb  88""Yb 88   88 888888 .dP"Y8 888888    db    8b    d8  dP"Yb  Yb    dP 88 88     8b    d8 88""Yb
     Yb  dP  dP   Yb   88     dPYb   88__dP 88__dP 88__dP dP   Yb 88__dP 88   88 88__   `Ybo."   88     dPYb   88b  d88 dP   Yb  Yb  dP  88 88     88b  d88 88__dP
      YbdP   Yb   dP   88    dP__Yb  88"Yb  88"""  88"Yb  Yb   dP 88"""  Y8   8P 88""   o.`Y8b   88    dP__Yb  88YbdP88 Yb   dP   YbdP   88 88  .o 88YbdP88 88"""
       YP     YbodP    88   dP""""Yb 88  Yb 88     88  Yb  YbodP  88     `YbodP' 888888 8bodP'   88   dP""""Yb 88 YY 88  YbodP     YP    88 88ood8 88 YY 88 88
    */
    public function votarPropuestaMovilMP(Request $request){
        //dd($request);
        $user = Auth::user();
        $votacionesUser = $user->votaciones();
        $votacionesUser->delete();

        if($votacionesUser->count() > 0)
        {
            foreach ($votacionesUser as $key => $value) {
                $propuesta = Propuesta::where('id', $value->idPropuesta)->first();
                if ($propuesta!= null && $propuesta->idVocalia == $request->vocaliaActual){
                    $votacionesUser[$key]->delete();
                }
            }
        }
        if ($request->propuestaSelectM != null || $request->propuestaSelectM != ""){
           foreach ($request->propuestaSelectM as $key => $value) {
                $votacion = Votacion::create([
                    'idSocio' => $user->id,
                    'idPropuesta' => $value,
                    'valor' => true,
                ]);
            }
        }
        if ($request->propuestaSelectP != null || $request->propuestaSelectP != ""){
            foreach ($request->propuestaSelectP as $key => $value) {
                 $votacion = Votacion::create([
                     'idSocio' => $user->id,
                     'idPropuesta' => $value,
                     'valor' => true,
                 ]);
             }
        }

        $request->session()->flash('alert-success', Lang::get('errortext.OKvotos'));
        return redirect()->route('vocaliaMovil', ['idVocalia' => $request->vocaliaActual]);
    }

    /*
     dP""b8 88   88    db    88""Yb 8888b.     db    88""Yb 88""Yb  dP"Yb  88""Yb 88   88 888888 .dP"Y8 888888    db    8b    d8  dP"Yb  Yb    dP 88 88
    dP   `" 88   88   dPYb   88__dP  8I  Yb   dPYb   88__dP 88__dP dP   Yb 88__dP 88   88 88__   `Ybo."   88     dPYb   88b  d88 dP   Yb  Yb  dP  88 88
    Yb  "88 Y8   8P  dP__Yb  88"Yb   8I  dY  dP__Yb  88"""  88"Yb  Yb   dP 88"""  Y8   8P 88""   o.`Y8b   88    dP__Yb  88YbdP88 Yb   dP   YbdP   88 88  .o
     YboodP `YbodP' dP""""Yb 88  Yb 8888Y"  dP""""Yb 88     88  Yb  YbodP  88     `YbodP' 888888 8bodP'   88   dP""""Yb 88 YY 88  YbodP     YP    88 88ood8
    */
    public function guardaPropuestaMovil(Request $request){
        //dd($request);
        if($request->inputDesc == "" || $request->inputCant == "" || $request->inputDesc == null || $request->inputCant == null){
            $request->session()->flash('alert-danger',  Lang::get('errortext.ERRORpropuestaCamposVacios'));
            return redirect()->route('vocaliaMovil', ['idVocalia' => $request->vocaliaActual]);
        }
        $propuesta = Propuesta::create([
            'idSocio' => Auth::user()->id,
            'idVocalia' => $request->vocaliaActual,
            'propuesta' => $request->inputDesc,
            'cantidad' => $request->inputCant
        ]);

        $votacion = Votacion::create([
            'idSocio' => Auth::user()->id,
            'idPropuesta' => $propuesta->id,
            'valor' => true,
        ]);

        Log::info(Lang::get('logtext.L_DesdeMovil').Lang::get('logtext.L_PropCompra'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        Log::info(Lang::get('logtext.L_PropName'). $propuesta->aString());

        $request->session()->flash('alert-success', Lang::get('errortext.OKpropuestaCreada'));
        return redirect()->route('vocaliaMovil', ['idVocalia' => $request->vocaliaActual]);
    }

    /*
     dP""b8 88   88    db    88""Yb 8888b.     db    88""Yb 888888 Yb    dP 888888 88b 88 888888  dP"Yb  8b    d8  dP"Yb  Yb    dP 88 88
    dP   `" 88   88   dPYb   88__dP  8I  Yb   dPYb   88__dP 88__    Yb  dP  88__   88Yb88   88   dP   Yb 88b  d88 dP   Yb  Yb  dP  88 88
    Yb  "88 Y8   8P  dP__Yb  88"Yb   8I  dY  dP__Yb  88"Yb  88""     YbdP   88""   88 Y88   88   Yb   dP 88YbdP88 Yb   dP   YbdP   88 88  .o
     YboodP `YbodP' dP""""Yb 88  Yb 8888Y"  dP""""Yb 88  Yb 888888    YP    888888 88  Y8   88    YbodP  88 YY 88  YbodP     YP    88 88ood8
    */
    public function guardarEventoMovil(Request $request)
    {
        //dd($request);
        if($request->vocaliaSel == "Evento de Asociación"){
            $vocaliaActual = collect([
                (object) [
                    'id' => 0,
                    'nombre' => 'Asociación',
                    'descripcion' => '',
                    'presupuesto' => 0,
                    'imagen' => '',
                    'color' => 11,
                    'idCalendario' => Configura::getValor('IdCalendarioImportantes')
                ]
            ])->first();;
        }else{
            $vocaliaActual = Vocalia::where('nombre', $request->vocaliaSel)->first();
        }
        if($request->inputNombre == "" || $request->inputNombre == null){
            $request->session()->flash('alert-danger', Lang::get('errortext.ERROReventoSinDescipcion'));
            return redirect()->route('vocalia', ['id' => $request->vocaliaActual]);
        }

        if($request->fechaInicio == "" || $request->fechaInicio == null){
            $request->fechaInicio = Carbon::now()->format('d/m/Y');
        }
        if($request->horaInicio == "" || $request->horaInicio == null){
            $request->horaInicio = Carbon::now()->format('H:i');
        }
        if($request->fechaFin == "" || $request->fechaFin == null){
            $request->fechaFin = $request->fechaInicio;
        }
        if($request->horaFin == "" || $request->horaFin == null){
            $request->horaFin = (Carbon::createFromFormat('H:i',$request->horaInicio))->add('hour',2)->format('H:i');
        }
        $event = new Event;
        $event->name = $request->inputNombre. " (".Auth::user()->username.")";
        $ini = $request->fechaInicio."-".$request->horaInicio;
        $fin = $request->fechaFin."-".$request->horaFin;
        $event->startDateTime = Carbon::createFromFormat('d/m/Y-H:i', $ini);
        $event->endDateTime = Carbon::createFromFormat('d/m/Y-H:i', $fin);
        if($request->socioSelect != null){
            $obj = new \stdClass();
            if($request->salaRol == "siSala"){
                $event->description = Lang::get('text.salaReservada'). "------------" . $request->descripcionEvento;
                $obj->descripcion = Lang::get('text.salaReservada'). "------------" . $request->descripcionEvento;
                $obj->reservaSala = Lang::get('text.salaReservada');
                $obj->plantilla = "Evento con Reserva de Sala";
            }else{
                $event->description = $request->descripcionEvento;
                $obj->descripcion = $request->descripcionEvento;
                $obj->reservaSala = "";
                $obj->plantilla = "Te han invitado a un evento";
            }
            $obj->asunto = $request->inputNombre;
            $obj->fecha = $request->fechaInicio;
            $obj->hora = $request->horaInicio;
            $obj->emailSender = Auth::user()->email;
            $obj->sender = Auth::user()->nombre . " " . Auth::user()->primerApellido . " (" . Auth::user()->username .")";
            foreach ($request->socioSelect as $key => $socio) {
                $participante = User::where('id',$socio)->first();
                $obj->receiver = $participante->nombre;
                Mail::to($participante->email)->send(new Email($obj));
            }
            if($request->salaRol == "siSala"){
                Mail::to(Lang::get('text.E_ReservSala'))->send(new Email($obj));
            }
        }

        $event->saveOnCalendarId($vocaliaActual->idCalendario);
        $events = Event::getEventsOnCalendar($vocaliaActual->idCalendario);
        $guardaCal = Calendario::create([
            'fecha' => Carbon::createFromFormat('d/m/Y-H:i',$ini),
            'idSocio' => Auth::user()->id,
            'idVocalia' => $vocaliaActual->id,
            'eventId' => $events->last()->id
        ]);

        Log::info(Lang::get('logtext.L_DesdeMovil').Lang::get('logtext.L_NewEven'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        Log::info(Lang::get('logtext.L_EvName'). $event->name. Lang::get('logtext.L_EnCal').$vocaliaActual->idCalendario);

        $request->session()->flash('alert-success',  Lang::get('errortext.OKeventoCreado'));
        return redirect()->route('pag2');

    }


    /*
    88b 88 88   88 888888 Yb    dP  dP"Yb     db    88""Yb 88   88 88b 88 888888 888888 8b    d8  dP"Yb  Yb    dP 88 88
    88Yb88 88   88 88__    Yb  dP  dP   Yb   dPYb   88__dP 88   88 88Yb88   88   88__   88b  d88 dP   Yb  Yb  dP  88 88
    88 Y88 Y8   8P 88""     YbdP   Yb   dP  dP__Yb  88"""  Y8   8P 88 Y88   88   88""   88YbdP88 Yb   dP   YbdP   88 88  .o
    88  Y8 `YbodP' 888888    YP     YbodP  dP""""Yb 88     `YbodP' 88  Y8   88   888888 88 YY 88  YbodP     YP    88 88ood8
    */
    public function nuevoApunteMovil (Request $request)
    {
        $fecha = Carbon::createFromFormat('Y-m-d', $request->fecha);
        if(preg_match( '/^[12][0-9]{3}$/', $request->year)){
            $anio = Carbon::now()->format('Y');
        }else{
            $anio = $request->year;
        }
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
        Log::info(Lang::get('logtext.L_DesdeMovil').Lang::get('logtext.L_NewApunt'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        Log::info(Lang::get('logtext.L_ApuntName'). $nuevoApunte->aString());
        if($request->conceptoAgrupado == Lang::get('text.ConceptCuota')){
            if($cantidad == Configura::getValor("CASV") || $cantidad == Configura::getValor("CASN")){
                $tipoCuota = "anual";
            }elseif($cantidad == Configura::getValor("CS")){
                $tipoCuota = "semestral";
            }elseif($cantidad == Configura::getValor("CT")){
                $tipoCuota = "trimestral";
            }else{
                $tipoCuota = "especial";
            }
            $cuota = Cuota::create([
                'idSocio' => $request->socioSelect,
                'tipoCuota' => $tipoCuota,
                'cantidad' => $cantidad,
                'fechaCuota' => $fecha,
            ]);
            $request->session()->flash('alert-info', Lang::get('errortext.OKNuevoApunteConCuota'));
            return redirect()->route('pag7', ['request' => $request]);
        }else{
            $request->session()->flash('alert-info', Lang::get('errortext.OKNuevoApunte'));
            return redirect()->route('pag7', ['request' => $request]);
        }
    }


}
