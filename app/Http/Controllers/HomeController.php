<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vocalia;
use Jenssegers\Agent\Agent;
use App\User;
use App\Cuenta;
use Illuminate\Support\Facades\DB;
use App\Calendario;
use Spatie\GoogleCalendar\Event;
use App\Mail\Email;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Configura;
use Redirect;
use Log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        //dd($request);
        $user = Auth::user();
        if($user == null){
            return view('welcome');
        }

        $agent = new Agent();
        if($agent->isPhone() || $agent->isMobile()){
           return app('App\Http\Controllers\MovilController')->index($request,null,null);
        }

        $vocalias = Vocalia::all();
        $resultado = array(
            'vocalias' => $vocalias,
            'request' => $request,
            'cal1' => Configura::getValor('CAL1'),
        );
        //dd($resultado);

        return view('home',$resultado);
    }

    public function crearEventoRapido(Request $request){
        $socios = User::where('habilitado',true)->where('privacidad',true)->get();
        $vocalias = Vocalia::all();
        $resultado = array(
            'request' => $request,
            'vocalias' => $vocalias,
            'socios' => $socios,
        );

        return view('accionesrapidas/crearEvento',$resultado);
    }

    public function apunteRapido(Request $request){
        if(!(Auth::user()->hasRole(['admin','secretario','tesorero']))){
            return view('prohibido');
        }

        $socios = User::all();
        $vocalias = DB::table('vocalias')->pluck('nombre');
        $vocalias = $vocalias->tobase()->merge(['Sin Vocalía']);
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

        $resultado = array(
            'request' => $request,
            'socios' => $socios,
            'vocalias' => $vocalias->unique(),
            'ultimoAsiento' => $ultimoAsiento + 1,
            'conceptosAgrupados' => $ConceptosAgrupados->unique(),
        );

        return view('accionesrapidas/apunteRapido',$resultado);
    }

    public function guardaEventoHome(Request $request){
        Log::info(Lang::get('logtext.L_NewEventRap'). Auth::user()->id.') '. Auth::user()->dimeNombre());

        //dd($request);
        if($request->inputNombre == "" || $request->inputNombre == null){
            $request->session()->flash('alert-danger', Lang::get('errortext.ERROReventoSinDescipcion'));
            return redirect()->back();
        }

        if( $request->vocalia == 0){
            $event = new Event;
            $event->name = $request->inputNombre. " (".Auth::user()->username.")";
            $event->description = $request->descripcionEvento;
            $event->colorId = 11;
            $ini = $request->fechaInicio."-".$request->horaInicio;
            $fin = $request->fechaFin."-".$request->horaFin;
            $event->startDateTime = Carbon::createFromFormat('d/m/Y-H:i', $ini);
            $event->endDateTime = Carbon::createFromFormat('d/m/Y-H:i', $fin);
            $event->calendarId = 1;
            if(!isset($request->checkSoloJD)){
                $obj = new \stdClass();
                $obj->plantilla = "Te han invitado a un evento";
                $obj->asunto = $request->inputNombre;
                $obj->descripcion = $request->inputDesc;
                $obj->fecha = $request->fechaInicio;
                $obj->hora = $request->horaInicio;
                $obj->emailSender = Auth::user()->email;
                //dd(Auth::user());
                $obj->sender = Auth::user()->nombre . " " . Auth::user()->primerApellido . " (usuario:" . Auth::user()->username .")";
                $participantes = User::role('junta')->get();
                foreach ($participantes as $key => $participante) {
                    $socio = User::where('id',$socio)->first();
                    $obj->receiver = $participante->nombre;
                    Mail::to($participante->email)->send(new Email($obj));
                }
            }
            else{
                if($request->socioSelect != null){
                    $obj = new \stdClass();
                    $obj->plantilla = "Te han invitado a un evento";
                    $obj->asunto = $request->inputNombre;
                    $obj->descripcion = $request->descripcionEvento;
                    $obj->fecha = $request->fechaInicio;
                    $obj->hora = $request->horaInicio;
                    $obj->emailSender = Auth::user()->email;
                    //dd(Auth::user());
                    $obj->sender = Auth::user()->nombre . " " . Auth::user()->primerApellido . " (" . Auth::user()->username .")";
                    foreach ($request->socioSelect as $key => $socio) {
                        $participante = User::where('id',$socio)->first();
                        $obj->receiver = $participante->nombre;
                        Mail::to($participante->email)->send(new Email($obj));
                    }
                }
            }
            $calID = Configura::getValor('IdCalendarioImportantes');
            $event->saveOnCalendarId($calID);
        }
        else{
            $vocaliaActual = (Vocalia::where('id', $request->vocalia))->first();
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
        }


        $request->session()->flash('alert-success',  Lang::get('text.OKeventoCreado'));
        return redirect()->route('home');
    }

    /**
     * Devuelve la URL del Calendario principal publico
     * @return URL-Redirect [Configura->CALURL]
     */
    public function verCalendario(){
        return Redirect::away(Configura::getValor('CALURL'));

    }

}
