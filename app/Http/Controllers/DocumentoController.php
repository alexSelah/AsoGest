<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Documento;
use Illuminate\Support\Facades\Storage;
use yajra\Datatables\Datatables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Nathanmac\Utilities\Parser\Facades\Parser;
use Log;
use Jenssegers\Agent\Agent;


class DocumentoController extends Controller
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
           return app('App\Http\Controllers\MovilController')->pag6();
        }
        $tiposD = null;
        $tiposDocs = Documento::distinct()->get('tipo');
        if(count($tiposDocs)<1){
            $tiposD = [Lang::get('text.tipoDoc1'), Lang::get('text.tipoDoc2')];
        }else{
            foreach ($tiposDocs as $key => $value) {
                $tiposD[] = $tiposDocs[$key]->tipo;
            }
        }

        $resultado = array(
            'request' => $request,
            'tipos' => $tiposD,
        );
        //dd($resultado);
        return view('documentos',$resultado);
    }

    /*
    8888b.   dP"Yb   dP""b8 88   88 8b    d8 888888 88b 88 888888  dP"Yb  .dP"Y8     8888b.     db    888888    db    888888    db    88""Yb 88     888888
     8I  Yb dP   Yb dP   `" 88   88 88b  d88 88__   88Yb88   88   dP   Yb `Ybo."      8I  Yb   dPYb     88     dPYb     88     dPYb   88__dP 88     88__
     8I  dY Yb   dP Yb      Y8   8P 88YbdP88 88""   88 Y88   88   Yb   dP o.`Y8b      8I  dY  dP__Yb    88    dP__Yb    88    dP__Yb  88""Yb 88  .o 88""
    8888Y"   YbodP   YboodP `YbodP' 88 YY 88 888888 88  Y8   88    YbodP  8bodP'     8888Y"  dP""""Yb   88   dP""""Yb   88   dP""""Yb 88oodP 88ood8 888888
    */
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function documentosDataTable()
    {
        $documentos = Documento::orderBy('created_at')->get();
        $docs=array();
        if(count($documentos) > 0){
            foreach ($documentos as $value) {
                $docs[] = array(
                    'id' => $value->id,
                    'tipo' => $value->tipo,
                    'fecha' => Carbon::parse($value->created_at)->format('d/M/Y'),
                    'descripcion' => $value->descripcion,
                    'nombre_fichero' => $value->nombre_fichero,
                    'nombre' => $value->nombre
                );
            }
        }
        return DataTables::of($docs)->make(true);
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
       //obtenemos el campo file definido en el formulario
       $file = $request->file('file');
       $ext = $file->getClientOriginalExtension();
       //obtenemos el nombre del archivo
       $nombreFichero = Carbon::now()->timestamp . $request->inputNombre . "." . $ext ;
       //indicamos que queremos guardar un nuevo archivo en el disco local
       $path = Storage::disk('public')->putFileAs(
            'documentos', $request->file('file'), $nombreFichero
        );
       //metemos el valor en la Base de Datos
       //
        $documento = new Documento();
        if($request->selectTipo == "opcionCustom"){
            $documento->tipo = $request->inputTipoNuevo;
        }
        else{
            $documento->tipo = $request->selectTipo;
        }
        $documento->descripcion = $request->inputDesc;
        $documento->nombre_fichero = $nombreFichero;
        $documento->nombre = $request->inputNombre;
        $documento->save();

        Log::info(Lang::get('logtext.L_newDoc'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        Log::info(Lang::get('logtext.L_NomArch'). $nombreFichero);

        $request->session()->flash('alert-info', Lang::get('errortext.OKguardado'));
        return $this->index($request);
    }

    /*
    ########   #######  ##      ## ##    ## ##        #######     ###    ########
    ##     ## ##     ## ##  ##  ## ###   ## ##       ##     ##   ## ##   ##     ##
    ##     ## ##     ## ##  ##  ## ####  ## ##       ##     ##  ##   ##  ##     ##
    ##     ## ##     ## ##  ##  ## ## ## ## ##       ##     ## ##     ## ##     ##
    ##     ## ##     ## ##  ##  ## ##  #### ##       ##     ## ######### ##     ##
    ##     ## ##     ## ##  ##  ## ##   ### ##       ##     ## ##     ## ##     ##
    ########   #######   ###  ###  ##    ## ########  #######  ##     ## ########
    */
    public function download(Request $request, Documento $doc)
    {
        //dd($doc);
        if($doc == null){
            $request->session()->flash('alert-danger', Lang::get('errortext.ERRORnoArchivo'));
            return $this->index($request);
        }
        $archivo = $doc->nombre_fichero;
        $ruta = storage_path("app/public/documentos/{$archivo}");
        //dd($ruta, $doc, $archivo, Storage::exists($archivo));
        //verificamos si el archivo existe y lo retornamos
        if (Storage::disk('documentos')->exists($archivo))
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


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Documento $doc)
    {
        //dd(Storage::disk('documentos')->exists($doc->nombre_fichero), DB::table('documentos')->where('id', '=', $doc->id)->get(), $doc);
        if($doc == null){
            $request->session()->flash('alert-danger', Lang::get('errortext.ERRORnoArchivo'));
            return $this->index($request);
        }
        //obtenemos el campo file definido en el formulario
        $archivo = $doc->nombre_fichero;
        //dd($archivo);
        $url = storage_path("app/public/documentos/{$archivo}");
        //dd($url);
        //verificamos si el archivo existe y lo retornamos
        if (Storage::disk('documentos')->exists($archivo)){
           Storage::disk('documentos')->delete($archivo);
           DB::table('documentos')->where('id', '=', $doc->id)->delete();
           $request->session()->flash('alert-info', Lang::get('errortext.delArchivo_exito'));
        }
        else{
           $request->session()->flash('alert-warning', Lang::get('errortext.ERRORnoArchivo'));
        }

        Log::info(Lang::get('logtext.L_ArchEl'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        Log::info(Lang::get('logtext.L_NomArch'). $archivo);

        return $this->index($request);
    }
}
