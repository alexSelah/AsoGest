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
use App\User;
use Carbon\Carbon;
use App\Invitaciones;
use Log;

class InvitacionesController extends Controller
{
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

        $users = User::all();
        foreach ($users as $user) {
            $listaUsers [] = array(
                'idSocio' => $user->id,
                'nombre' => $user->nombre,
                'apellidos' => $user->primerApellido ." ". $user->segundoApellido,
                'invitacionesRestantes' => $user->invitaciones,
            );
        }

        $resultado = array(
            'usuarios' => $listaUsers,
            'request' => $request,
        );

        return view('secretario/visorInvitaciones', $resultado);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$hoy = Carbon::now();
        if(Auth::user()->hasPermissionTo('permiso_ver_informes')){
            $invitaciones = Invitaciones::all();
        }
        else{
            $invitaciones = Invitaciones::where('idSocio', '=', Auth::user()->id)->get();
        }
        if(count($invitaciones) == 0){
            $listaInvitacion = array();
        }else{
            foreach ($invitaciones as $invitacion) {
				if($hoy->lte($invitacion->fecha)){
                    $caducada = true;
                }else{
                    $caducada = false;
                }
                $listaInvitacion[] = array(
                    'id' => $invitacion->id,
                    'idSocio' => $invitacion->idSocio,
                    'nombreSocio' => User::where('id','=',$invitacion->idSocio)->first()->nombre . " " . User::where('id','=',$invitacion->idSocio)->first()->primerApellido,
                    'fecha' => $invitacion->fecha->format('d/m/Y'),
                    'invitado' => $invitacion->invitado,
					'caducada' => $caducada,
                );
            }

        }
        return DataTables::of($listaInvitacion)->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $usuario = User::where('id', '=', $request->idSocio)->first();
        $fecha = Carbon::createFromFormat('Y-m-d',$request->fechaInvitacion);
        if( $usuario->invitaciones > 0){
            $usuario->restaInvitacion();
            $invitado = Invitaciones::create([
                'fecha' => $fecha,
                'idSocio' => $request->idSocio,
                'invitado' => $request->invitado
            ]);
            $invitado->save();
            Log::info(Lang::get('logtext.L_GastInv'). Auth::user()->id.') '. Auth::user()->dimeNombre().Lang::get('logtext.L_InvName').$request->invitado);
            $request->session()->flash('alert-info', Lang::get('errortext.OKgastaInvitacion'));
            return $this->index($request);
        }
        else{
            $request->session()->flash('alert-danger', Lang::get('errortext.ERRORnoInvitaciones'));
            return $this->index($request);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $idIvitacion)
    {
        //dd($request);
        $invitacion = Invitaciones::where('id', $idIvitacion);
        $socio = User::where('id', $invitacion->first()->idSocio)->first();
        $socio->restauraInvitacion();
        Log::info(Lang::get('logtext.L_RestInv'). Auth::user()->id.') '. Auth::user()->dimeNombre());
        Log::info('EVENTO:'. $invitacion->aString());
        $invitacion->delete();

        $request->session()->flash('alert-info', Lang::get('errortext.OKInvitaacionRestaurada'));
        return $this->index($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyAll(Request $request)
    {
        Log::info(Lang::get('logtext.L_ResetAllInv'). Auth::user()->id.') '. Auth::user()->dimeNombre());

        $socios = User::all();
        foreach ($socios as $socio) {
            $socio->reseteaInvitados($socio->id);
        }
        $request->session()->flash('alert-info', Lang::get('errortext.OKreseteoInvitacionesTodas'));
        return $this->index($request);
    }
}
