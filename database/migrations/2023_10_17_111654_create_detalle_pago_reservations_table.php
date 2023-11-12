<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallePagoReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *
     */
    public function up()
    {
        Schema::create('detalle_pago_reservations', function (Blueprint $table) {
            $table->id();
            $table->string('tipoPago', 255);
            $table->decimal('montoPagado', 25, 3);
            $table->decimal('montoPagadoDolar', 25, 3);
            $table->decimal('vueltos', 25, 3);
            $table->decimal('vueltosDolar', 25, 3);
            $table->string('nombreBanco', 255)->nullable();
            $table->string('referencia', 50)->nullable();
            $table->date('fechaPago')->nullable();
            $table->decimal('tasaDolar', 25, 3)->nullable();
            $table->decimal('tasaPeso', 25, 3)->nullable();
            $table->decimal('tasaBolivar', 25, 3)->nullable();
            $table->string('operadorNombre', 255)->nullable();

            $table->unsignedBigInteger('reservation_id');
            $table->foreign('reservation_id')->references('id')->on('reservations');

            $table->unsignedBigInteger('caja_id');
            $table->foreign('caja_id')->references('id')->on('cajas');

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
        Schema::dropIfExists('detalle_pago_reservations');
    }
}
