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
        'Vueltos',
        'detalle_credito_id'
    ];



    protected $guarded = [];

    public function detalle_credito(){
        return $this->belongsTo(detalle_credito::class);
    }
    public function cajas(){
        return $this->belongsTo(Caja::class);
    }
}
