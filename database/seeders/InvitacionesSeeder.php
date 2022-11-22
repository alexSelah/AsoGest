<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Invitaciones;
use Carbon\Carbon;

class InvitacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $invitacion = Invitaciones::create([
            'fecha' => Carbon::create('3/2/2020'),
        	'idSocio' => 10,
        	'invitado' => 'Invitado de prueba Fulanito de Tal'
        ]);
    }
}
