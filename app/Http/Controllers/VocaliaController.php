<?php

namespace App\Http\Controllers;

use App\AsignacionSocio;
use Illuminate\Http\Request;
use App\Vocalia;
use App\User;
use Spatie\GoogleCalendar\Event;
use Carbon\Carbon;
use App\Propuesta;
use App\Compra;
use App\Votacion;
use App\Calendario;
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
use App\Configura;
use Log;
use Illuminate\Http\Response;


class VocaliaController extends Controller
{
#### ##    ## ########  ######## ##     ##
 ##  ###   ## ##     ## ##        ##   ##
 ##  ####  ## ##     ## ##         ## ##
 ##  ## ## ## ##     ## ######      ###
 ##  ##  #### ##     ## ##         ## ##
 ##  ##   ### ##     ## ##        ##   ##
#### ##    ## ########  ######## ##     ##
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request, Vocalia $id = null)
    {
        $user = Auth::user();
        if($user == null){
            return view('welcome');
        }

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

        //VOCALIAS ACTUALES
        $vocalias = Vocalia::all();
        if($id == null){
            $request->session()->flash('alert-danger', Lang::get('errortext.ERRORVocaliaNoEncontrada'));
            return redirect()->route('home');
        }
        $usuarioVocal = User::permission('permiso_vocalia_'.$id->nombre)->get()->first();
        //dd('permiso_vocalia_'.$id->nombre, $usuarioVocal);
        if($usuarioVocal == "" || $usuarioVocal == null){
            $vocal = array(
                'nombre' => 'No hay ningún vocal (todavía)',
                'foto' => 'sinFoto.png',
                'email' => Lang::get('text.emailSecretario')
            );
        }else{
            $vocal = array(
                'nombre' => $usuarioVocal->nombre . " " . $usuarioVocal->primerApellido,
                'foto' => $usuarioVocal->foto,
                'email' => $usuarioVocal->email
            );
        }
        if($vocal['foto'] == ""){
            $vocal['foto'] = 'sinFoto.png';
        }

        //EVENTOS DEL GOOGLE CALENDAR
        //$events = Event::get();

        $idCalendario = $id->idCalendario;
        $events = Event::getEventsOnCalendar($idCalendario);
        //dd($events, $events->last(), $events->last()->id);
        //dd($events,$events[0]->startDate, $events[0]->startDateTime, $events[0]->endDate, $events[0]->endDateTime);
        if($events->count() <5){
            $long = $events->count();
        }else{
            $long =5;
        }
        if($events->isEmpty()){
            $proximosEventos = null;
        }else{
            for ($i=0;$i<$long;$i++){
                $proximosEventos[] = array(
                    'titulo' => $events[$i]->name,
                    'fechaInicio' => $events[$i]->startDateTime,
                    'fechaFin' => $events[$i]->endDateTime,
                    'descripcion' => $events[$i]->description,
                    'link' => $events[$i]->htmlLink
                );
            }
        }

        $votaciones = $user->votaciones()->get()->toArray();
        //PROPUESTAS DE COMPRA Y VOTACIONES
        $propuestasLista = $id->propuestas()->get();

        //$votaciones = null;
        $propuestas = null;
        $juegos = null;
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
                    'socio' => User::where('id',$value->idSocio)->first()->dimeNombre(),
                    'propuesta' => $value->propuesta,
                    'cantidad' => $value->cantidad,
                    'votado' => $vot,
                    'mp' => $MP,
                    'numVotos' => $numVotos,
                    'BGG' => $url,
            );
        }

        $numVotaciones = 0;
        $cantidad = 0;
        $numVotos = 0;
        $numSocNV = 0;
        $numSocIVNV = 0;
        // Obtener una lista de columnas
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
                $aux = $value->asignaciones()->where('idVocalia',$id->id)->get();
                if($aux->count() > 0){
                    $numSocIVNV++;
                }
            }
            $numSocIVNV = $numSocIVNV - count($numVotaciones);
        }
        //COMPRAS
        $compras = $id->compras()->get();

        //SOCIOS CON PRIVACIDAD HABILITADA
        $socios = User::where('habilitado',true)->where('privacidad',true)->get();
        $sociosEmail = User::where('habilitado',true)->where('recibirCorreos',true)->get();
        $sociosInteresados = [];
        foreach ($socios as $key => $value) {
            foreach ($value->asignaciones()->get() as $key => $value2) {
                if($value2->idVocalia == $id->id){
                    $sociosInteresados[] = $value;
                }
            }
        }
        $socIntPercent = (100 * count($sociosInteresados)) / count($socios);

        //CHART
        /*$borderColors = [
            "rgba(255, 99, 132, 1.0)",
            "rgba(22,160,133, 1.0)",
            "rgba(255, 205, 86, 1.0)",
            "rgba(51,105,232, 1.0)",
            "rgba(244,67,54, 1.0)",
            "rgba(34,198,246, 1.0)",
            "rgba(153, 102, 255, 1.0)",
            "rgba(255, 159, 64, 1.0)",
            "rgba(233,30,99, 1.0)",
            "rgba(205,220,57, 1.0)"
        ];*/
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

        $resultado = array(
            'vocaliaActual' => $id,
            'vocalias' => $vocalias,
            'vocal' => $vocal,
            'request' => $request,
            'proximosEventos' => $proximosEventos,
            'propuestas' => $propuestas,
            'compras' => $compras,
            'numVotaciones' => $numVotaciones,
            'fillColors' => $fillColors,
            'socios' => $socios,
            'sociosEmail' => $sociosEmail,
            'sociosInteresados' => $sociosInteresados,
            'socIntPercent' => $socIntPercent,
            'props' => $props,
            'vots' => $vots,
            'propsM' => $propsM,
            'votsM' => $votsM,
            'propsP' => $propsP,
            'votsP' => $votsP,
            'colores' => $colores,
            'cantPropMen' =>  $compMen,
            'cal2' => Configura::getValor('CAL2'),
            'numSocNV' => $numSocNV,
            'numSocIVNV' => $numSocIVNV,
        );
        //dd($resultado);
        return view('vocalia',$resultado);
    }

 ######   #######  ##     ## ########  ########     ###     ######     ########  ######## ########
