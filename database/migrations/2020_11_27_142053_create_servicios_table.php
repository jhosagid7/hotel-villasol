<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->string('num_servicio', 20);
            $table->string('operador', 100);
            $table->enum('status_servicio', ['Iniciado', 'Finalizado']);
            $table->string('nombre_habitacion', 40);
            $table->string('detalle_habitacion', 100);
            $table->string('tipo_habitacion', 20);
            $table->string('horario', 100);
            $table->date('fecha_entrada');
            $table->time('hora_entrada');
            $table->date('fecha_salida');
            $table->time('hora_salida');
            $table->decimal('tasaDolar', 25, 2)->nullable();
            $table->decimal('porDolar', 25, 2)->nullable();
            $table->decimal('tasaPeso', 25, 2)->nullable();
            $table->decimal('porPeso', 25, 2)->nullable();
            $table->decimal('tasaTransPunto', 25, 2)->nullable();
            $table->decimal('porTransPunto', 25, 2)->nullable();
            $table->decimal('tasaMixto', 25, 2)->nullable();
            $table->decimal('porMixto', 25, 2)->nullable();
            $table->decimal('tasaEfectivo', 25, 2)->nullable();
            $table->decimal('porEfectivo', 25, 2)->nullable();
            $table->decimal('tasaDolarHabitacion', 25, 2)->nullable();
            $table->decimal('porDolarHabitacion', 25, 2)->nullable();
            $table->decimal('tasaPesoHabitacion', 25, 2)->nullable();
            $table->decimal('porPesoHabitacion', 25, 2)->nullable();
            $table->string('num_Punto', 20)->nullable();
            $table->string('num_Trans', 20)->nullable();
            $table->enum('modo_pago', ['Contado', 'Credito', 'Cortesia', 'Cambio', 'Excedente', 'Contado-Excedente']);
            $table->string('tipo_pago', 20)->nullable();
            $table->enum('is_cambio', ['Si', 'No'])->nullable();
            $table->enum('status', ['Pagado', 'Falta pagar', 'Exonerado']);
            $table->decimal('precio_costo', 25, 8)->nullable();
            $table->unsignedInteger('cantidad')->nullable();
            $table->decimal('dinero_dejado', 25, 8)->nullable();
            $table->decimal('excedente_nuevo', 25, 8)->nullable();
            $table->decimal('pago_con_excedente', 25, 8)->nullable();
            $table->decimal('total_venta', 25, 8)->nullable();
            $table->enum('estado', ['Aceptada', 'Cancelada', 'Procesando']);
            $table->string('nombre_cliente', 100)->nullable();
            $table->string('cedula_cliente', 20)->nullable();
            $table->string('direccion_cliente', 100)->nullable();
            $table->string('telefono_cliente', 20)->nullable();
            $table->integer('limite_fecha')->nullable();
            $table->integer('limite_monto')->nullable();
            $table->foreignId('persona_id')->references('id')->on('personas');
            $table->foreignId('habitacion_id')->references('id')->on('habitaciones');
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
        Schema::dropIfExists('servicios');
    }
}
