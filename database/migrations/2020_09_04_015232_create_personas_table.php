<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_persona', ['Proveedor', 'Cliente', 'Administrador_mesa', 'Inactivo']);
            $table->string('nombre', 100);
            $table->string('tipo_documento', 20);
            $table->string('num_documento', 15);
            $table->string('direccion', 256)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->unsignedInteger('isCortesia')->nullable();
            $table->unsignedInteger('isCredito')->nullable();
            $table->string('imagen', 200)->nullable();
            $table->integer('limite_fecha')->nullable();
            $table->decimal('limite_monto',25,2)->nullable();
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
        Schema::dropIfExists('personas');
    }
}
