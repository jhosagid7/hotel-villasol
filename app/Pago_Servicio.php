<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago_Servicio extends Model
{
    protected $fillabel = [
        'Divisa',
        'MontoDivisa',
        'TasaTiket',
        'MontoDolar',
        'Vueltos',
        'servicio_id'
    ];



    protected $guarded = [];

    public function servicio(){
        return $this->belongsTo(Servicio::class);
    }
}
