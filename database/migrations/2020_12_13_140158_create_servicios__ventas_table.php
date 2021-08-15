<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiciosVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicios__ventas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('cantidad')->nullable();
            $table->decimal('precio_costo_unidad', 25, 9)->nullable();
            $table->decimal('precio_venta_unidad', 25, 9)->nullable();
            $table->decimal('porEspecial', 25, 2)->nullable();
            $table->unsignedInteger('isDolar')->nullable();
            $table->unsignedInteger('isPeso')->nullable();
            $table->unsignedInteger('isTransPunto')->nullable();
            $table->unsignedInteger('isMixto')->nullable();
            $table->unsignedInteger('isEfectivo')->nullable();
            $table->decimal('descuento', 25, 3)->nullable();
            $table->enum('estado_pago', ['Pagado', 'Falta pagar']);
            $table->enum('tipo_pago', ['Dolar', 'Peso','Bolivar','Punto','Transferencia','No pagado']);
            $table->foreignId('articulo_id')->references('id')->on('articulos');
            $table->foreignId('servicio_id')->references('id')->on('servicios');
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
        Schema::dropIfExists('servicios__ventas');
    }
}
