<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago_Extra extends Model
{
    protected $fillabel = [
        'Divisa',
        'MontoDivisa',
        'TasaTiket',
        'MontoDolar',
        'horas_extra_id',
        'servicio_id',
        'caja_id'
    ];



    protected $guarded = [];

    public function servicio(){
        return $this->belongsTo(Servicio::class);
    }

    public function horas_extra(){
        return $this->belongsTo(Horas_extra::class);
    }

    public function cajas(){
        return $this->belongsTo(Caja::class);
    }
}
