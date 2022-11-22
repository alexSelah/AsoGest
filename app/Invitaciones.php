<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use  App\User;

class Invitaciones extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha','idSocio','invitado',
    ];

    protected $dates = ['fecha'];


    public function socio()
    {
    	return $this->belongsTo('App\User');
    }

    public function aString(){
        return 'FECHA: '. $this['fecha']. '   / IDSOCIO: ' . $this['idSocio']. '   / INVITADO: ' . $this['invitado'];
    }
}
