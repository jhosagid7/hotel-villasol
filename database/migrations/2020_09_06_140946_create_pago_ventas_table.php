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
        Schema::create('pago_ventas', function (Blueprint $table) {
            $table->id();
            $table->decimal('Divisa', 11, 2)->nullable();
            $table->decimal('MontoDivisa', 11, 2)->nullable();
            $table->decimal('TasaTiket', 11, 2)->nullable();
            $table->decimal('MontoDolar', 11, 2)->nullable();
            $table->decimal('Vueltos', 11, 2)->nullable();
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
        Schema::dropIfExists('pago_ventas');
    }
}
