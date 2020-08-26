<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $fillabel     = [
        'nombre',
        'descripcion',
        'estado',
        'sucursal_id'
    ];

    //Ahora especificamos los campos guarded
    protected $guarded=[];

    public function sucursal()
    {
        return $this->belongsTo('App\Sucursal');
    }
}
