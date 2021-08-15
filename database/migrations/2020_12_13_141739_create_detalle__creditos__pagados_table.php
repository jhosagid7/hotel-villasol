<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleCreditosPagadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle__creditos__pagados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_cliente', 100)->nullable();
            $table->string('cedula_cliente', 20)->nullable();
            $table->string('direccion_cliente', 100)->nullable();
            $table->string('telefono_cliente', 20)->nullable();
            $table->string('tipo_pago', 20)->nullable();
            $table->integer('total_factura')->nullable();
            $table->decimal('total_Consumo', 25, 8)->default(0)->nullable();
            $table->decimal('total_Servicio', 25, 8)->default(0)->nullable();
            $table->decimal('total_deuda', 25, 8)->nullable();
            $table->date('fecha_pago')->nullable();
            $table->enum('estado_credito', ['Activo', 'Moroso']);
            $table->foreignId('persona_id')->references('id')->on('personas');
            $table->foreignId('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('detalle__creditos__pagados');
    }
}
