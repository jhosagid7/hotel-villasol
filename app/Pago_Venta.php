<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago_Venta extends Model
{
    protected $fillabel = [
        'Divisa',
        'MontoDivisa',
        'TasaTiket',
        'MontoDolar',
        'Vueltos',
        'venta_id'
    ];


    protected $guarded = [];
}
