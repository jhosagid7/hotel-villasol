<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BancosCliente extends Model
{
    protected $fillabel = [
        'nombre_banco',
        'codigo',
        'num_cuenta',
        'tipo_cuenta',
        'persona_id',
        'banco_id'
    ];


    protected $guarded = [];

    public function persona(){
        return $this->belongsTo('App\Persona');
    }

    public function banco(){
        return $this->belongsTo('App\Banco');
    }
}
