<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credito_Pagado extends Model
{
    protected $fillabel = [
        'numero_factura',
        'tipo_operacion',
        'operacion_id',
        'monto',
        'tipo_pago',
        'fecha_emision',
        'fecha_vencimiento',
        'fecha_pago',
        'estado_credito_al_pagar',
        'persona_id',
        'user_id',
        'detalle_credito_id',
        'credito_id',
        'caja_id'
    ];


    protected $guarded = [];

    public function caja(){
        return $this->belongsTo(Caja::class);
    }

    public function credito(){
        return $this->belongsTo(Credito::class);
    }

    public function detalle_credito(){
        return $this->belongsTo(Detalle_credito::class);
    }

    public function cliente(){
        return $this->belongsTo(Persona::class);
    }

    public function operador(){
        return $this->belongsTo(User::class);
    }
}
