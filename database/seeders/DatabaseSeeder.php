<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /*DEBES ELEGIR ENTRE ESTE (que es el minimo para crear la Base de Datos)*/
            $this->call(MINIMO::class);


        /* O ESTE (Que tiene unos ejemplos)
            $this->call(DocumentoSeeder::class);
            $this->call(ConfiguraSeeder::class);
            $this->call(VocaliaSeeder::class);
            $this->call(RoleTableSeeder::class);
            $this->call(UserTableSeeder::class);
            $this->call(InvitacionesSeeder::class);
            $this->call(CuentasSeeder::class);
            $this->call(PropuestasSeeder::class);
            $this->call(AsignacionSocioSeeder::class);
            $this->call(CuotaSeeder::class);*/
    }
}
