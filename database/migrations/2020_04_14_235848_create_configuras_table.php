<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigurasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuras', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nombre')->unique();
            $table->string('descripcion');
            $table->float('valorNumero',8,2)->unsigned()->nullable();
            $table->longText('valorTexto')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configuras');
    }
}
