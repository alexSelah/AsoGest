<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Calendario extends Model
{
    protected $fillable = [
        'idSocio','idVocalia', 'eventId', 'fecha'
    ];

    public function vocalia()
    {
    	return $this->belongsTo('App\Vocalia','idVocalia', 'id');
    }

    public function socio()
    {
    	return $this->belongsTo('App\User', 'idSocio','id');
    }

    public static function limpiaCalendario(){
        $hoy = Carbon::now();
        $eventos = Calendario::all();
        foreach ($eventos as $key => $value) {
            if($hoy->gt($value->fecha)){
                Calendario::where('id',$value->id)->delete();
            }
        }
        return true;
    }
}
