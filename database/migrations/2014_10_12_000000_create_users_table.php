<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('altaSocio');
            $table->date('bajaSocio')->nullable();
            $table->integer('numSocio')->unsigned();
            $table->string('nombre');
            $table->string('primerApellido')->nullable();;
            $table->string('segundoApellido')->nullable();;
            $table->string('DNI')->unique();
            $table->string('email')->unique();
            $table->string('username',15)->unique();
            $table->string('sexo')->nullable();;
            $table->date('fnacimiento')->default(Carbon::now());
            $table->string('direccion')->nullable();
            $table->string('localidad')->nullable();
            $table->string('provincia')->nullable();
            $table->integer('codPostal')->nullable()->unsigned();
            $table->string('telefono')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('notas')->nullable();
            $table->string('foto')->nullable();
            $table->integer('invitaciones')->unsigned()->default(1);
            $table->boolean('habilitado')->default(true);
            $table->boolean('accesoDrive')->nullable(); //accs1
            $table->boolean('accesoJunta')->nullable(); //accs2
            $table->boolean('recibirCorreos')->default(true);
            $table->boolean('privacidad')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
