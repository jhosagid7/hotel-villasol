<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticuloVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulo_ventas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('cantidad')->nullable();
            $table->decimal('precio_costo_unidad', 11, 2)->nullable();
            $table->decimal('precio_venta_unidad', 11, 2)->nullable();
            $table->decimal('descuento', 11, 2)->nullable();
            $table->foreignId('articulo_id')->references('id')->on('articulos');
            $table->foreignId('venta_id')->references('id')->on('ventas');
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
        Schema::dropIfExists('articulo_ventas');
    }
}
