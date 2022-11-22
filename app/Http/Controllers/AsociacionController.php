<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use App\User;
use App\Configura;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;
use App\Mail\Email;
use Spatie\GoogleCalendar\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Container\Container;
use File;
use App\Vocalia;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use SimpleXMLElement;

class AsociacionController extends Controller
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

        $agent = new Agent();
        if($agent->isPhone() || $agent->isMobile()){
           return app('App\Http\Controllers\MovilController')->index($request,null,null);
        }

//Recuperamos los nombres de la Junta Directiva
        $presidentes = User::role('admin')->get();
        $presidente = null;
        foreach ($presidentes as $key => $value) {
           if($value->nombre != Lang::get('text.nombreAdminBacup')){
                $presidente = $value;
           }
        }
        if($presidente == null){
            $presidente = collect([
                (object) [
                    'nombre' => Lang::get('errortext.ERRORNoPresi'),
                    'primerApellido' => '',
                    'email' => Lang::get('text.emailPrincipal')
                    ,
                    'foto' => 'sinFoto.png',
                    'privacidad' => true,
                ]
            ])->first();
        }
        if($presidente->foto == null){
            $presidente->foto = "sinFoto.png";
        }
        if($presidente->privacidad == false){
            $presidente->email = Lang::get('text.emailPrincipal');
        }

        $secretario = User::role('secretario')->first();
        if($secretario == null){
            $secretario = collect([
                (object) [
                    'nombre' => Lang::get('errortext.ERRORNoSecre'),
                    'primerApellido' => '',
                    'email' => Lang::get('text.emailSecretario'),
                    'foto' => 'sinFoto.png',
                    'privacidad' => true,
                ]
            ])->first();
        }
        if($secretario->foto == null){
            $secretario->foto = "sinFoto.png";
        }
        if($secretario->privacidad == false){
            $secretario->email = Lang::get('text.emailSecretario');
        }

        $tesorero = User::role('tesorero')->first();
        if($tesorero == null){
            $tesorero = collect([
                (object) [
                    'nombre' => Lang::get('errortext.ERRORNoTeso'),
                    'primerApellido' => '',
                    'email' => Lang::get('text.emailTesorero'),
                    'foto' => 'sinFoto.png',
                    'privacidad' => true,
                ]
            ])->first();
        }
        if($tesorero->foto == null){
            $tesorero->foto = "sinFoto.png";
        }
        if($tesorero->privacidad == false){
            $tesorero->email = Lang::get('text.emailTesorero');
        }



        //DATOS PARA EL TIMELINE
        $socios = User::all();
        $vocalias = Vocalia::all();
        $fecha = Carbon::now()->format('d-m-Y');
        $texto = Lang::get('text.timelineText_3') . count($socios) . Lang::get('text.timelineText_4') . count($socios->where('habilitado', true)) . Lang::get('text.timelineText_5') . count($vocalias) . " (";
        foreach ($vocalias as $key => $vocalia) {
            $texto = $texto . $vocalia->nombre . ", ";
        };
        $texto = substr($texto, 0, -2);
        $texto = $texto . ")";

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

        $resultado= array(
            'request' => $request,
            'presidente' => $presidente,
            'tesorero' => $tesorero,
            'secretario' => $secretario,
            'socios' => $socios,
            'texto' => $texto,
            'fecha' => $fecha,
            'cal2' => Configura::getValor('CAL2'),
            'cal3' => Configura::getValor('CAL3'),
            'calurl' => Configura::getValor('CALURL'),
            'codQR' => Configura::getValor('CALURL'),
            'grupochat' => $grupochat,
        );
