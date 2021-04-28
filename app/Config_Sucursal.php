<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config_Sucursal extends Model
{
    protected $fillabel     = [
        'precioHorasExtra',
        'minutosMaximosCobrar',
        'sucursal_id'
    ];

    //Ahora especificamos los campos guarded
    protected $guarded=[];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }
}
