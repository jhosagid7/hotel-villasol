<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BancosEmpresa extends Model
{
    protected $fillabel = [
        'pertenece',
        'nombre_banco',
        'codigo',
        'num_cuenta',
        'tipo_cuenta',
        'pago_mobil',
        'sucursal_id',
        'banco_id'
    ];


    protected $guarded = [];

    public function sucursal(){
        return $this->belongsTo('App\Sucursal');
    }

    public function banco(){
        return $this->belongsTo('App\Banco');
    }
}
