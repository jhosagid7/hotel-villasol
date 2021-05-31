<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallePagoOficinasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_pago_oficinas', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_pago', ['Pago_movil', 'Efectivo','Transferencia']);
            $table->string('telefono_pago_movil', 20);
            $table->string('num_cuenta_cliente', 100);
            $table->date('fecha_pago');
            $table->string('banco_cliente', 256);
            $table->decimal('saldo_pagado', 25, 2);
            $table->string('num_cuenta_empresa', 100);
            $table->enum('tipo_cuenta', ['Corriente', 'Ahorrro']);
            $table->string('num_transaccion', 100);
            $table->foreignId('persona_id')->references('id')->on('personas');
            $table->foreignId('banco_id')->references('id')->on('bancos');
            $table->foreignId('servicio_id')->references('id')->on('servicios');
            $table->foreignId('caja_id')->references('id')->on('cajas');
            $table->foreignId('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('detalle_pago_oficinas');
    }
}
