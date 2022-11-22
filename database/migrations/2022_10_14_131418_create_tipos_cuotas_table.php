<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiposCuotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipos_cuotas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nombre')->unique();
            $table->string('descripcion')->nullable();
            $table->integer('meses')->default(1);
            $table->float('cantidad',9,2);
            $table->integer('invitaciones')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipos_cuotas');
    }
}
