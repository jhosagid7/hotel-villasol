<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contabilidad extends Model
{
    protected $fillabel     = [
        'denominacion',
        'valor',
        'cantidad',
        'tipo',
        'modo',
        'caja_id'

    ];

    //Ahora especificamos los campos guarded
    protected $guarded=[];
}
