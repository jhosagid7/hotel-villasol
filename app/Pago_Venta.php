<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago_Venta extends Model
{
    protected $fillabel = [
        'Divisa',
        'MontoDivisa',
        'TasaTiket',
        'MontoDolar',
        'MontoDolarConsumo',
        'Excedente',
        'Vueltos',
        'servicio_id',
        'caja_id',
        'venta_id'
    ];

    public function venta(){
        return $this->belongsTo(Venta::class);
    }
    public function servicio(){
        return $this->belongsTo(Servicio::class);
    }
    public function caja(){
        return $this->belongsTo(Caja::class);
    }
}
