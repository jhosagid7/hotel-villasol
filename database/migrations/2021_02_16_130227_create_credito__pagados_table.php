<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditoPagadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credito__pagados', function (Blueprint $table) {
            $table->id();
            $table->string('numero_factura', 20);
            $table->string('tipo_operacion', 20);
            $table->integer('operacion_id');
            $table->decimal('monto', 25, 2)->nullable();
            $table->string('tipo_pago', 20);
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento');
            $table->date('fecha_pago')->nullable();
            $table->enum('estado_credito_al_pagar', ['Vigente', 'Vencido','Pagado']);
            $table->foreignId('persona_id')->references('id')->on('personas');
            $table->foreignId('user_id')->references('id')->on('users')->default(1);
            $table->foreignId('detalle_credito_id')->references('id')->on('detalle_creditos');
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
        Schema::dropIfExists('credito__pagados');
    }
}
