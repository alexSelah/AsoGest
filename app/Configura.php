<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configura extends Model
{
    public static function getValor($nombre)
    {
        //$configura = self::where('nombre', '=', $nombre)->get();
        if(Configura::where('nombre', $nombre)->pluck('ValorNumero')->first() != null){
        	return Configura::where('nombre',$nombre)->pluck('ValorNumero')->first();
        }
        if(Configura::where('nombre',$nombre)->pluck('valorTexto')->first() != null){
        	return Configura::where('nombre',$nombre)->pluck('valorTexto')->first();
        }
        return null;
    }
}
