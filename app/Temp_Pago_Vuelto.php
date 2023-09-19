<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Temp_Pago_Vuelto extends Model
{
    protected $fillabel = [
        'Tipo',
        'Divisa',
        'MontoDivisa',
        'TasaTiket',
        'MontoDolar',
        'servicio_id',
        'caja_id'
    ];



    protected $guarded = [];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function cajas()
    {
        return $this->belongsTo(Caja::class);
    }
}
