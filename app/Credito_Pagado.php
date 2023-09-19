<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Credito_Pagado extends Model
{
    protected $fillabel = [
        'numero_factura',
        'tipo_operacion',
        'operacion_id',
        'monto',
        'abono',
        'fecha_emision',
        'fecha_vencimiento',
        'fecha_pago',
        'estado_credito_al_pagar',
        'persona_id',
        'user_id',
        'detalle__creditos__pagado_id',
        'caja_id'
    ];


    protected $guarded = [];

    public function cliente()
    {
        return $this->belongsTo(Persona::class);
    }

    public function operador()
    {
        return $this->belongsTo(User::class);
    }

    public function detalle_creditos_pagado()
    {
        return $this->belongsTo(Detalle_Creditos_Pagado::class);
    }

    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }
}
