<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsuarioMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('usuario', function (Blueprint $table) {
            $table->increments('id_usuario');
            $table->string('rut');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('usuario');
            $table->string('password');

            $table->integer('rol_id')->unsigned();

            // Indicamos cual es la clave foránea de esta tabla:
            $table->foreign('rol_id')->references('id')->on('rol');

            $table->timestamps();
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario');
    }
}
