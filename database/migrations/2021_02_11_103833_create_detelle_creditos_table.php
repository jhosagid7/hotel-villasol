<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetelleCreditosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_creditos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_factura', 20);
            $table->string('tipo_operacion', 20);
            $table->integer('operacion_id');
            $table->decimal('monto', 25, 8)->nullable();
            $table->decimal('abono', 25, 8)->nullable()->default(0);
            $table->enum('estado_pago', ['Pendiente', 'Pagado']);
            $table->enum('estado_credito', ['Vigente', 'Vencido','Pagado']);
            $table->string('tipo_pago', 20);
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento');
            $table->date('fecha_pago')->nullable();
            $table->foreignId('persona_id')->references('id')->on('personas');
            $table->foreignId('credito_id')->references('id')->on('creditos');
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
        Schema::dropIfExists('detalle_creditos');
    }
}