//dd($resultado);
        $agent = new Agent();
        if($agent->isPhone() || $agent->isMobile()){
           return view('movil/homeMovil',$resultado);
        }

        return view('asociacion/asociacion',$resultado);
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $urlXML = asset("bgg/Bgg.xml");
        $juegosBGG = simplexml_load_file($urlXML) or die(Lang::get('errortext.ERRORNoLectXML'));

        foreach ($juegosBGG as $key => $value) {
            $juegos[] = array(
                'thumbnail' => (string)$value->thumbnail,
                'name' => (string)$value->name,
                'yearpublished' => (string)$value->yearpublished,
                'minplayers' => (string)$value->stats['minplayers'],
                'maxplayers' => (string)$value->stats['maxplayers'],
                'playingtime' => (string)$value->stats['playingtime']."min.",
                'objectid' => (string)$value['objectid'],
            );
        }
        return DataTables::of($juegos)->make(true);

    }

    /*
     ######  ########  ########    ###    ########  ##     ## #### ########  #######
    ##    ## ##     ## ##         ## ##   ##     ## ##     ##  ##     ##    ##     ##
    ##       ##     ## ##        ##   ##  ##     ## ##     ##  ##     ##    ##     ##
    ##       ########  ######   ##     ## ########  #########  ##     ##    ##     ##
    ##       ##   ##   ##       ######### ##   ##   ##     ##  ##     ##    ##     ##
    ##    ## ##    ##  ##       ##     ## ##    ##  ##     ##  ##     ##    ##     ##
     ######  ##     ## ######## ##     ## ##     ## ##     ## ####    ##     #######
    */
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crearHito(Request $request)
    {
        Log::info(Lang::get('logtext.L_HitoCre'). Auth::user()->id.') '. Auth::user()->dimeNombre());

        $archivo = base_path() . '/resources/views/timeline.blade.php';

        //Recogemos las variables
        $colorHito = $request->inputColorHito;
        if($colorHito == null){
            $colorHito = "info";
        }
        $inputIcono = $request->inputIcono;
        if($inputIcono == null || $inputIcono == ""){
            $inputIcono = "&#9733;";
        }
        $inputTituloHito = $request->inputTituloHito;
        if($inputTituloHito == null){
            $inputTituloHito = "";
        }
        $inputSubtituloHito = $request->inputSubtituloHito;
        if($inputSubtituloHito == null){
            $inputSubtituloHito = "";
        }
        $inputTextoHito = $request->inputTextoHito;
        if($inputTextoHito == null){
            $inputTextoHito = "";
        }
        $cont = null;
        foreach(file($archivo) as $line) {
            $cont[] = $line;
            if($line == "{{--IZQUIERDA--}}" || $line == "{{--IZQUIERDA--}}\n"){
                /*<li>
                    <div class="timeline-badge info">&#128406;</div>
                    <div class="timeline-panel">
                      <div class=\"timeline-heading\">
                        <h4 class=\"timeline-title\">".$request->inputTituloHito."</h4>
                        <p><small class=\"text-muted\">".$request->inputSubtituloHito."</small></p>
                      </div>
                      <div class=\"timeline-body\">
                        <p>".$request->inputTextoHito."</p>
                      </div>
                    </div>
                  </li>
                  {{{{--DERECHA--}}}}*/

                // BORRAMOS LAS 6 ULTIMAS LINEAS
                for ($i=0; $i < 3; $i++) {
                    $lines = file($archivo);
                    $last = sizeof($lines) - 1 ;
                    unset($lines[$last]);
                    $fp = fopen($archivo, 'w');
                    fwrite($fp, implode(' ', $lines));
                    fclose($fp);
                }
                //ESCRIBIMOS
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "<li>");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "  <div class=\"timeline-badge ".$colorHito. "\">".$inputIcono ."</div>");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "  <div class=\"timeline-panel\">");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "    <div class=\"timeline-heading\">");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "      <h4 class=\"timeline-title\">".$inputTituloHito."</h4>");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "      <p><small class=\"text-muted\">".$inputSubtituloHito."</small></p>");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "    </div>");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "    <div class=\"timeline-body\">");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "      <p>".$inputTextoHito."</p>");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "    </div>");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "  </div>");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "</li>");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "</ul>");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "</div>");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "{{--DERECHA--}}");

            }
            elseif($line == "{{--DERECHA--}}" || $line == "{{--DERECHA--}}\n") {
                /*<li class=\"timeline-inverted\">
                    <div class=\"timeline-badge danger\">. $request->inputIcono ."</div>
                    <div class=\"timeline-panel\">
                      <div class=\"timeline-heading\">
                        <h4 class="timeline-title">".$request->inputTituloHito."</h4>
                        <p><small class="text-muted">".$request->inputSubtituloHito."</small></p>
                      </div>
                      <div class="timeline-body">
                        <p>".$request->inputTextoHito."</p>
                      </div>
                    </div>
                  </li>*/
                // BORRAMOS LAS 6 ULTIMAS LINEAS
                for ($i=0; $i < 3; $i++) {
                    $lines = file($archivo);
                    $last = sizeof($lines) - 1 ;
                    unset($lines[$last]);
                    $fp = fopen($archivo, 'w');
                    fwrite($fp, implode(' ', $lines));
                    fclose($fp);
                }
                //ESCRIBIMOS
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "<li class=\"timeline-inverted\">");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "  <div class=\"timeline-badge ".$colorHito."\">". $inputIcono ."</div>");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "  <div class=\"timeline-panel\">");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "    <div class=\"timeline-heading\">");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "      <h4 class=\"timeline-title\">".$inputTituloHito."</h4>");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "      <p><small class=\"text-muted\">".$inputSubtituloHito."</small></p>");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "    </div>");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "    <div class=\"timeline-body\">");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "      <p>".$inputTextoHito."</p>");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "    </div>");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "  </div>");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "</li>");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "</ul>");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "</div>\n");
                Storage::disk('timeline')->append('/resources/views/timeline.blade.php', "{{--IZQUIERDA--}}");

            }
        }
        $request->session()->flash('alert-success',  Lang::get('errortext.OKHitoCreado'));
        return redirect()->route('asociacion', $request);
    }


    /*
       db     dP""b8 888888 88   88    db    88     88 8888P    db    88     88   88 8888b.   dP"Yb  888888 888888  dP""b8    db
      dPYb   dP   `"   88   88   88   dPYb   88     88   dP    dPYb   88     88   88  8I  Yb dP   Yb   88   88__   dP   `"   dPYb
     dP__Yb  Yb        88   Y8   8P  dP__Yb  88  .o 88  dP    dP__Yb  88  .o Y8   8P  8I  dY Yb   dP   88   88""   Yb       dP__Yb
    dP""""Yb  YboodP   88   `YbodP' dP""""Yb 88ood8 88 d8888 dP""""Yb 88ood8 `YbodP' 8888Y"   YbodP    88   888888  YboodP dP""""Yb
    */
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function actualizaLudoteca(Request $request)
    {
        Log::info(Lang::get('logtext.L_ActLud'). Auth::user()->id.') '. Auth::user()->dimeNombre());

        $archivoBGG = asset("bgg/Bgg.xml");

		File::delete(public_path().'\bgg\Bgg.xml');

        $urlXML = Configura::getValor('URLBGG');

        $juegosBGG = simplexml_load_file($urlXML);
        sleep(30);
        // Initialize a file URL to the variable
        $url = Configura::getValor('URLBGG');
        $destination_folder = public_path().'\bgg';
        $newfname = $destination_folder .'\Bgg.xml'; //set your file ext
        $file = fopen ($url, "rb");
        if ($file) {

          $newf = fopen ($newfname, "a"); // to overwrite existing file
          if ($newf)
          while(!feof($file)) {
            fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
          }
        }
        if ($file) {
          fclose($file);
        }
        if ($newf) {
          fclose($newf);
        }

        $request->session()->flash('alert-success',  Lang::get('errortext.OKLudotecaActualizada'));
        return redirect()->route('asociacion', $request);

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
       //dd($request);
       Log::info(Lang::get('logtext.L_NewEven'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        if($request->inputNombre == "" || $request->inputNombre == null){
            $request->session()->flash('alert-danger', Lang::get('errortext.ERROReventoSinDescipcion'));
            return redirect()->route('asociacion', $request);
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
        $event->description = $request->descripcionEvento;
        $event->colorId = 11;
        $ini = $request->fechaInicio."-".$request->horaInicio;
        $fin = $request->fechaFin."-".$request->horaFin;
        $event->startDateTime = Carbon::createFromFormat('d/m/Y-H:i', $ini);
        $event->endDateTime = Carbon::createFromFormat('d/m/Y-H:i', $fin);
        //$event->addAttendee(['email' => Auth::user()->email]);
        //
        $event->calendarId = 1;
        //bloque para añadir participantes y enviarles un correo electrónico.
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
                $socio = Auth::user();
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
                $obj->sender = Auth::user()->nombre . " " . Auth::user()->primerApellido . " (usuario:" . Auth::user()->username .")";
                foreach ($request->socioSelect as $key => $socio) {
                    $participante = User::where('id',$socio)->first();
                    $obj->receiver = $participante->nombre;
                    Mail::to($participante->email)->send(new Email($obj));
                }
            }
        }
        $calID = Configura::getValor('IdCalendarioImportantes');
        $event->saveOnCalendarId($calID);

        Log::info(Lang::get('logtext.L_EvName'). $event->name. Lang::get('logtext.L_EnCal').$calID);

        $request->session()->flash('alert-success',  Lang::get('errortext.OKeventoCreado'));
        return redirect()->route('asociacion', $request);
    }

    /*
    88""Yb 888888 .dP"Y8 888888 888888 888888    db    88  88 88 888888  dP"Yb  .dP"Y8
    88__dP 88__   `Ybo." 88__     88   88__     dPYb   88  88 88   88   dP   Yb `Ybo."
    88"Yb  88""   o.`Y8b 88""     88   88""    dP__Yb  888888 88   88   Yb   dP o.`Y8b
    88  Yb 888888 8bodP' 888888   88   888888 dP""""Yb 88  88 88   88    YbodP  8bodP'
    */
    /**
     * Restaura la página de Hitos a su estado original.
     *
     * @return \Illuminate\Http\Response
     */
    public function reseteaHitos(Request $request)
    {
        Log::info(Lang::get('logtext.L_RestHit'). Auth::user()->id.') '. Auth::user()->dimeNombre());

        $archivoBackup = base_path() . '/resources/views/BACKUPtimeline.blade.php';
        $archivo = base_path() . '/resources/views/timeline.blade.php';

        if(File::exists($archivo)){
            File::delete($archivo);
        }

        $success = \File::copy($archivoBackup,$archivo);

        //dd($success);
        if(!$success){
            $request->session()->flash('alert-danger',  Lang::get('errortext.ERRORrestoreHito'));
            return redirect()->route('asociacion', $request);
        }else{
            $request->session()->flash('alert-success',  Lang::get('errortext.OKrestoreHito'));
            return redirect()->route('asociacion', $request);
        }

    }

}
