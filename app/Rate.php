<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    public $timestamps = true;
    protected $fillabel = [
        'nombre',
        'tasa',
        'porcentaje_ganancia',
        'estado'
    ];

    protected $guarded = [];
}
