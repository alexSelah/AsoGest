<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\DB;
use App\Vocalia;
use App\Configura;
use App\Cuota;
use App\Propuesta;
use App\TiposCuota;
use App\Votacion;
use Image;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;
use App\User;
use File;
use Log;

class ConfiguraController extends Controller
{
    /*
    88 88b 88 8888b.  888888 Yb  dP
    88 88Yb88  8I  Yb 88__    YbdP
    88 88 Y88  8I  dY 88""    dPYb
    88 88  Y8 8888Y"  888888 dP  Yb
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
        $vocalias = Vocalia::all();

        $configuraciones = Configura::all()->toArray();

        //Rellenamos los posibles colores que puede haber para una vocalía. El número 11 se reserva para eventos de la Asociación. Estos son los posibles colores y sus asignaciones por defecto
        //Color ID          Color Name          Hex Code        Sample
        // 1                   Lavender            #7986cb         violeta
        // 2                   Sage                #33b679         verde claro L
        // 3                   Grape               #8e24aa         morado
        // 4                   Flamingo            #e67c73         naranja-rojo
        // 5                   Banana              #f6c026         amarillo
        // 6                   Tangerine           #f5511d         naranja
        // 7                   Peacock             #039be5         azul claro (es el que pone por defecto si no se escoge uno)
        // 8                   Graphite            #616161         gris
        // 9                   Blueberry           #3f51b5         morazo-azulado
        // 10                  Basil               #0b8043         verde oscuro
        // 11                  Tomato              #d60000         rojo -> PARA EVENTOS DE LA ASOCIACIÓN
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
            "10" => "#0b8043"
        );
        $coloresValidos = $colores; //Colores que quedan disponibles cuando quitamos los que ya están usados.
        $vocalias = Vocalia::all();
        foreach ($vocalias as $key => $vocalia) {
            foreach($coloresValidos as $key => $colorHex){
                  if($key == $vocalia->color){
                       unset($coloresValidos[$key]);
                  }
             }
        }

        $cuotas = TiposCuota::get();

        $resultado = array(
        	'configuras' => $configuraciones,
            'vocalias' => $vocalias,
        	'request' => $request,
            'colores' => $colores,
            'cuotas' => $cuotas,
            'coloresValidos' => $coloresValidos,
        );
        //dd($resultado);
        return view('configura', $resultado);
    }

    /*
    Yb    dP 88 .dP"Y8  dP"Yb  88""Yb
     Yb  dP  88 `Ybo." dP   Yb 88__dP
      YbdP   88 o.`Y8b Yb   dP 88"Yb
       YP    88 8bodP'  YbodP  88  Yb
    */
    public function visor()
    {
        $user = Auth::user();
        if($user == null){
            return view('welcome');
        }

        if(!(Auth::user()->hasRole(['admin','secretario','tesorero']))){
            return view('prohibido');
        }

        return app('Rap2hpoutre\LaravelLogViewer\LogViewerController')->index();
    }

