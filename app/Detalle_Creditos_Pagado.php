<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Detalle_Creditos_Pagado extends Model
{
    protected $fillabel = [
        'nombre_cliente',
        'cedula_cliente',
        'direccion_cliente',
        'telefono_cliente',
        'tipo_pago',
        'total_factura',
        'total_Consumo',
        'total_Servicio',
        'total_deuda',
        'fecha_pago',
        'estado_credito',
        'persona_id',
        'user_id',
        'caja_id'
    ];


    protected $guarded = [];

    protected $dates = [
        'fecha_pago',
    ];

    public function Persona(){
        return $this->belongsTo(Persona::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function Caja(){
        return $this->belongsTo(Caja::class);
    }

    public function creditos_pagados(){
        return $this->hasMany(Credito_Pagado::class);
    }

    public function pagos_creditos(){
        return $this->hasMany(Pagos_Creditos::class);
    }

    public function scopeFecha($query, $fecha){

        if($fecha){
        list($fecha_inicio, $fecha_fin) = explode(" - ", $fecha);
            $fecha_inicio = Carbon::parse($fecha_inicio. '00:00:00')->format('Y-m-d H:i:s');
            $fecha_fin = Carbon::parse($fecha_fin. '23:59:59')->format('Y-m-d H:i:s');
        return $query->whereBetween('detalle__creditos__pagados.created_at', [$fecha_inicio, $fecha_fin]);
        }
    }

    // public function scopeTipo($query, $tipo){
    //     if($tipo)
    //     return $query->where('detalle__creditos__pagados.estado', 'LIKE', "$tipo");
    // }

    public function scopeOperador($query, $operador){
        if($operador)
        return $query->where('detalle__creditos__pagados.user_id', '=', "$operador");
    }

    public function scopeCliente($query, $cliente){
        if($cliente)
        return $query->where('detalle__creditos__pagados.persona_id', '=', "$cliente");
    }


}
