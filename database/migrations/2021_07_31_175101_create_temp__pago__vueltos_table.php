<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempPagoVueltosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp__pago__vueltos', function (Blueprint $table) {
            $table->id();
            $table->enum('Tipo', ['Pendiente', 'Procesado'])->nullable();
            $table->string('Divisa', 20)->nullable();
            $table->decimal('MontoDivisa', 25, 8)->nullable();
            $table->decimal('TasaTiket', 25, 2)->nullable();
            $table->decimal('MontoDolar', 25, 8)->nullable();
            $table->foreignId('servicio_id')->references('id')->on('servicios');
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
        Schema::dropIfExists('temp__pago__vueltos');
    }
}
