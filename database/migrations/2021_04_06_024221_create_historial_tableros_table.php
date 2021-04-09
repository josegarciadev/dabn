<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorialTablerosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historial_tableros', function (Blueprint $table) {
            $table->id();
            $table->integer('x');
            $table->integer('y');
            $table->unsignedBigInteger('tablero');
            $table->string('turno');
            $table->string('movimiento');
            $table->foreign('tablero')->references('id')->on('tableros');
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
        Schema::dropIfExists('historial__tableros');
    }
}
