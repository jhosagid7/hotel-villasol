<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cat extends Model
{
    protected $fillabel = [
        'nonbre',
        'descripcion'
    ];


    protected $guarded = [];

    public function habitaciones()
    {
        return $this->hasMany('App\Habitacione');
    }

    public function precios()
    {
        return $this->hasMany('App\Precio');
    }
}
