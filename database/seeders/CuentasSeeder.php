<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Cuenta;
use Carbon\Carbon;

class CuentasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cuenta = Cuenta::create([
			'fechaApunte' => Carbon::create('10/10/2020'),
			'año'=> '2018',
			'tipo'=> 'Gasto',
			'conceptoAgrupado' => 'Otros gastos e inversiones',
			'detalle' => 'Gasto de prueba',
			'vocalia' => 'Sin Vocalía',
			'cantidad' => -10.50,
			'notas' => 'Gasto de Prueba',
			'pagcob'=> "No",
		]);

        $cuenta = Cuenta::create([
			'fechaApunte' => Carbon::create('11/12/2020'),
			'año'=> '2018',
			'tipo'=> 'Ingreso',
			'conceptoAgrupado' => 'Otros ingresos',
			'detalle' => 'Ingreso de prueba',
			'vocalia' => 'Sin Vocalía',
			'cantidad' => 224,
			'notas' => 'Ingreso de Prueba',
			'pagcob'=> "Si",
		]);

    }
}
