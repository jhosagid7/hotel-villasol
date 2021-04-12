<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Excedentes_Recibidos_Caja_Actual extends Model
{
    protected $fillabel = [
        'Tipo',
        'Estado',
        'Divisa',
        'MontoDivisa',
        'TasaTiket',
        'MontoDolar',
        'servicio_id',
        'caja_id'
    ];



    protected $guarded = [];

    public function caja(){
        return $this->belongsTo(Caja::class);
    }

    public function servicio(){
        return $this->belongsTo(Servicio::class);
    }
}