    /**
     * Elimina un elemento de un array multidimensional
     * @param  [type] $array [Array que contiene el valor a buscar]
     * @param  [type] $key   [clave a buscar]
     * @param  [type] $value [valor]
     * @return [type]        [arraay con el valor eliminado]
     */
    public function removeElementWithValue($array, $key, $value){
         foreach($array as $subKey => $subArray){
              if($subArray[$key] == $value){
                   unset($array[$subKey]);
              }
         }
         return $array;
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
    public function store(Request $request)
    {
        //dd($request);
        $ids = collect($request['id']);
        $descripcion = collect($request['descripcion']);
        $valorNumero = collect($request['valorNumero']);
        $valorTexto= collect($request['valorTexto']);
        $longitud = $ids->count();

        //dd($ids, $descripcion, $valorTexto, $valorNumero, $longitud);
        $i = 0;
        for ($i=0; $i < $longitud; $i++) {
            $config = Configura::find($ids->get($i));
            $config->descripcion = $descripcion->get($i);
            $config->valorNumero = $valorNumero->get($i);
            $config->valorTexto = $valorTexto->get($i);
            $config->save();
        }

        //COMPROBAMOS NUEVAS VOCALIAS
        if($request->crearVocaliaCheck){
            $posVocalia = Vocalia::where('nombre',$request->inputNombreVocalia)->first();
            if($posVocalia != null){
                $request->session()->flash('alert-danger', Lang::get('errortext.ERRORconfigVocaliaDupli'));
                return $this->index($request);
            }
            if($request->inputNombreVocalia == "" || $request->inputNombreVocalia == null || $request->inputDescVocalia == "" || $request->inputDescVocalia == null){
                $request->session()->flash('alert-danger', Lang::get('errortext.ERRORconfigNoVocalia'));
                return $this->index($request);
            }
            else{
                if($request->hasFile('inputImagenVocalia')){
                    $imagenVocalia = $request->file('inputImagenVocalia');
                    $nombreFichero = $imagenVocalia->getClientOriginalName();
                    Image::make($imagenVocalia)->resize(null,340, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->save(public_path('/images/'.$nombreFichero));
                    Storage::disk('public')->delete('images/' . $nombreFichero);
                }else{
                    $nombreFichero = "sinImagen.png";
                }
                $vocalia = Vocalia::create([
                    'nombre' => $request->inputNombreVocalia,
                    'descripcion' => $request->inputDescVocalia,
                    'presupuesto' => 0,
                    'color' => $request->selectColor,
                    'imagen' => $nombreFichero,
                    'idCalendario' => $request->idCalendario
                ]);
                $nombre = $vocalia->nombre;
                $permission = Permission::create(['name' => 'permiso_vocalia_'.$nombre]);
            }
        }

        //COMPROBAMOS SI QUIERE BORAR VOCALIAS
        if($request->estoySeguro){
            $vocaliaDestroy = Vocalia::find($request->vocaliaDestroy);
            if($vocaliaDestroy == null){
                $request->session()->flash('alert-danger', Lang::get('errortext.ERRORnoVocaliaSelectConfig'));
                return $this->index($request);
            }
            if($vocaliaDestroy->imagen != "sinImagen.png"){
                $path = public_path() . "\images\\" .$vocaliaDestroy->imagen;
                File::delete($path);
            }
            //Eliminamos el permiso y lo desasignamos a los roles (voal y admin)
            $permission = Permission::where(['name' => 'permiso_vocalia_'.$vocaliaDestroy->nombre])->first();
            if($permission != null){
                $users = User::permission($permission->name)->get();
                foreach ($users as $value) {
                   $value->revokePermissionTo($permission);
                }
                $permission->delete();
            }

            //Eliminamos Propuestas y votaciones asociadas a esas propuestas
            $propuestas = Propuesta::where('idVocalia', $vocaliaDestroy->id)->get();
            foreach ($propuestas as $key => $value) {
                //Eliminamos Votaciones
                $votaciones = Votacion::where('idPropuesta', $value->id)->get();
                foreach ($votaciones as $key => $vot) {
                   $vot->delete();
                }
               $value->delete();
            }
            // Eliminamos la Vocalía
            $vocaliaDestroy->delete();
        }

        //GUARDAMOS EDICION DE CUOTAS
        $i = 0;
        $tpids = collect($request['TPid']);
        $tpnombres = collect($request['TPnombre']);
        $tpdescripcions = collect($request['TPdescripcion']);
        $tpcantidades= collect($request['TPcantidad']);
        $tpmeses= collect($request['TPmeses']);
        $tplongitud = $tpids->count();
        for ($i=0; $i < $tplongitud; $i++) {
            $tp = TiposCuota::find($tpids->get($i));
            $tp->nombre = $tpnombres->get($i);
            $tp->descripcion = $tpdescripcions->get($i);
            $tp->cantidad = $tpcantidades->get($i);
            $tp->meses = $tpmeses->get($i);
            $tp->save();
        }

        //COMPROBAMOS NUEVAS CUOTAS
        if($request->crearCuotaCheck){
            if($request->inputNombrecuota == "" || $request->inputNombrecuota == null || $request->inputDescCuota == "" || $request->inputDescCuota == null || $request->inputCantCuota == null || $request->inputCantCuota == null){
                $request->session()->flash('alert-danger', Lang::get('errortext.ERRORconfigNoCuota'));
                return $this->index($request);
            }
            else{
                $tipocuota = TiposCuota::create([
                    'nombre' => $request->inputNombrecuota,
                    'descripcion' => $request->inputDescCuota,
                    'cantidad' => $request->inputCantCuota,
                    'meses' => $request->inputMesescuota,
                ]);
            }
        }

        //COMPROBAMOS SI QUIERE BORAR CUOTAS
        if($request->estoySeguroCuota){
            $CuotaDestroy = TiposCuota::find($request->cuotaDestroy)->first();
            if($CuotaDestroy == null){
                $request->session()->flash('alert-danger', Lang::get('errortext.ERRORnoCuotaSelectConfig'));
                return $this->index($request);
            }
            $CuotaDestroy->delete();
        }

        Log::info(Lang::get('logtext.L_actConfT'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        $request->session()->flash('alert-info', Lang::get('errortext.OKguardado'));
        return $this->index($request);
    }


}
