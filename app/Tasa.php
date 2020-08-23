<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tasa extends Model
{
    protected $table = 'tasa';

    protected $primaryKey = 'id';

    public $timestamps = true;

    
    protected $fillabel = [
        'id',
        'nombre',
        'tasa',
        'estado',
        'fecha_hora'
    ];

    
    protected $guarded = [];
}
