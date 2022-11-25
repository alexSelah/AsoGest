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
use App\Cuenta;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;
use DateTime;
use App\Invitaciones;
use App\Configura;
use App\Vocalia;
use App\Calendario;
use App\TiposCuota;
use File;
use Spatie\GoogleCalendar\Event;
use Log;
use Throwable;

class GestionFichaController extends Controller
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
    public function index(Request $request, $idSocio = null)
    {
        $user = Auth::user();
        if($user == null){
            return view('welcome');
        }

        $agent = new Agent();
        if($agent->isPhone() || $agent->isMobile()){
           return app('App\Http\Controllers\MovilController')->pag1($request,$idSocio);
        }

        if($idSocio != null){
            $user = User::where('id',$idSocio)->first();
            if($user == null){
                $request->session()->flash('alert-danger', Lang::get('errortext.ERRORnoSocio'));
                return view('/secretario',$request);
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

        //dd($roles, $posiblesRoles);

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

        //Asignaciones del socio a las Vocalías
        $as = $user->asignaciones()->get();

        $vocalias=Vocalia::all();

        $tiposCuota = TiposCuota::get();
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
        //Recoge eventos del calendario
        $eventosdelsocio = null;
        $listaCalendariosId = Vocalia::get()->pluck('idCalendario')->toArray();
        $listaCalendariosId[] = Lang::get('text.emailPrincipal');
        $AllEvents = new Collection();
        foreach ($listaCalendariosId as $key => $value) {
            try{
                $events = Event::get(null,null,[],$value);
                if(count($events)>0)
                {
                    $AllEvents = $AllEvents->merge(Event::get(null,null,[],$value));
                }
            }
            catch(Throwable $e){
                $request->session()->flash('alert-danger',  Lang::get('text.ERRORcalendarioNoEncontrado'));
                return redirect()->route('home');
            }
        }
        $AllEvents = $AllEvents->toArray();
        //Limpiamos el calendario de efentos pasados y recogemos los del usuario
        $limpia = Calendario::limpiaCalendario();
        $calEvents = $user->eventos()->get()->toArray();
        //dd("AllEvents", $AllEvents);
        foreach ($AllEvents as $key => $calendario) {
             foreach ($calEvents as $key => $evento){
                if($calendario->id == $evento['eventId']){
                    $eventosdelsocio[] = array(
                        'id' => $evento['id'],
                        'idEventoGoogle' => $evento['eventId'],
                        'fecha' => $evento['fecha'],
                        'descripcion' => $calendario->name,
                        'vocalia' => (Vocalia::where('id', $evento['idVocalia']))->first()->nombre,
                    );
                }
            }
        }

        // Carbon -> ...
        // eq() equals
        // ne() not equals
        // gt() greater than
        // gte() greater than or equals
        // lt() less than
        // lte() less than or equals

        if($user->altaSocio->lte(Carbon::now()->addMonths(-12))){
            $veretaro = true;
        }else{
            $veretaro = false;
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
            'eventosCal' => $eventosdelsocio,
            'noCuota' => $noCuota,
            'tiposCuota' => $tiposCuota,
        );
        //dd($resultado);
        $agent = new Agent();
        if($agent->isPhone() || $agent->isMobile()){
           return view('movil/homeMovil',$resultado);
        }
        return view('fichaSocio/gestionficha', $resultado);
    }

    /*
    888888 88     88 8b    d8 88 88b 88    db    888888 Yb    dP 888888 88b 88 888888  dP"Yb
    88__   88     88 88b  d88 88 88Yb88   dPYb   88__    Yb  dP  88__   88Yb88   88   dP   Yb
    88""   88  .o 88 88YbdP88 88 88 Y88  dP__Yb  88""     YbdP   88""   88 Y88   88   Yb   dP
    888888 88ood8 88 88 YY 88 88 88  Y8 dP""""Yb 888888    YP    888888 88  Y8   88    YbodP
    */
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function eliminaEvento(Request $request, $idEvento)
    {
        $eventoAborrar = Calendario::where('id', $idEvento)->first();
        $idSocio = $eventoAborrar->idSocio;
        //Event::find($eventId);
        $listaCalendariosId = Vocalia::get()->pluck('idCalendario')->toArray();
        $listaCalendariosId[] = env('GOOGLE_CALENDAR_ID');
        $AllEvents = new Collection();
        foreach ($listaCalendariosId as $key => $value) {
            $AllEvents = $AllEvents->merge(Event::get(null,null,[],$value));
        }
        $AllEvents = $AllEvents->toArray();
        foreach ($AllEvents as $key => $value) {
            if($eventoAborrar->eventId == $value->id){
                $value->delete();
                $eventoAborrar->delete();
            }
        }

        $request->session()->flash('alert-info', Lang::get('errortext.OKEventoEliminado'));
        return redirect()->route('gestionficha', ['idSocio' => $idSocio]);
    }

    /*
     dP""b8    db    .dP"Y8 888888    db    88""Yb 88 88b 88 Yb    dP 88 888888    db     dP""b8 88  dP"Yb  88b 88
    dP   `"   dPYb   `Ybo."   88     dPYb   88__dP 88 88Yb88  Yb  dP  88   88     dPYb   dP   `" 88 dP   Yb 88Yb88
    Yb  "88  dP__Yb  o.`Y8b   88    dP__Yb  88"Yb  88 88 Y88   YbdP   88   88    dP__Yb  Yb      88 Yb   dP 88 Y88
     YboodP dP""""Yb 8bodP'   88   dP""""Yb 88  Yb 88 88  Y8    YP    88   88   dP""""Yb  YboodP 88  YbodP  88  Y8
    */
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function gastarInvitacion(Request $request)
    {
        //dd($request);
        $usuario = User::where('id', '=', $request->idSocio)->first();
        $fecha = Carbon::createFromFormat('d/m/Y',$request->fechaInvitacion);
        if( $usuario->invitaciones > 0){
            $usuario->restaInvitacion();
            $invitado = Invitaciones::create([
                'fecha' => $fecha,
                'idSocio' => $request->idSocio,
                'invitado' => $request->invitado
            ]);
            $request->session()->flash('alert-info', Lang::get('errortext.OKgastaInvitacion'));
            return $this->index($request);
        }
        else{
            $request->session()->flash('alert-danger', Lang::get('errortext.ERRORnoInvitaciones'));
            return redirect()->route('gestionficha', ['idSocio' => $request->idSocio]);
        }

    }

    /*
    88""Yb 888888 .dP"Y8 888888 888888 888888    db    88 88b 88 Yb    dP 88 888888    db     dP""b8 88  dP"Yb  88b 88 888888 .dP"Y8
    88__dP 88__   `Ybo." 88__     88   88__     dPYb   88 88Yb88  Yb  dP  88   88     dPYb   dP   `" 88 dP   Yb 88Yb88 88__   `Ybo."
    88"Yb  88""   o.`Y8b 88""     88   88""    dP__Yb  88 88 Y88   YbdP   88   88    dP__Yb  Yb      88 Yb   dP 88 Y88 88""   o.`Y8b
    88  Yb 888888 8bodP' 888888   88   888888 dP""""Yb 88 88  Y8    YP    88   88   dP""""Yb  YboodP 88  YbodP  88  Y8 888888 8bodP'
    */
    public function resetearInvitaciones(Request $request, $idSocio = null)
    {
        if ($idSocio == null){
            $idSocio = Auth::user()->id;
        }
        if ($idSocio < 0){
            $socios = User::all();
            foreach ($socios as $socio) {
                $socio->reseteaInvitados();
            }
        }
        else{
            $socio = User::where('id','=', $idSocio)->first();
            if($socio == null){
                $request->session()->flash('alert-info', Lang::get('errortext.ERRORnoSocio'));
                return redirect()->route('gestionficha', ['idSocio' => $idSocio]);
            }else{
                $socio->reseteaInvitados($idSocio);
            }
        }

        $request->session()->flash('alert-info', Lang::get('errortext.OKreseteoInvitaciones'));
        return redirect()->route('gestionficha', ['idSocio' => $idSocio]);
    }

    /*
     ######  ########  #######  ########  ########
    ##    ##    ##    ##     ## ##     ## ##
    ##          ##    ##     ## ##     ## ##
     ######     ##    ##     ## ########  ######
          ##    ##    ##     ## ##   ##   ##
    ##    ##    ##    ##     ## ##    ##  ##
     ######     ##     #######  ##     ## ########
    */
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Busco al usuario y le actualizo los datos
        $timezone = new \DateTimeZone('Europe/Madrid');
        $user = User::where('id',$request->inputIdSocio)->first();

		$usernameCogido = User::where('username', $request->inputUsername)->first();
        if($usernameCogido != null && strcmp($usernameCogido->numSocio, $request->inputNumsocio) != 0){
            $request->session()->flash('alert-danger', Lang::get('errortext.ERRORUsernameCogido'));
            return redirect()->route('gestionficha', ['idSocio' => $request->inputIdSocio]);
        }
        $emailCogido = User::where('email', $request->inputEmail)->first();
        if($emailCogido != null && strcmp($emailCogido->numSocio, $request->inputNumsocio) != 0){
            $request->session()->flash('alert-danger', Lang::get('errortext.ERROREmailCogido'));
            return redirect()->route('gestionficha', ['idSocio' => $request->inputIdSocio]);
        }

        Log::info(Lang::get('logtext.L_FichaAct'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        Log::info(Lang::get('logtext._antes'). $user->aString());


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

        $altaSocio = Carbon::createFromFormat('d/m/Y',$request->altaSocio, $timezone);
        //dd($altaSocio);
        $user->altaSocio = $altaSocio;
        $user->numSocio = $request->inputNumsocio;
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
        $user->codPostal = $request->inpurCodPostal;
        $user->email = $request->inputEmail;
        $user->username = $request->inputUsername;
        $user->notas = $request->txtNotas;
        $user->recibirCorreos = $request->recibirCorreos;
        $user->privacidad = $request->privacidad;
        if(Auth::user()->hasRole(['secretario', 'admin'])){
        	if($request->fechaBaja == null){
				$user->bajaSocio = null;
			}else{
				$bajaSocio = Carbon::createFromFormat('d/m/Y',$request->fechaBaja, $timezone);
				if($bajaSocio->format('dmY') != (Carbon::now())->format('dmY')){
					$user->bajaSocio = $bajaSocio;
				}
			}
        }
        if(Auth::user()->hasRole(['secretario','tesorero', 'admin'])){
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

        if(Auth::user()->hasRole(['secretario', 'admin'])){
            //Le quito los roles y permisos que tuviese y Le asigno el Rol o el permiso correspondiente
            if($request->vocaliaSelect != null){
                $vocaliaSel = (Vocalia::where('id', $request->vocaliaSelect)->first())->nombre;
            }
            $roleSelect = Role::where('id', $request->roles)->first();
            //dd($request, $user, $vocaliaSel , $roleSelect, 'permiso_vocalia_'.$vocaliaSel);
            $rolesCol = $user->getRoleNames();
            foreach ($rolesCol as $key => $value) {
                $user->removeRole($value);
            }
            $permissionsCol = $user->getAllPermissions();
            foreach ($permissionsCol as $key => $value) {
                $user->revokePermissionTo($value);
            }
            if($roleSelect->name == "vocal"){
                $user->assignRole($roleSelect);
                $user->givePermissionTo('permiso_vocalia_'.$vocaliaSel);
            }
            else{
                $user->assignRole($roleSelect);
            }
        }

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

        //Guardamos una cuota si se ha seleccionado el check del tesorero
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
                 Log::info(Lang::get('logtext.L_CuotaNew').$cuota->id.Lang::get('logtext.L_NumAs').$cuenta->id);
            }
        }

        //vuelvo a la pantalla con un mensaje OK
        $request->session()->flash('alert-info', Lang::get('errortext.OKdatosActualizados'));
        return redirect()->route('gestionficha', ['idSocio' => $request->inputIdSocio]);
        //return $this->index($request, $request->inputIdSocio);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

}
