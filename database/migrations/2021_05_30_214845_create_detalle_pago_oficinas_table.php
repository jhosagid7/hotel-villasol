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
            $table->enum('tipo_pago', ['Pago_movil','Transferencia','Dolar','Peso','Bolivar']);
            $table->string('telefono_pago_movil_cliente', 20);
            $table->string('num_cuenta_cliente', 100);
            $table->enum('tipo_cuenta_cliente', ['Corriente', 'Ahorro']);
            $table->string('nombre_banco_cliente', 256);
            $table->string('num_cuenta_empresa', 100);
            $table->string('nombre_banco_empresa', 100);
            $table->enum('tipo_cuenta_empresa', ['Corriente', 'Ahorro']);
            $table->string('num_transaccion', 100);
            $table->decimal('deuda', 25, 8);
            $table->decimal('saldo_pagado', 25, 8);
            $table->date('fecha_pago');
            $table->foreignId('persona_id')->references('id')->on('personas');
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
