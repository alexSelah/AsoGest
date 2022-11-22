<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateCuentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuentas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->date('fechaApunte')->default(Carbon::now());
            $table->string('año')->default(Carbon::now()->year);
            $table->string('tipo')->default('Gasto');
            $table->string('conceptoAgrupado')->nullable();
            $table->string('detalle')->nullable();
            $table->string('vocalia')->nullable();
            $table->float('cantidad',9,2)->nullable();
            $table->text('notas')->nullable();
            $table->string('pagcob')->default("No");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cuentas');
    }
}