##    ## ##     ## ###   ### ##     ## ##     ##   ## ##   ##    ##    ##     ##    ##       ##
##       ##     ## #### #### ##     ## ##     ##  ##   ##  ##          ##     ##    ##       ##
##       ##     ## ## ### ## ########  ########  ##     ##  ######     ##     ##    ##       ##
##       ##     ## ##     ## ##        ##   ##   #########       ##    ##     ##    ##       ##
##    ## ##     ## ##     ## ##        ##    ##  ##     ## ##    ##    ##     ##    ##       ##
 ######   #######  ##     ## ##        ##     ## ##     ##  ######     ########     ##       ##
    public function comprasDatatable(Vocalia $id = null){

        $compras = $id->compras()->get();
        $comprasArray = array();
        if($compras == null){
            $comprasArray = array(
                    'fecha' => null,
                    'descripcion'  => null,
                    'vocal'  => null,
                    'cuantia'  => null,
               );
        }else{
            foreach ($compras as $key => $value) {
               $comprasArray[] = array(
                    'id' => $value->id,
                    'idVocalia' => $id->id,
                    'fecha' => $value->created_at->format('Y-m-d'),
                    'descripcion'  => $value->descripcion,
                    'vocal'  => $value->vocal,
                    'cuantia'  => $value->cuantia,
               );
            }
        }
        return DataTables::of($comprasArray)->make(true);
    }


