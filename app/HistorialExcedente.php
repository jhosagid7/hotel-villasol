<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistorialExcedente extends Model
{
    protected $fillabel = [
        'tipo_registro',
        'status',
        'tipo_operacion',
        'num_servicio',
        'motivo',
        'saldo_anterior',
        'saldo_operacion',
        'saldo_disponible',
        'operador',
        'banco_id',
        'detalle_pago_oficina_id',
        'persona_id',
        'servicio_id',
        'caja_id',
        'user_id'
    ];



    protected $guarded = [];

    public function caja(){
        return $this->belongsTo(Caja::class);
    }

    public function servicio(){
        return $this->belongsTo(Servicio::class);
    }

    public function cliente(){
        return $this->belongsTo(Persona::class);
    }

    public function banco(){
        return $this->belongsTo(Banco::class);
    }

    public function operador(){
        return $this->belongsTo(User::class);
    }
}
