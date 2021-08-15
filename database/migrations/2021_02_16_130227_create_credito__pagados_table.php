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
            $table->string('numero_factura', 50);
            $table->string('tipo_operacion', 50);
            $table->integer('operacion_id');
            $table->decimal('monto', 25, 2)->nullable();
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento');
            $table->date('fecha_pago')->nullable();
            $table->enum('estado_credito_al_pagar', ['Vigente', 'Vencido','Pagado']);
            $table->foreignId('persona_id')->references('id')->on('personas');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('detalle__creditos__pagado_id')->references('id')->on('detalle__creditos__pagados');
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
