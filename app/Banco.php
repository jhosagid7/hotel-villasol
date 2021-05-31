<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    protected $fillabel = [
        'codigo',
        'nombre_banco'
    ];


    protected $guarded = [];

    public function bancos_clientes(){
        return $this->hasMany(BancosCliente::class);
    }

    public function historialExcedentes(){
        return $this->hasMany(HistorialExcedente::class);
    }

    public function detalle_pago_oficina(){
        return $this->hasMany(DetallePagoOficina::class);
    }
}
