<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBancosEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bancos_empresas', function (Blueprint $table) {
            $table->id();
            $table->enum('pertenece', ['Empresa']);
            $table->string('nombre_banco', 256);
            $table->string('codigo', 4);
            $table->string('num_cuenta', 30);
            $table->enum('tipo_cuenta', ['Corriente', 'Ahorro']);
            $table->string('pago_mobil', 30)->nullable();
            $table->foreignId('sucursal_id')->references('id')->on('sucursals');
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
        Schema::dropIfExists('bancos_empresas');
    }
}
