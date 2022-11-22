<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Cuota;
use Carbon\Carbon;
use App\User;
use App\Cuenta;
use App\TiposCuota;

class CuotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cuotaMensual = TiposCuota::create([
            'nombre' => "Mensual",
            'descripcion' => "Cuota mensual, se cobra el día 1 de cada mes",
            'meses'=> 1,
            'cantidad' => 10,
            'invitaciones' => 1,
        ]);
        $cuotaTrimes = TiposCuota::create([
            'nombre' => "Trimestral",
            'descripcion' => "Cuota Trimestral, se cobra el 1 de enero, abril,julio y septiembre",
            'meses'=> 3,
            'cantidad' => 30,
            'invitaciones' => 2,
        ]);
        $cuotaAnual = TiposCuota::create([
            'nombre' => "Anual",
            'descripcion' => "Cuota Anual, se cobra cuando renueva el socio",
            'meses'=> 12,
            'cantidad' => 120,
            'invitaciones' => 6,
        ]);
        $cuotaAnualEsp = TiposCuota::create([
            'nombre' => "Reducida",
            'descripcion' => "Cuota Anual para Socios con cantidad reducida (parados, veteranos, menores, etc)",
            'meses'=> 12,
            'cantidad' => 90,
            'invitaciones' => 6,
        ]);

        $socios = User::all();
        foreach ($socios as $key => $socio) {
            $cuenta = null;
            $cuenta = Cuenta::create([
                'fechaApunte' => $socio->altaSocio,
                'año'=> $socio->altaSocio->format('Y'),
                'tipo'=> 'Ingreso',
                'conceptoAgrupado' => 'Ingreso cuotas',
                'detalle' => 'Pago Cuota anual del Socio: '. $socio->id,
                'vocalia' => 'Sin vocalia',
                'cantidad' => $cuotaAnual->cantidad,
                'notas' => '',
                'pagcob'=> 'Si',
            ]);
            $cuota = null;
            $rand = rand(0,6);
            $tp = null;
            $cant = 0;
            switch ($rand) {
                case 0:
                    $tp = $cuotaMensual->id;
                    $cant = $cuotaMensual->cantidad;
                    break;
                case 1:
                    $tp = $cuotaTrimes->id;
                    $cant = $cuotaTrimes->cantidad;
                    break;
                case 2:
                    $tp = $cuotaAnualEsp->id;
                    $cant = $cuotaAnualEsp->cantidad;
                    break;
                default:
                    $tp = $cuotaAnual->id;
                    $cant = $cuotaAnual->cantidad;
                    break;
            }
            $cuota = Cuota::create([
                'idSocio' => $socio->id,
                'idAsiento' => $cuenta->id,
                'tipoCuota' => $tp,
                'cantidad' => $cant,
                'fechaCuota' => $socio->altaSocio,
            ]);
        }
    }
}
