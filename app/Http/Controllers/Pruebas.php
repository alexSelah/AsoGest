<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\Email;
use Illuminate\Support\Facades\Mail;
use App\Configura;
use Carbon\Carbon;
use Helper;
use Log;
use App\User;
use App\Cuota;
use App\Vocalia;
use App\Cuenta;
use App\Invitaciones;


class Pruebas extends Controller
{
    //CONTROLADOR SOLO PARA PRUEBAS. BORRAR PARA PRODUCCIÓN

    public function pruebas ()
    {

        $doc = new \DOMDocument();
        $doc->load( Configura::getValor('URLBGG') );
        $games = $doc->getElementsByTagName( "boardgame" );
        foreach( $games as $game )
        {
            $namegame = $game->getElementsByTagName( "name" );
            $name = $namegame->item(0)->nodeValue;
            $ypubli = $game->getElementsByTagName( "yearpublished" );
            $yearpublished = $ypubli->item(0)->nodeValue;
        }
        dd($games );

        dd("ENTORNO DE PRUEBAS   ");
        //

    	return view('wellcome');
    }
}
