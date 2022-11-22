<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\AsignacionSocio;


class AsignacionSocioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $asignacion = AsignacionSocio::create([
        	'idSocio' => 1,
        	'idVocalia' => 1,
        ]);
    }
}
