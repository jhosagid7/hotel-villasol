<?php

namespace App;

use Carbon\Carbon;
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

    public function pago_creditos()
    {
        return $this->hasMany(Pago_Credito::class);
    }

    public function scopeFecha($query, $fecha)
    {

        if ($fecha) {
            list($fecha_inicio, $fecha_fin) = explode(" - ", $fecha);
            $fecha_inicio = Carbon::parse($fecha_inicio . '00:00:00')->format('Y-m-d H:i:s');
            $fecha_fin = Carbon::parse($fecha_fin . '23:59:59')->format('Y-m-d H:i:s');
            return $query->whereBetween('detalle_creditos.created_at', [$fecha_inicio, $fecha_fin]);
        }
    }

    // public function scopeTipo($query, $tipo){
    //     if($tipo)
    //     return $query->where('detalle__creditos__pagados.estado', 'LIKE', "$tipo");
    // }

    public function scopeOperador($query, $operador)
    {
        if ($operador)
            return $query->where('detalle_creditos.user_id', '=', "$operador");
    }

    public function scopeCliente($query, $cliente)
    {
        if ($cliente)
            return $query->where('detalle_creditos.persona_id', '=', "$cliente");
    }

    public function scopeEstadoPago($query, $estadoPago)
    {
        if ($estadoPago) {
            return $query->where('detalle_creditos.estado_pago', '=', "$estadoPago");
        }
    }

    public function scopeEstadoCredito($query, $estadoCredito)
    {
        if ($estadoCredito) {
            return $query->where('detalle_creditos.estado_credito', '=', "$estadoCredito");
        }
    }

    public function scopeCaja($query, $caja_id)
    {
        if ($caja_id) {
            return $query->where('detalle_creditos.caja_id', '=', "$caja_id");
        }
    }
}
