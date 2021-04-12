<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cortesia extends Model
{
    protected $fillabel = [
        'nombre_cliente',
        'cedula_cliente',
        'direccion_cliente',
        'telefono_cliente',
        'exonerado',
        'persona_id',
        'servicio_id'
    ];


    protected $guarded = [];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }
}
