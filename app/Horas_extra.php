<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Horas_extra extends Model
{
    protected $fillabel = [
        'num_servicio',
        'nombre_habitacion',
        'nombre_cliente',
        'cedula_cliente',
        'fecha_hora_entrada',
        'fecha_hora_salida_sugerida',
        'fecha_hora_salida_real',
        'precio_hora_extra',
        'cantidad_hora_extra',
        'monto_total_hora_extra',
        'otros_montos',
        'detalle_otros_montos',
        'total_horas_extras_otros_montos',
        'modo_pago',
        'tipo_pago',
        'servicio_id',
        'caja_id'
    ];



    protected $guarded = [];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function pagos_extras()
    {
        return $this->hasMany(Pago_extra::class);
    }
    public function excedentes()
    {
        return $this->hasMany(Excedentes_Recibidos_Caja_Actual::class);
    }

    public function cajas()
    {
        return $this->belongsTo(Caja::class);
    }
}
