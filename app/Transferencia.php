<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transferencia extends Model
{
    protected $fillabel     = [
        'accion',
        'origenNombreProducto',
        'origenStockInicial',
        'origenStockFinal',
        'origenUnidades',
        'origenVender_al',
        'cantidadRestarOrigen',
        'destinoNombreProducto',
        'destinoStockInicial',
        'destinoStockFinal',
        'destinoUnidades',
        'destinoVender_al',
        'cantidadSumarDestino',
        'operador'
    ];

    protected $guarded=[];

}
