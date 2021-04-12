<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detalle_credito extends Model
{
    protected $fillabel = [
        'numero_factura',
        'tipo_operacion',
        'operacion_id',
        'monto',
        'estado_pago',
        'estado_credito',
        'tipo_pago',
        'fecha_emision',
        'fecha_vencimiento',
        'fecha_pago',
        'persona_id',
        'credito_id',
        'caja_id'
    ];


    protected $guarded = [];

    protected $dates = [
        'fecha_vencimiento',
    ];

    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }

    public function credito()
    {
        return $this->belongsTo(credito::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Persona::class);
    }

    public function pago_creditos(){
        return $this->hasMany(Pago_Credito::class);
    }


}
