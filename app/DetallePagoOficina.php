<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetallePagoOficina extends Model
{
    protected $fillabel = [
        'tipo_pago',
        'telefono_pago_movil',
        'num_cuenta_cliente',
        'fecha_pago',
        'banco_cliente',
        'saldo_pagado',
        'num_cuenta_empresa',
        'tipo_cuenta',
        'num_transaccion',
        'persona_id',
        'banco_id',
        'servicio_id',
        'caja_id',
        'user_id'
    ];



    protected $guarded = [];

    public function caja(){
        return $this->belongsTo(Caja::class);
    }

    public function servicio(){
        return $this->belongsTo(Servicio::class);
    }

    public function cliente(){
        return $this->belongsTo(Persona::class);
    }

    public function banco(){
        return $this->belongsTo(Banco::class);
    }

    public function operador(){
        return $this->belongsTo(User::class);
    }
}
