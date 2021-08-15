<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHorasExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horas_extras', function (Blueprint $table) {
            $table->id();
            $table->string('num_servicio', 20);
            $table->string('nombre_habitacion', 20);
            $table->string('nombre_cliente', 256);
            $table->string('cedula_cliente', 20);
            $table->string('fecha_hora_entrada', 20)->nullable();
            $table->string('fecha_hora_salida_sugerida', 20)->nullable();
            $table->string('fecha_hora_salida_real', 20)->nullable();
            $table->decimal('precio_hora_extra', 25, 2)->nullable();
            $table->integer('cantidad_hora_extra')->nullable();
            $table->decimal('monto_total_hora_extra', 25, 8)->nullable();
            $table->decimal('otros_montos', 25, 8)->nullable();
            $table->string('detalle_otros_montos', 256)->nullable();
            $table->decimal('total_horas_extras_otros_montos', 25, 2)->nullable();
            $table->decimal('dinero_dejado', 25, 8)->nullable();
            $table->decimal('excedente_nuevo', 25, 8)->nullable();
            $table->decimal('pago_con_excedente', 25, 8)->nullable();
            $table->enum('modo_pago', ['Contado', 'Credito', 'Cortesia', 'Excedente', 'Contado-Excedente']);
            $table->string('tipo_pago', 20)->nullable();
            $table->enum('status', ['Pagado', 'Falta pagar', 'Exonerado']);
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
        Schema::dropIfExists('horas_extras');
    }
}
