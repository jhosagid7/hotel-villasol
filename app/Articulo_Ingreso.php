<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo_Ingreso extends Model
{
    protected $fillabel = [
        'cantidad',
        'precio_costo_unidad',
        'ingreso_id',
        'articulo_id'
    ];


    protected $guarded = [];
}
