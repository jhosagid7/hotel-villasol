<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago_Vuelto extends Model
{
    protected $fillabel = [
        'Divisa',
        'MontoDivisa',
        'TasaTiket',
        'MontoDolar',
        'servicio_id'
    ];



    protected $guarded = [];

    public function servicio(){
        return $this->belongsTo(Servicio::class);
    }
}
