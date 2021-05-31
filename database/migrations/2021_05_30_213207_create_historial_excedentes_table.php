<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorialExcedentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historial_excedentes', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_registro', ['Pago_por_oficina', 'Excedente']);
            $table->string('num_servicio', 30);
            $table->enum('motivo', ['Pagos_extras', 'Consumo', 'Servicio']);
            $table->decimal('saldo_anterior', 25, 2);
            $table->decimal('saldo_operacion', 25, 2);
            $table->decimal('saldo_disponible', 25, 2);
            $table->string('operador', 256);
            $table->foreignId('banco_id')->references('id')->on('bancos')->nullable();
            $table->foreignId('persona_id')->references('id')->on('personas');
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
        Schema::dropIfExists('historial_excedentes');
    }
}
