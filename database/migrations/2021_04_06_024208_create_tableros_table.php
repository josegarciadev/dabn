<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablerosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tableros', function (Blueprint $table) {
            $table->id();
            $table->string('tablero');
            $table->unsignedBigInteger('sala_id');
            $table->string('turno')->nullable();
            $table->string('movimientos')->nullable();
            $table->unsignedBigInteger('j2')->nullable();
            $table->integer('j2puntos')->nullable();
            $table->unsignedBigInteger('j1');
            $table->integer('j1puntos');
            $table->unsignedBigInteger('estado');
            $table->string('ganador')->nullable();
            $table->integer('x_tablero');
            $table->integer('y_tablero');
            $table->foreign('sala_id')->references('id')->on('salas');
            $table->foreign('j2')->references('id')->on('perfil_publico');
            $table->foreign('j1')->references('id')->on('perfil_publico');
            $table->foreign('estado')->references('id')->on('estado');
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
        Schema::dropIfExists('tableros');
    }
}
