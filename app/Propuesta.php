<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Vocalia;
use App\User;
use App\Votacion;

class Propuesta extends Model
{
    protected $fillable = [
        'idSocio','idVocalia','propuesta','nombre','cantidad'
    ];

    public function vocalia(){
    	return $this->belongsTo('App\Vocalia', 'idVocalia','id');
    }

    public function socio(){
    	return $this->belongsTo('App\User', 'idSocio','id');
    }

    public function votaciones(){
    	return $this->hasMany('App\Votacion','id','idPropuesta');
    }

    public function aString(){
        return 'ID: '.$this['id'].'   / IDSOCIO: '.$this['idSocio'].'    / IDVOCALIA: '.$this['idVocalia'].'     / PROPUESTA: '.$this['propuesta'].'     / NOMBRE: '.$this['nombre'].'     / CANTIDAD: '.$this['cantidad'];
    }
}
