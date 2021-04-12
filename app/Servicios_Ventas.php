<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Servicios_Ventas extends Model
{
    protected $fillabel = [
    'cantidad',
    'precio_costo_unidad',
    'precio_venta_unidad',
    'porEspecial',
    'isDolar',
    'isPeso',
    'isTransPunto',
    'isMixto',
    'isEfectivo',
    'descuento',
    'estado_pago',
    'tipo_pago',
    'articulo_id',
    'servicio_id'

    ];


    protected $guarded = [];

    public function creditos(){
        return $this->hasMany(Credito::class);
    }
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    // public function servicio()
    // {
    //     return $this->belongsTo(Servicio::class);
    // }

    public function servicio(){
        return $this->belongsTo(Servicio::class);
    }

    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }

}
