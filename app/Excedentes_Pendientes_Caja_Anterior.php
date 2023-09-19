<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Excedentes_Pendientes_Caja_Anterior extends Model
{
    protected $fillabel = [
        'Registrado_por',
        'Dolar',
        'Dolar_To_Dolar',
        'Peso',
        'Peso_To_Dolar',
        'Punto',
        'Punto_To_Dolar',
        'Transferencia',
        'Trans_To_Dolar',
        'Bolivar',
        'Bolivar_To_Dolar',
        'Operador',
        'user_id',
        'caja_id'
    ];



    protected $guarded = [];

    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }
}
