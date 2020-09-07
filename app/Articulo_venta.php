<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo_venta extends Model
{
    protected $fillabel = [
    'cantidad',
    'precio_costo',
    'descuento',
    'articulo_id',
    'venta_id'
    ];

    //Haora especificamos los campos guarded
    protected $guarded = [];

    public function venta(){
        return belongsTo(Venta::class);
    }

    public function articulo(){
        return belongsTo(Articulo_Venta::class);
    }
}

