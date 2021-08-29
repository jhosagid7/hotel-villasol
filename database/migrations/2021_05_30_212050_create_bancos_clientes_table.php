<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBancosClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bancos_clientes', function (Blueprint $table) {
            $table->id();
            $table->enum('pertenece', ['Empresa', 'Cliente','Proveedor','Empleado']);
            $table->string('nombre_banco', 256);
            $table->string('codigo', 4);
            $table->string('num_cuenta', 30);
            $table->enum('tipo_cuenta', ['Corriente', 'Ahorro']);
            $table->string('pago_movil', 30)->nullable();
            $table->foreignId('persona_id')->references('id')->on('personas');
            $table->foreignId('banco_id')->references('id')->on('bancos');
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
        Schema::dropIfExists('bancos_clientes');
    }
}
