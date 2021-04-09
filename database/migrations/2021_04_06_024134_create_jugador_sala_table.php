<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJugadorSalaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jugador_sala', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jugador');
            $table->unsignedBigInteger('sala');
            $table->foreign('jugador')->references('id')->on('perfil_publico');
            $table->foreign('sala')->references('id')->on('salas');
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
        Schema::dropIfExists('jugador_sala');
    }
}
