<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_comprobante', 20);
            $table->string('serie_comprobante', 20);
            $table->string('num_comprobante', 20);
            $table->datetime('fecha_hora');
            $table->string('tipo_pago', 20);
            $table->decimal('precio_costo', 11, 2)->nullable();
            $table->decimal('margen_ganancia', 11, 2)->nullable();
            $table->decimal('total_venta', 11, 2)->nullable();
            $table->decimal('ganancia_neta', 11, 2)->nullable();
            $table->enum('estado', ['Aceptada', 'Cancelada', 'Procesando']);
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('persona_id')->references('id')->on('personas');
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
        Schema::dropIfExists('ventas');
    }
}
