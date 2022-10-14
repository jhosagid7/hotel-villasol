<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reintegro extends Model
{
    protected $fillabel = [
        'nombre_cliente',
        'monto_deuda',
        'monto_pagado',
        'monto_dolar',
        'monto_peso',
        'monto_bolivar',
        'monto_trans',
        'monto_dolar_to_dolar',
        'monto_peso_to_dolar',
        'monto_bolivar_to_dolar',
        'monto_trans_to_dolar',
        'tasa_dolar',
        'tasa_peso',
        'tasa_bolivar',
        'tasa_trans',
        'observacion',
        'operador',
        'user_id',
        'cliente_id',
        'caja_id'
    ];

    protected $guarded = [];

    public function caja(){
        return $this->belongsTo(Caja::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
