<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{

    public function Ventas(){
        return $this->hasMany(Venta::class);
    }

    // protected $table = 'persona';

    // protected $primaryKey = 'idpersona';

    // public $timestamps = false;

    protected $fillabel = [
        'tipo_persona',
        'nombre',
        'tipo_documento',
        'num_documento',
        'direccion',
        'telefono',
        'email',
        'imagen'
    ];

    protected $guarded = [];
}
