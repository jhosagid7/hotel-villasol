<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start');
            $table->dateTime('end');
            $table->string('title', 255);
            $table->string('personaContacto', 255)->nullable();
            $table->string('nombreCliente', 255);
            $table->string('cedulaCliente', 255);
            $table->string('telefonoContacto', 15);
            $table->integer('numAcompanantes')->nullable();
            $table->string('tipoHabitacion', 15);
            $table->string('tipoServicio', 15);
            $table->unsignedInteger('cantidad')->nullable();
            $table->integer('numHabitacion')->nullable();
            $table->decimal('precio', 25, 3)->nullable();
            $table->decimal('montoPago', 25, 3)->nullable();
            $table->decimal('vueltoPago', 25, 3)->nullable();
            $table->string('telefonoPago', 255)->nullable();
            $table->string('cedulaPago', 255)->nullable();
            $table->string('operadorNombre', 255)->nullable();
            $table->enum('status', ['Pendiente', 'Procesado','Cancelado'])->default('Pendiente');
            $table->text('observation')->nullable();
            $table->string('numServicio', 50)->nullable();
            $table->string('color', 20);

            $table->unsignedBigInteger('cat_id')->nullable();
            $table->foreign('cat_id')->references('id')->on('cats');

            $table->unsignedBigInteger('horario_id')->nullable();
            $table->foreign('horario_id')->references('id')->on('horarios');

            $table->unsignedBigInteger('habitacione_id')->nullable();
            $table->foreign('habitacione_id')->references('id')->on('habitaciones');

            $table->unsignedBigInteger('servicio_id')->nullable();

            $table->foreign('servicio_id')->references('id')->on('servicios');
            $table->foreignId('persona_id')->references('id')->on('personas');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('caja_id')->references('id')->on('cajas');
            $table->unsignedBigInteger('caja_pago_reservacion_id')->nullable();
            $table->foreign('caja_pago_reservacion_id')->references('id')->on('cajas');
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
        Schema::dropIfExists('reservations');
    }
}
