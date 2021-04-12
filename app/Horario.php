<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $fillabel = [
        'tipo',
        'nombre',
        'desde',
        'hasta',
        'restringir',
        'is24Horas'
    ];


    protected $guarded = [];

    public function precios(){
        return $this->hasMany('App\Precio');
    }

    public function setIs24HorasAttribute($value){
        $this->attributes['is24Horas'] = ($value == 'on' ? '1' : null);
    }
}
