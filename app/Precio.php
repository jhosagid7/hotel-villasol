<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Precio extends Model
{
    protected $fillabel = [
        'horario_id',
        'cat_id',
        'precio'
    ];


    protected $guarded = [];

    public function horario()
    {
        return $this->belongsTo(Horario::class);
    }

    public function cat()
    {
        return $this->belongsTo(Cat::class);
    }
}
