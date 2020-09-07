<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    // protected $table = 'venta';

    // protected $primaryKey = 'idventa';

    // public $timestamps = false;

    public function articulo_ventas(){
        return hasMany(Articulo_Venta::Class);
    }

    public function caja(){
        return belongsTo(Venta::class);
    }

    public function user()
    {
        return $this->hasOneThrough('App\User', 'App\Caja');
    }

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
        'persona_id',
        'caja_id'
    ];


    protected $guarded = [];

}
