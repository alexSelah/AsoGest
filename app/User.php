<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\AsignacionSocio;
use App\Cuota;
use App\Configura;
use App\Propuesta;
use App\Votacion;
use App\Calendario;
use App\Invitaciones;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fnacimiento','altaSocio', 'bajaSocio' ,'numSocio','nombre','primerApellido','segundoApellido','DNI', 'email', 'username', 'direccion', 'localidad', 'provincia', 'codPostal', 'telefono', 'foto', 'invitaciones', 'habilitado', 'accesoDrive','accesoJunta', 'recibirCorreos', 'privacidad', 'notas', 'password',
    ];

    protected $dates = ['fnacimiento','altaSocio', 'bajaSocio'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function aString(){
         return 'ID: '. $this['id'].'  / NOMBRE: '. $this['nombre'].'  / PRIMERAPELLIDO:'.$this['primerApellido'].'  / SEGUNDOAPELLIDO:'.$this['segundoApellido'].'  / DNI:' .$this['DNI'].'  / EMAIL:' .$this['email'].'  / USERNAME:' .$this['username'].'  / FNACIMIENTO:' .$this['fnacimiento'].'  / ALTASOCIO:' .$this['altaSocio'].'  / BAJASOCIO:'.$this['bajaSocio'].'  / SEXO:'.$this['sexo'].'  / DIRECCION:'.$this['direccion'].'  / LOCALIDAD:'.$this['localidad'].'  / PROVINCIA:'.$this['provincia'].'  / TELEFONO:'.$this['telefono'].'  / FOTO:'.$this['foto'].'  / ACCESOJUNTA:'.$this['accesoJunta'].'  / INVITACIONES:'.$this['invitaciones'].'  / HABILITADO:'.$this['habilitado'].'  / ACCESODRIVE:'.$this['accesoDrive'].'  / PRIVACIDAD:'.$this['privacidad'].'  / RECIBIRCORREOS:'.$this['recibirCorreos'];
    }

    public function habilitado()
    {
        if ($this['habilitado']) {
            return true;
        }
        return false;
    }

    public function privacidad()
    {
        if ($this['privacidadInfo']) {
            return true;
        }
        return false;
    }

    public function correos()
    {
        if ($this['recibirCorreos']) {
            return true;
        }
        return false;
    }

    public function tieneAccesoDrive()
    {
        if ($this['accesoDrive']!=null && $this['accesoDrive'] == true) {
            return true;
        }
        return false;
    }

    public function tieneAccesoJunta()
    {
        if ($this['accesoJunta'] !=null && $this['accesoJunta'] == true) {
            return true;
        }
        return false;
    }

    public function tipoUltimaCuota()
    {
        $tipoCuota = Cuota::where('idSocio', $this['id'])->last()->first();
        if($tipoCuota == null){
            return "especial";
        }
        return $tipoCuota->tipoCuota;
    }

    public function ultimaCuota()
    {
        $cuota = Cuota::where('idSocio', $this['id'])->orderBy('fechaCuota','desc')->get()->first();
        return $cuota;

    }

    public function renuevaCuota()
    {
        $cuota = Cuota::where('idSocio', $this['id'])->get()->last();
        if($cuota->tipoCuota == null){
            return null;
        }
        elseif($cuota->tipoCuota == 'anual'){
            return (new Carbon($cuota->fechaCuota))->addMonths(12);
        }
        elseif($cuota->tipoCuota == 'semestral'){
            return (new Carbon($cuota->fechaCuota))->addMonths(6);
        }
        elseif($cuota->tipoCuota == 'trimestral'){
            return (new Carbon($cuota->fechaCuota))->addMonths(3);
        }
        else{
            return (new Carbon($cuota->fechaCuota))->addMonths(1);
        }
    }

    public function cuotas()
    {
        return $this->hasMany('App\Cuota','idSocio','id');
    }

    public function asignaciones()
    {
        return $this->hasMany('App\AsignacionSocio','idSocio','id');
    }

    public function asignacionesVocalia($idVocalia)
    {
        return $this->hasMany('App\AsignacionSocio','idSocio','id')->where('idVocalia',$idVocalia)->get();
    }

    public function restaInvitacion(){
        User::where('id', '=' ,$this->id)->update(['invitaciones' => $this->invitaciones-1]);
    }

    public function restauraInvitacion(){
        User::where('id', '=' ,$this->id)->update(['invitaciones' => $this->invitaciones+1]);
    }

    public function dimeNombre(){
        return $this['nombre'] . " " .  $this['primerApellido'] . " " .  $this['segundoApellido'];

    }

    public function reseteaInvitados($idSocio = null){
        if ( $idSocio == null){
            $idSocio = Auth::user()->id;
        }
        $socio = User::where('id', $idSocio)->first();
        $tipoCuota = Cuota::where('idSocio', $idSocio)->get()->last();
        if($tipoCuota == null){
            $socio->invitaciones = 0;
            $socio->save();
        }else{
            if($tipoCuota->tipoCuota == 'anual'){
                $socio->invitaciones = intval(Configura::getValor('IA'));
                $socio->save();
            }
            elseif($tipoCuota->tipoCuota == 'semestral'){
                $socio->invitaciones = intval(Configura::getValor('IS'));
                $socio->save();
            }
            elseif($tipoCuota->tipoCuota == 'trimestral'){
                $socio->invitaciones = intval(Configura::getValor('IT'));
                $socio->save();
            }
            else{
                $socio->invitaciones = 1;
                $socio->save();
            }
        }
        $invitaciones = Invitaciones::where('idSocio', $idSocio)->get();
        if($invitaciones != null ){
            foreach ($invitaciones as $key => $value) {
                 Invitaciones::where('id', $value->id)->delete();
            }
        }
    }

    public function propuesta(){
        return $this->hasMany('App\Propuesta','idSocio','id');
    }

    public function votaciones(){
        return $this->hasMany('App\Votacion','idSocio','id');
    }

    public function haVotado(Votacion $idV){
        if ($this->votaciones->where('id', $idV)){
            return true;
        }
        return false;
    }

    public function queVota(Votacion $idV){
        if ($this->votaciones->where('id', $idV)){
            return $this->votaciones->where('id', $idV)->valor;
        }
        return false;
    }

    public function eventos(){
        return $this->hasMany('App\Calendario', 'idSocio','id');
    }
}
