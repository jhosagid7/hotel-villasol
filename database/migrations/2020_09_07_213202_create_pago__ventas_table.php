<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagoVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pago__ventas', function (Blueprint $table) {
            $table->id();
            $table->string('Divisa', 20)->nullable();
            $table->decimal('MontoDivisa', 25, 8)->nullable();
            $table->decimal('TasaTiket', 25, 2)->nullable();
            $table->decimal('MontoDolar', 25, 8)->nullable();
            $table->decimal('MontoDolarConsumo', 25, 8)->default(0)->nullable();
            $table->decimal('Excedente', 25, 8)->default(0)->nullable();
            $table->decimal('Vueltos', 25, 8)->nullable();
            $table->unsignedBigInteger('servicio_id')->nullable();
            $table->unsignedBigInteger('caja_id')->nullable();
            $table->foreignId('venta_id')->references('id')->on('ventas');
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
        Schema::dropIfExists('pago__ventas');
    }
}
