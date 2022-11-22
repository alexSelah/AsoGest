<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Vocalia;

class VocaliaColor extends Model
{
    protected $fillable = [
          'nombre','descripcion','presupuesto','imagen'
    ];

    public function vocalia(){
    	return $this->belongsTo('App\Vocalia','idVocalia','id');
    }
}
