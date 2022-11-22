<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Vocalia;
use App\User;
use App\Cuota;

class VocaliaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vocalia = Vocalia::create([
            'nombre' => 'Rol',
        	'descripcion' => 'Vocalía de juegos de Rol, roles ocultos, rol en vivo. Todo lo relacionado con la interpretación deun PJ en una mesa de Juego. D&D, Vampiro, Pathfinder, Resurgir del Dragón, etc...',
        	'presupuesto' => 0,
            'imagen' => 'rol.jpg',
            'color' => 5,
            'idCalendario' => 'hunqjn1pmanff81se1hrjj93i4@group.calendar.google.com'
        ]);
        $vocalia = Vocalia::create([
            'nombre' => 'Mesa',
        	'descripcion' => 'Vocalía de juegos de Mesa. Cartas, eurogames, gestión de recursos. Todo lo que se juegue en una mesa y no sea rol...',
        	'presupuesto' => 0,
            'imagen' => 'mesa.jpg',
            'color' => 2,
            'idCalendario' => 'v8ivo4dbrpl85kbcm6gclnf27g@group.calendar.google.com'
        ]);
        $vocalia = Vocalia::create([
            'nombre' => 'Miniaturas',
        	'descripcion' => 'Vocalía de juegos de Miniaturas y Wargames. Warhammer, Kill Team, y cosas que se relacionen con juegos históricos o de fantasía y que incluyan miniaturas.',
        	'presupuesto' => 0,
            'imagen' => 'minis.jpg',
            'color' => 8,
            'idCalendario' => 't839v7uhh0ulcun44m7p6fohgg@group.calendar.google.com'
        ]);
    }
}
