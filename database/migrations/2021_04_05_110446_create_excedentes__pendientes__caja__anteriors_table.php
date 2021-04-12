<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExcedentesPendientesCajaAnteriorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('excedentes__pendientes__caja__anteriors', function (Blueprint $table) {
            $table->id();
            $table->enum('Registrado_por', ['Sistema', 'Operador','Diferencia']);
            $table->decimal('Dolar', 25, 2)->nullable();
            $table->decimal('Dolar_To_Dolar', 25, 2)->nullable();
            $table->decimal('Peso', 25, 2)->nullable();
            $table->decimal('Peso_To_Dolar', 25, 2)->nullable();
            $table->decimal('Punto', 25, 2)->nullable();
            $table->decimal('Punto_To_Dolar', 25, 2)->nullable();
            $table->decimal('Transferencia', 25, 2)->nullable();
            $table->decimal('Trans_To_Dolar', 25, 2)->nullable();
            $table->decimal('Bolivar', 25, 2)->nullable();
            $table->decimal('Bolivar_To_Dolar', 25, 2)->nullable();
            $table->string('Operador', 50)->nullable();
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
        Schema::dropIfExists('excedentes__pendientes__caja__anteriors');
    }
}
