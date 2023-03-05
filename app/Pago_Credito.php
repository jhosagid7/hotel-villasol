<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago_Credito extends Model
{
    protected $fillabel = [
        'Divisa',
        'MontoDivisa',
        'TasaTiket',
        'MontoDolar',
        'MontoCredito',
        'Vueltos',
        'detalle__creditos__pagado_id',
        'caja_id'
    ];



    protected $guarded = [];

    public function detalle_creditos_pagado()
    {
        return $this->belongsTo(Detalle_Creditos_Pagado::class);
    }
    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }
}
