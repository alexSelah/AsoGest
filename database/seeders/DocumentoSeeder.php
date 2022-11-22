<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Documento;

class DocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $doc = Documento::create([
            'tipo' => 'Documentos Generales',
            'descripcion' => 'Instrucciones para la instalación de AsoGest',
            'nombre' => 'Instrucciones ASOGEST',
        	'nombre_fichero' => 'Instrucciones ASOGEST.pdf'
        ]);


    }
}