##     ##  #######  ########    ###    ########     ########  ########   #######  ########
##     ## ##     ##    ##      ## ##   ##     ##    ##     ## ##     ## ##     ## ##     ##
##     ## ##     ##    ##     ##   ##  ##     ##    ##     ## ##     ## ##     ## ##     ##
##     ## ##     ##    ##    ##     ## ########     ########  ########  ##     ## ########
 ##   ##  ##     ##    ##    ######### ##   ##      ##        ##   ##   ##     ## ##
  ## ##   ##     ##    ##    ##     ## ##    ##     ##        ##    ##  ##     ## ##
   ###     #######     ##    ##     ## ##     ##    ##        ##     ##  #######  ##
    /**
     *
     */
    public function votarPropuesta(Request $request){
        $user = Auth::user();
        $votacionesUser = $user->votaciones()->get();
        if($votacionesUser->count() > 0)
        {
            foreach ($votacionesUser as $key => $value) {
                $propuesta = Propuesta::where('id', $value->idPropuesta)->first();
                if ($propuesta!= null && $propuesta->idVocalia == $request->vocaliaActual){
                    $votacionesUser[$key]->delete();
                }
            }
        }

        if ($request->propuestaSelect != null || $request->propuestaSelect != ""){
           foreach ($request->propuestaSelect as $key => $value) {
                $votacion = Votacion::create([
                    'idSocio' => $user->id,
                    'idPropuesta' => $value,
                    'valor' => true,
                ]);
            }
        }
        $request->session()->flash('alert-success', Lang::get('errortext.OKvotos'));
        return redirect()->route('vocalia', ['id' => $request->vocaliaActual]);
    }


##     ##  #######  ########    ########  ########   #######  ########     ##     ## ########
##     ## ##     ##    ##       ##     ## ##     ## ##     ## ##     ##    ###   ### ##     ##
##     ## ##     ##    ##       ##     ## ##     ## ##     ## ##     ##    #### #### ##     ##
##     ## ##     ##    ##       ########  ########  ##     ## ########     ## ### ## ########
 ##   ##  ##     ##    ##       ##        ##   ##   ##     ## ##           ##     ## ##
  ## ##   ##     ##    ##       ##        ##    ##  ##     ## ##           ##     ## ##
   ###     #######     ##       ##        ##     ##  #######  ##           ##     ## ##
    /**
     *
     */
    public function votarPropuestaMP(Request $request){
        $user = Auth::user();
        $votacionesUser = $user->votaciones()->get();
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
        return redirect()->route('vocalia', ['id' => $request->vocaliaActual]);
    }

 ######   ##     ##    ###    ########  ########     ###       ########  ########   #######  ########
