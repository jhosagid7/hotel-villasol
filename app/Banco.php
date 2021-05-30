<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    protected $fillabel = [
        'codigo',
        'nombre_banco'
    ];


    protected $guarded = [];
}
