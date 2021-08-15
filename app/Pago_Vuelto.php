<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago_Vuelto extends Model
{
    protected $fillabel = [
        'Tipo',
        'tipo_vuelto',
        'Divisa',
        'MontoDivisa',
        'TasaTiket',
        'MontoDolar',
        'servicio_id',
        'venta_id',
        'horas_extra_id',
        'detalle__creditos__pagado_id',
        'caja_id'
    ];



    protected $guarded = [];

    public function servicio(){
        return $this->belongsTo(Servicio::class);
    }

    public function venta(){
        return $this->belongsTo(Venta::class);
    }

    public function detalle_creditos_pagado(){
        return $this->belongsTo(Detalle_Creditos_Pagado::class);
    }
    public function cajas(){
        return $this->belongsTo(Caja::class);
    }
}
