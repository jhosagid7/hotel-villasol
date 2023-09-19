<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillabel = [
        'sucursal_id',
        'nonbre',
        'estado'
    ];


    protected $guarded = [];

    public function habitaciones()
    {
        return $this->hasMany('App\Habitacione');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }
}
