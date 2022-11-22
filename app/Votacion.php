<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Propuesta;

class Votacion extends Model
{
    protected $fillable = [
        'idSocio','idPropuesta','valor'
    ];

    public function socio(){
    	return $this->belongsTo('App\User','idSocio','id');
    }

    public function propuesta(){
    	return $this->belongsTo('App\Propuesta','idPropuesta','id');
    }

    public function aString(){
    	return "ID: ".$this['id']."     IDSOCIO: ".$this['idSocio']."     IDPROPUESTA: ".$this['idPropuesta']."    VALOR: ".$this['valor'];
    }
}