##    ##  ##     ##   ## ##   ##     ## ##     ##   ## ##      ##     ## ##     ## ##     ## ##     ##
##        ##     ##  ##   ##  ##     ## ##     ##  ##   ##     ##     ## ##     ## ##     ## ##     ##
##   #### ##     ## ##     ## ########  ##     ## ##     ##    ########  ########  ##     ## ########
##    ##  ##     ## ######### ##   ##   ##     ## #########    ##        ##   ##   ##     ## ##
##    ##  ##     ## ##     ## ##    ##  ##     ## ##     ##    ##        ##    ##  ##     ## ##
 ######    #######  ##     ## ##     ## ########  ##     ##    ##        ##     ##  #######  ##
    public function guardaPropuesta(Request $request){
        //dd($request);
        if($request->inputDesc == "" || $request->inputCant == "" || $request->inputDesc == null || $request->inputCant == null){
            $request->session()->flash('alert-danger',  Lang::get('errortext.ERRORpropuestaCamposVacios'));
            return redirect()->route('vocalia', ['id' => $request->vocaliaActual]);
        }

        $regex = '~^\d*\.?\d+$~';
        if(!preg_match($regex,$request->inputCant)){
            $request->session()->flash('alert-danger',  Lang::get('errortext.ERRORNocomas'));
            return redirect()->route('vocalia', ['id' => $request->vocaliaActual]);
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

        Log::info(Lang::get('logtext.L_VocProComp'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        Log::info(Lang::get('logtext.L_VocProp'). $propuesta->aString());

        $request->session()->flash('alert-success', Lang::get('errortext.OKpropuestaCreada'));
        return redirect()->route('vocalia', ['id' => $request->vocaliaActual]);
    }


 ######   ##     ##    ###    ########  ########     ###       ######## ##     ## ######## ##    ## ########  #######
##    ##  ##     ##   ## ##   ##     ## ##     ##   ## ##      ##       ##     ## ##       ###   ##    ##    ##     ##
##        ##     ##  ##   ##  ##     ## ##     ##  ##   ##     ##       ##     ## ##       ####  ##    ##    ##     ##
##   #### ##     ## ##     ## ########  ##     ## ##     ##    ######   ##     ## ######   ## ## ##    ##    ##     ##
##    ##  ##     ## ######### ##   ##   ##     ## #########    ##        ##   ##  ##       ##  ####    ##    ##     ##
##    ##  ##     ## ##     ## ##    ##  ##     ## ##     ##    ##         ## ##   ##       ##   ###    ##    ##     ##
 ######    #######  ##     ## ##     ## ########  ##     ##    ########    ###    ######## ##    ##    ##     #######
    /**
     * [guardaEvento description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function guardaEvento(Request $request){
       //dd($request);
       $vocaliaActual = (Vocalia::where('id', $request->vocaliaActual))->first();
        if($request->inputNombre == "" || $request->inputNombre == null){
            $request->session()->flash('alert-danger', Lang::get('errortext.ERROReventoSinDescipcion'));
            return redirect()->route('vocalia', ['id' => $request->vocaliaActual]);
        }

//FECHA DE INICIO
        if($request->fechaInicio == "" || $request->fechaInicio == null){
            $request->fechaInicio = Carbon::now()->format('d/m/Y');
        }
//HORA DE INICIO
        if($request->horaInicio == "" || $request->horaInicio == null){
            $request->horaInicio = Carbon::now()->format('H:i');
        }
//FECHA DE FIN
        if($request->fechaFin == "" || $request->fechaFin == null){
            $request->fechaFin = $request->fechaInicio;
        }
//HORA DE FIN
        if($request->horaFin == "" || $request->horaFin == null){
            $request->horaFin = (Carbon::createFromFormat('H:i',$request->horaInicio))->add('hour',2)->format('H:i');
        }


        $event = new Event;
        $event->name = $request->inputNombre. " (".Auth::user()->username.")";

        //$event->colorId = $vocaliaActual->color();
        $ini = $request->fechaInicio."-".$request->horaInicio;
        $fin = $request->fechaFin."-".$request->horaFin;
        $event->startDateTime = Carbon::createFromFormat('d/m/Y-H:i', $ini);
        $event->endDateTime = Carbon::createFromFormat('d/m/Y-H:i', $fin);
        //$event->addAttendee(['email' => Auth::user()->email]);
        //bloque para añadir participantes y enviarles un correo electrónico.
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
            $obj->sender = Auth::user()->nombre . " " . Auth::user()->primerApellido . " (usuario:" . Auth::user()->username .")";
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

        //Recogemos los Eventos del calendario y guardamos el último evento en la tabla Calendario
        $events = Event::getEventsOnCalendar($vocaliaActual->idCalendario);
        $guardaCal = Calendario::create([
            'fecha' => Carbon::createFromFormat('d/m/Y-H:i',$ini),
            'idSocio' => Auth::user()->id,
            'idVocalia' => $vocaliaActual->id,
            'eventId' => $events->last()->id
        ]);

        Log::info(Lang::get('logtext.L_Voc_NuevEvent'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        Log::info(Lang::get('logtext.L_Voc_Event'). $event->name.Lang::get('logtext.L_Voc_Encal').$vocaliaActual->idCalendario);

        $request->session()->flash('alert-success',  Lang::get('errortext.OKeventoCreado'));
        return redirect()->route('vocalia', ['id' => $request->vocaliaActual]);
    }


######## ##     ##    ###    #### ##          ##     ##  #######   ######     ###    ##
##       ###   ###   ## ##    ##  ##          ##     ## ##     ## ##    ##   ## ##   ##
##       #### ####  ##   ##   ##  ##          ##     ## ##     ## ##        ##   ##  ##
######   ## ### ## ##     ##  ##  ##          ##     ## ##     ## ##       ##     ## ##
##       ##     ## #########  ##  ##           ##   ##  ##     ## ##       ######### ##
##       ##     ## ##     ##  ##  ##            ## ##   ##     ## ##    ## ##     ## ##
######## ##     ## ##     ## #### ########       ###     #######   ######  ##     ## ########
    /**
     * [emailVocal description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function emailVocal(Request $request){
        //dd($request);
        //vocaliaActual
        $socios = null;
        $vocaliaActual= Vocalia::where('id',$request->vocaliaActual)->first();
        if(!isset($request->checkSoloFollowers)){
            //dd($request);
            $sociosLista = User::where('habilitado',true)->where('recibirCorreos',true)->get();
            foreach ($sociosLista as $key => $value) {
                //dd($value->asignaciones()->get(), $value->asignaciones()->get()->contains('idVocalia', $vocaliaActual));
                foreach ($value->asignaciones()->get() as $key => $value2) {
                    if($value2->idVocalia == $vocaliaActual->id){
                        $socios[] = $value;
                    }
                }
            }
        }else{
            foreach ($request->socioSelect as $key => $value) {
                $socios[] = User::where('id', $value)->first();
            }
        }

        //CREAMOS UN EMAIL
        //Primero las cosas obligatorias: La plantilla que se va a usar, el receptor, el email del remitente y el nombre:
        $obj = new \stdClass();
        $obj->plantilla = "Has recibido un email desde una vocalía";
        $obj->receiver = null;
        $obj->emailSender = $request->email;
        $obj->sender = Auth::user()->nombre . " " . Auth::user()->primerApellido . " (". Lang::get('text.usuario').":" . Auth::user()->username .")";
        //Ahora ponemos las variables que van a ir dentro del email:
        $obj->asunto = Lang::get('text.emailDelVocal') ." ". $vocaliaActual->nombre;
        $obj->fecha = Carbon::now()->format('d/m/Y');
        $obj->texto = $request->message;
        //Ahora enviamos el email. Si es a muchas personas hacemos un bucle for.
        foreach ($socios as $key => $socio) {
            $obj->receiver = $socio->nombre;
            Mail::to($socio->email)->send(new Email($obj));
        }
        $obj->asunto = Lang::get('text.copiaParaRemitente') . $obj->asunto;
        $obj->receiver = Auth::user()->nombre;
        Mail::to(Auth::user()->email)->send(new Email($obj));

        Log::info(Lang::get('logtext.L_Voc_Enviadoemail').$vocaliaActual->nombre.Lang::get('logtext.L_Voc_Emailx'). Auth::user()->id.') '. Auth::user()->dimeNombre());


        $request->session()->flash('alert-success',  Lang::get('errortext.emailEnviadoOK'));
        return redirect()->route('vocalia', ['id' => $request->vocaliaActual]);
    }


######## ##       #### ##     ## #### ##    ##    ###       ########  ########   #######  ########
##       ##        ##  ###   ###  ##  ###   ##   ## ##      ##     ## ##     ## ##     ## ##     ##
##       ##        ##  #### ####  ##  ####  ##  ##   ##     ##     ## ##     ## ##     ## ##     ##
######   ##        ##  ## ### ##  ##  ## ## ## ##     ##    ########  ########  ##     ## ########
##       ##        ##  ##     ##  ##  ##  #### #########    ##        ##   ##   ##     ## ##
##       ##        ##  ##     ##  ##  ##   ### ##     ##    ##        ##    ##  ##     ## ##
######## ######## #### ##     ## #### ##    ## ##     ##    ##        ##     ##  #######  ##
    /**
     * [emailVocal description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function eliminaPropuesta(Request $request){
        //dd($request);
        $data = $request->prop;
        Log::info(Lang::get('logtext.L_Voc_PropElimin'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        if(empty($data)){
            $request->session()->flash('alert-danger', Lang::get('errortext.        if($emailCogido != null && $emailCogido->id != $request->inputIdSocio){
                '));
            return redirect()->route('vocalia', ['id' => $request->vocaliaActual]);
        }
        // run foreach loop and delete each data one by one
        foreach ($data as $id){
            $prop = Propuesta::where('id',$id)->first();
            Log::info(Lang::get('logtext.L_Voc_ProplistElim').$prop->aString());
            $prop->delete();
            $vot = Votacion::where('idPropuesta',$id);
            $vot->delete();
        //DB::table("$tbl")->where("$tblid",$id)->delete();
        }

        $request->session()->flash('alert-success',  Lang::get('text.OKactualizadoLista'));
        return redirect()->route('vocalia', ['id' => $request->vocaliaActual]);
    }


 ######  ########  ########    ###    ########      ######   #######  ##     ## ########  ########     ###
##    ## ##     ## ##         ## ##   ##     ##    ##    ## ##     ## ###   ### ##     ## ##     ##   ## ##
##       ##     ## ##        ##   ##  ##     ##    ##       ##     ## #### #### ##     ## ##     ##  ##   ##
##       ########  ######   ##     ## ########     ##       ##     ## ## ### ## ########  ########  ##     ##
##       ##   ##   ##       ######### ##   ##      ##       ##     ## ##     ## ##        ##   ##   #########
##    ## ##    ##  ##       ##     ## ##    ##     ##    ## ##     ## ##     ## ##        ##    ##  ##     ##
 ######  ##     ## ######## ##     ## ##     ##     ######   #######  ##     ## ##        ##     ## ##     ##
    /**
     * [crearCompra description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function crearCompra(Request $request)
    {
        $opcionSelect = Propuesta::where('id',$request->propuesta)->first();
        $vocaliaA = Vocalia::where('id', $request->vocaliaActual)->first();
        $usuarioVocal = User::permission('permiso_vocalia_'.$vocaliaA->nombre)->role('vocal')->get()->first();

        $regex = '~^\d*\.?\d+$~';
        if(!preg_match($regex,$request->cantidadReal)){
            $request->session()->flash('alert-danger',  Lang::get('errortext.ERRORNocomas'));
            return redirect()->route('vocalia', ['id' => $request->vocaliaActual]);
        }

        if($usuarioVocal == null){
            $usuarioVocal = collect([
                (object) [
                    'nombre' => 'No hay vocal todavía',
                    'primerApellido' => '',
                    'segundoApellido' => '',
                    'email' => Lang::get('text.emailSecretario'),
                ]
            ])->first();
        }

        if($request->borrarProps == "todas"){
            $borrar = Lang::get('text.borradoTodos');
            $borraT = true;
        }else{
            $borrar = Lang::get('text.borradoUno');
            $borraT = false;
        }

        $opcionesDeCompra = "";

        //dd($opcionSelect->propuesta, $vocaliaA->nombre, $usuarioVocal);

        if(!isset($request->emailASuscriptores)){
            $propuestas = Propuesta::where('idVocalia',$request->vocaliaActual)->get();
            foreach ($propuestas as $key => $value) {
                $votacionesProp =  Votacion::where('idPropuesta', $value->id)->get();
                $opcionesDeCompra = $opcionesDeCompra. "" . $value->propuesta . " (votos:" . $votacionesProp->count() ."), ";
            }


            $obj = new \stdClass();
            $obj->plantilla = "Comunicación de compra para una vocalía";
            $obj->asunto = Lang::get('text.asuntoCompraVocalia') ." ". Vocalia::where('id',$request->vocaliaActual)->first()->nombre;
            $obj->fecha = Carbon::now()->format('d/m/Y');
            $obj->hora = $request->horaInicio;
            $obj->emailSender = Auth::user()->email;
            $obj->resultado = $opcionSelect->propuesta;
            $obj->opciones = $opcionesDeCompra;
            $obj->borrar = $borrar;
            $obj->propuestaSocio = $opcionSelect->socio()->first()->nombre . " " . $opcionSelect->socio()->first()->primerApellido;
            $obj->sender = Auth::user()->nombre . " " . Auth::user()->primerApellido . " (". Lang::get('text.usuario').":" . Auth::user()->username .")";
            $votaciones = Votacion::where('idPropuesta', $opcionSelect->id)->get();
            foreach ($votaciones as $key => $votacion) {
                $participante = User::where('id', $votacion->idSocio)->first();
                $obj->receiver = $participante->nombre;
                Mail::to($participante->email)->send(new Email($obj));
            }
            $obj->asunto = Lang::get('text.copiaParaRemitente') . $obj->asunto;
            $obj->receiver = Auth::user()->email;
            Mail::to(Auth::user()->email)->send(new Email($obj));
        }

        if($borraT){
            $propuestas = Propuesta::where('idVocalia',$request->vocaliaActual)->get();
            $votaciones =  Votacion::where('idPropuesta', $opcionSelect->id)->get();
            $compra = Compra::create([
                'descripcion' => $opcionSelect->propuesta,
                'vocalia' => $vocaliaA->nombre,
                'vocal' => $usuarioVocal->nombre . " " . $usuarioVocal->primerApellido . " " . $usuarioVocal->segundoApellido,
                'cuantia' => $request->cantidadReal,
            ]);
            $apunte = Cuenta::create([
                'fechaApunte' => Carbon::now(),
                'año'=> Carbon::now()->format('Y'),
                'tipo'=> "Gasto",
                'conceptoAgrupado' => "Otros gastos",
                'detalle' => "Compra de la Vocalía de " . $vocaliaA->nombre,
                'vocalia' => $vocaliaA->nombre,
                'cantidad' => $request->cantidadReal,
                'notas' => "",
                'pagcob'=> "No",
            ]);
            $vocaliaA->presupuesto = $vocaliaA->presupuesto - $request->cantidadReal;
            $vocaliaA->save();
            foreach ($propuestas as $key => $propuesta) {
               $propuesta->delete();
               $votaProps = Votacion::where('idPropuesta', $propuesta->id);
                foreach ($votaProps as $key => $value){
                    $value->delete();
                }
            }
        }else{
            $propuestas = Propuesta::where('idVocalia',$request->vocaliaActual)->get();
            $votaciones =  Votacion::where('idPropuesta', $opcionSelect->id)->get();
            $compra = Compra::create([
                'descripcion' => $opcionSelect->propuesta,
                'vocalia' => $vocaliaA->nombre,
                'vocal' => $usuarioVocal->nombre . " " . $usuarioVocal->primerApellido . " " . $usuarioVocal->segundoApellido,
                'cuantia' => $opcionSelect->cantidad
            ]);
            $apunte = Cuenta::create([
                'fechaApunte' => Carbon::now(),
                'año'=> Carbon::now()->format('Y'),
                'tipo'=> "Gasto",
                'conceptoAgrupado' => "Otros gastos",
                'detalle' => "Compra de la Vocalía de " . $vocaliaA->nombre,
                'vocalia' => $vocaliaA->nombre,
                'cantidad' => $opcionSelect->cantidad,
                'notas' => "Se ha comprado: ". $opcionSelect->propuesta,
                'pagcob'=> "Si",
            ]);
            $vocaliaA->presupuesto = $vocaliaA->presupuesto - $opcionSelect->cantidad;
            $vocaliaA->save();
            $propuestas->where('id',$opcionSelect->id)->first()->delete();
            foreach ($votaciones as $key => $votacion) {
                if($votacion->idPropuesta == $opcionSelect->id){
                    $votacion->delete();
                }
            }
        }

        Log::info(Lang::get('logtext.L_Voc_comprCrea'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        Log::info(Lang::get('logtext.L_Voc_compr'). $compra->aString() .Lang::get('logtext.L_Voc_enApunt').$apunte->aString());

        $request->session()->flash('alert-success',   Lang::get('errortext.OKcompraCreada'));
        return redirect()->route('vocalia', ['id' => $request->vocaliaActual]);
    }


 ######   ##     ##    ###    ########  ########     ###       ########     ###    ##    ## ######## ##          ##     ##  #######   ######     ###    ##
##    ##  ##     ##   ## ##   ##     ## ##     ##   ## ##      ##     ##   ## ##   ###   ## ##       ##          ##     ## ##     ## ##    ##   ## ##   ##
##        ##     ##  ##   ##  ##     ## ##     ##  ##   ##     ##     ##  ##   ##  ####  ## ##       ##          ##     ## ##     ## ##        ##   ##  ##
##   #### ##     ## ##     ## ########  ##     ## ##     ##    ########  ##     ## ## ## ## ######   ##          ##     ## ##     ## ##       ##     ## ##
##    ##  ##     ## ######### ##   ##   ##     ## #########    ##        ######### ##  #### ##       ##           ##   ##  ##     ## ##       ######### ##
##    ##  ##     ## ##     ## ##    ##  ##     ## ##     ##    ##        ##     ## ##   ### ##       ##            ## ##   ##     ## ##    ## ##     ## ##
 ######    #######  ##     ## ##     ## ########  ##     ##    ##        ##     ## ##    ## ######## ########       ###     #######   ######  ##     ## ########
    /** Función para guardar el Tablón de Anuncios del Vocal
     *
     */
    public function guardaPanelVocal (Request $request){
        $vocaliaA = Vocalia::where('id', $request->vocaliaActual)->first();
        //dd($request->editordata, base64_encode($request->editordata));
        $vocaliaA->tablon = base64_encode($request->editordata);
        $vocaliaA->save();
        Log::info(Lang::get('logtext.L_Voc_editTablon').$vocaliaA->nombre. Lang::get('logtext.L_Voc_editTablonSoc'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        $request->session()->flash('alert-success',   Lang::get('errortext.OKPanelActualizado'));
        return redirect()->route('vocalia', ['id' => $request->vocaliaActual]);
    }


 ######     ###    ##     ## ########  ####    ###     ######  ######## ########   ######   #######  ##     ## ########
##    ##   ## ##   ###   ### ##     ##  ##    ## ##   ##    ## ##       ##     ## ##    ## ##     ## ###   ### ##     ##
##        ##   ##  #### #### ##     ##  ##   ##   ##  ##       ##       ##     ## ##       ##     ## #### #### ##     ##
##       ##     ## ## ### ## ########   ##  ##     ##  ######  ######   ########  ##       ##     ## ## ### ## ########
##       ######### ##     ## ##     ##  ##  #########       ## ##       ##        ##       ##     ## ##     ## ##
##    ## ##     ## ##     ## ##     ##  ##  ##     ## ##    ## ##       ##        ##    ## ##     ## ##     ## ##
 ######  ##     ## ##     ## ########  #### ##     ##  ######  ######## ##         ######   #######  ##     ## ##
    /** Función para guardar el Tablón de Anuncios del Vocal
     *
     */
    public function cambiaSepComp (Request $request, $idVocalia){
        $vocaliaA = Vocalia::where('id', $idVocalia)->first();
        //dd($request->editordata, base64_encode($request->editordata));
        if($vocaliaA->separaComp == true){
            $vocaliaA->separaComp = false;
        }else{
            $vocaliaA->separaComp = true;
        }
        $vocaliaA->save();
        Log::info(Lang::get('logtext.L_Voc_cambComp').$vocaliaA->separaComp. Lang::get('logtext.L_Voc_cambiaCompSoc'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        $request->session()->flash('alert-success',   Lang::get('errortext.OKCompActualizado'));
        return redirect()->route('vocalia', ['id' => $vocaliaA->id]);
    }


######## ##       #### ##     ## #### ##    ##    ###        ######   #######  ##     ## ########  ########     ###
##       ##        ##  ###   ###  ##  ###   ##   ## ##      ##    ## ##     ## ###   ### ##     ## ##     ##   ## ##
##       ##        ##  #### ####  ##  ####  ##  ##   ##     ##       ##     ## #### #### ##     ## ##     ##  ##   ##
######   ##        ##  ## ### ##  ##  ## ## ## ##     ##    ##       ##     ## ## ### ## ########  ########  ##     ##
##       ##        ##  ##     ##  ##  ##  #### #########    ##       ##     ## ##     ## ##        ##   ##   #########
##       ##        ##  ##     ##  ##  ##   ### ##     ##    ##    ## ##     ## ##     ## ##        ##    ##  ##     ##
######## ######## #### ##     ## #### ##    ## ##     ##     ######   #######  ##     ## ##        ##     ## ##     ##
    public function eliminaCompra(Request $request, $idVocalia, $idCompra)
    {
        $vocalia = Vocalia::where('id',$idVocalia)->first();
        $compra = Compra::where('id',$idCompra)->first();

        if(Auth::user()->hasAnyPermission(['Acceso_total','permiso_vocalia_'.$vocalia->nombre])){
            $compra = Compra::where('id',$idCompra)->first();
            if($compra == null){
                $request->session()->flash('alert-success',   Lang::get('errortext.ERRORNoEncComp'));
                return redirect()->route('vocalia', ['id' => $vocalia->id]);
            }
            Log::info(Lang::get('logtext.L_CompElim1'). $compra->aString() .Lang::get('logtext.L_CompElim2').Auth::user()->id.') '. Auth::user()->dimeNombre());
            $vocalia->presupuesto = $vocalia->presupuesto + $compra->cuantia;
            $vocalia->save();
            $compra->delete();
            $request->session()->flash('alert-success',   Lang::get('errortext.OKCompElimin'));
            return redirect()->route('vocalia', ['id' => $vocalia->id]);
        }
        else{
            return view('prohibido');
        }
    }

}
