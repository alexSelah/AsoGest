<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVocaliasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vocalias', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nombre')->unique();
            $table->text('descripcion')->nullable();
            $table->float('presupuesto',9,2)->default(0);
            $table->string('imagen')->default('sinImagen.png');
            $table->integer('color')->default('7');
            $table->longText('tablon')->nullable();
            $table->string('idCalendario')->default(env('GOOGLE_CALENDAR_ID','asogest@gmail.com'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vocalias');
    }
}
