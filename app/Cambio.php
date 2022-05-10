<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cambio extends Model
{
    protected $fillabel = [
        'servicio_id',
        'habitacion',
        'servicio_id_cambio',
        'habitacion_cambio',
        'caja_id',
        'observacion'
    ];

    //Haora especificamos los campos guarded
    protected $guarded = [];

    public function servicio(){
        return $this->belongsTo(Servicio::class);
    }

    public function servicio_id_cambio(){
        return $this->belongsTo(Servicio::class);
    }

    public function caja(){
        return $this->belongsTo(Caja::class);
    }
}
