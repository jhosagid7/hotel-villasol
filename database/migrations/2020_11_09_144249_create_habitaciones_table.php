<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHabitacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('habitaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cat_id')->references('id')->on('cats');
            $table->foreignId('level_id')->references('id')->on('levels');
            $table->string('nombre', 20);
            $table->enum('estado', ['Activa', 'Eliminada']);
            $table->enum('status', ['Disponible', 'Ocupada', 'Limpieza','Finalizando','En reparacion'])->nullable();
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
        Schema::dropIfExists('habitaciones');
    }
}
