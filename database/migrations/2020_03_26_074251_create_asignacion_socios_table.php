<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\User;
use App\vocalia;

class CreateAsignacionSociosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asignacion_socios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('idSocio')->unsigned();
            $table->integer('idVocalia')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asignacion_socios');
    }
}
