<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'venta';

    protected $primaryKey = 'idventa';

    public $timestamps = false;

    protected $dates = [
        'fecha_hora',
    ];

    protected $fillabel = [
        'tipo_comprobante',
        'serie_comprobante',
        'num_comprobante',
        'fecha_hora',
        'tipo_pago',
        'precio_costo',
        'margen_ganancia',
        'total_venta',
        'ganancia_neta',
        'estado',
        'user_id',
        'persona_id',
        'caja_id'
    ];


    protected $guarded = [];

}
