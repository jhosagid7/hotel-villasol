<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetallePagoOficina extends Model
{
    protected $fillabel = [
        'tipo_pago',
        'telefono_pago_movil_cliente',
        'num_cuenta_cliente',
        'tipo_cuenta_cliente',
        'nombre_banco_cliente',
        'num_cuenta_empresa',
        'nombre_banco_empresa',
        'tipo_cuenta_empresa',
        'num_transaccion',
        'deuda',
        'saldo_pagado',
        'fecha_pago',
        'persona_id',
        'caja_id',
        'user_id'
    ];



    protected $guarded = [];

    public function caja(){
        return $this->belongsTo(Caja::class);
    }



    public function cliente(){
        return $this->belongsTo(Persona::class);
    }



    public function operador(){
        return $this->belongsTo(User::class);
    }
}
