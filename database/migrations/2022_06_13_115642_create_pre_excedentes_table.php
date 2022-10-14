<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreExcedentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_excedentes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_cliente',50);
            $table->decimal('monto_excedente_actual', 25, 3)->nullable();
            $table->decimal('deuda_total_acumulada', 25, 3)->nullable();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('cliente_id')->references('id')->on('personas');
            $table->foreignId('caja_id')->references('id')->on('cajas');
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
        Schema::dropIfExists('pre_excedentes');
    }
}
