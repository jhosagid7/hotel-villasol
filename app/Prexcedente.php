<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prexcedente extends Model
{
    protected $fillabel = [
        'nombre_cliente',
        'cedula_cliente',
        'direccion_cliente',
        'telefono_cliente',
        'excedente',
        'persona_id'
    ];


    protected $guarded = [];
}
