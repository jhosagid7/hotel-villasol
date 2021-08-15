<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExcedentesRecibidosCajaActualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('excedentes__recibidos__caja__actuals', function (Blueprint $table) {
            $table->id();
            $table->enum('Tipo', ['Servicio', 'Consumo','Credito','Horas_Extras']);
            $table->enum('Estado', ['Pendiente', 'Devueltos','PagarOficina','ExcedenteNuevo']);
            $table->string('Divisa', 20)->nullable();
            $table->decimal('MontoDivisa', 25, 8)->nullable();
            $table->decimal('TasaTiket', 25, 2)->nullable();
            $table->decimal('MontoDolar', 25, 8)->nullable();
            $table->foreignId('servicio_id')->references('id')->on('servicios');
            $table->foreignId('venta_id')->references('id')->on('ventas')->default(0)->nullable();
            $table->foreignId('horas_extra_id')->references('id')->on('horas_extras')->default(0)->nullable();
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
        Schema::dropIfExists('excedentes__recibidos__caja__actuals');
    }
}
