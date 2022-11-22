<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Compra;
use App\Propuesta;
use App\Votacion;

class PropuestasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $propuesta = Propuesta::create([
            'idSocio' => 1,
        	'idVocalia' => 1,
        	'propuesta' => 'Compra de dados para la seccion de rol',
        	'cantidad' => 10
        ]);

        $propuesta2 = Propuesta::create([
            'idSocio' => 3,
        	'idVocalia' => 1,
        	'propuesta' => 'Compra de juego de rol D&D (Manual de Director, Monstruos y Jugador)',
        	'cantidad' => 150
        ]);

        $propuesta3 = Propuesta::create([
            'idSocio' => 8,
        	'idVocalia' => 1,
        	'propuesta' => 'Compra de juego de rol Aventuras',
        	'cantidad' => 50
        ]);

        //CREAMOS UNA COMPRA DE EJEMPLO
        $compra = Compra::create([
            'descripcion' => 'Compra de libros de rol',
        	'vocalia' => 'Rol',
        	'vocal' => 'Perico de los Palotes',
        	'cuantia' => 50
        ]);

        //ALGUNAS VOTACIONES
        $votacion = Votacion::create([
            'idSocio' => 1,
            'idPropuesta' => $propuesta->id,
            'valor' => true,
        ]);
        $votacion = Votacion::create([
            'idSocio' => 1,
            'idPropuesta' => $propuesta2->id,
            'valor' => true,
        ]);

        $votacion = Votacion::create([
            'idSocio' => 3,
            'idPropuesta' => $propuesta2->id,
            'valor' => true,
        ]);
        $votacion = Votacion::create([
            'idSocio' => 4,
            'idPropuesta' => $propuesta2->id,
            'valor' => true,
        ]);
        $votacion = Votacion::create([
            'idSocio' => 3,
            'idPropuesta' => $propuesta3->id,
            'valor' => true,
        ]);
        $votacion = Votacion::create([
            'idSocio' => 4,
            'idPropuesta' => $propuesta3->id,
            'valor' => true,
        ]);


    }
}
