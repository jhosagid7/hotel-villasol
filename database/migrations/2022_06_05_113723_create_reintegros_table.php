<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReintegrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reintegros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_cliente',50);
            $table->decimal('monto_deuda', 25, 3)->nullable();
            $table->decimal('monto_pagado', 25, 3)->nullable();
            $table->decimal('monto_dolar', 25, 3)->nullable();
            $table->decimal('monto_peso', 25, 3)->nullable();
            $table->decimal('monto_bolivar', 25, 3)->nullable();
            $table->decimal('monto_trans', 25, 3)->nullable();
            $table->decimal('monto_dolar_to_dolar', 25, 3)->nullable();
            $table->decimal('monto_peso_to_dolar', 25, 3)->nullable();
            $table->decimal('monto_bolivar_to_dolar', 25, 3)->nullable();
            $table->decimal('monto_trans_to_dolar', 25, 3)->nullable();
            $table->decimal('tasa_dolar', 25, 3)->nullable();
            $table->decimal('tasa_peso', 25, 3)->nullable();
            $table->decimal('tasa_bolivar', 25, 3)->nullable();
            $table->decimal('tasa_trans', 25, 3)->nullable();
            $table->text('observacion')->nullable();
            $table->string('operador',50);
            $table->foreignId('historial_excedente_id')->references('id')->on('historial_excedentes');
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
        Schema::dropIfExists('reintegros');
    }
}
