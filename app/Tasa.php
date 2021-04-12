<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tasa extends Model
{
    public $timestamps = true;
    protected $fillabel = [
        'nombre',
        'tasa',
        'porcentaje_ganancia'
    ];

    protected $guarded = [];
}
// 'api_token' => str_random(50)
