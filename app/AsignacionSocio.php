<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Vocalia;

class AsignacionSocio extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idSocio','idVocalia',
    ];

    public function socio()
    {
    	return $this->belognsTo('App\User', 'idSocio', 'id');
    }

    public function vocalia()
    {
    	return $this->belognsTo('App\Vocalia', 'idVocalia', 'id');
    }
}
