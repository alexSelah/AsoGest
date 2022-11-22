<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Vocalia;

class Compra extends Model
{
    protected $fillable = [
        'descripcion','vocalia','vocal','cuantia',
    ];
    
    public function vocalia()
    {
    	return $this->belongsTo('App\Vocalia','nombre', 'vocalia');
    }

    public function aString(){
    	return 'ID: '. $this['id'].'  / DESCRIPCION: '. $this['descripcion'].'  / VOCALIA:'.$this['vocalia'].'  / VOCAL:'.$this['vocal'].'  / CUANTIA:' .$this['cuantia'];
    }
}
