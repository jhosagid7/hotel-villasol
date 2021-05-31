<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExcedentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('excedentes', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['Excedente','Pagar_por_oficina']);
            $table->string('nombre_cliente', 100)->nullable();
            $table->string('cedula_cliente', 20)->nullable();
            $table->string('direccion_cliente', 100)->nullable();
            $table->string('telefono_cliente', 20)->nullable();
            $table->decimal('excedente', 25, 2)->nullable();
            $table->foreignId('persona_id')->references('id')->on('personas');
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
        Schema::dropIfExists('excedentes');
    }
}
