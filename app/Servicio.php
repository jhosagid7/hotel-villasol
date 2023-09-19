<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }

    public function habitacion()
    {
        return $this->belongsTo(Habitacione::class);
    }

    public function pago_servicios()
    {
        return $this->hasMany(Pago_Servicio::class);
    }

    public function pago_ventas()
    {
        return $this->hasMany(Pago_Venta::class);
    }
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function pago_vueltos()
    {
        return $this->hasMany(Pago_Vuelto::class);
    }

    public function servicios_ventas()
    {
        return $this->hasMany(Servicios_Ventas::class);
    }

    // public function creditos(){
    //     return $this->hasMany(Credito::class);
    // }

    public function articulo()
    {
        return $this->hasManyThrough(Articulo::class, Servicios_Ventas::class);
    }

    public function excedente_actual()
    {
        return $this->hasMany(Excedentes_Recibidos_Caja_Actual::class);
    }

    public function historial_vueltos_pendientes()
    {
        return $this->hasMany(Historial_Vueltos_Pendiente::class);
    }

    public function historialExcedentes()
    {
        return $this->hasMany(HistorialExcedente::class);
    }

    public function detalle_pago_oficina()
    {
        return $this->hasMany(DetallePagoOficina::class);
    }

    public function Cambios()
    {
        return $this->hasMany(Cambio::class);
    }

    public function servicio_id_cambios()
    {
        return $this->hasMany(Cambio::class);
    }

    public function undeliveredChanges()
    {
        return $this->hasMany(Excedentes_Recibidos_Caja_Actual::class);
    }



    // public function habitacion()
    // {
    //     return $this->belongsTo(Habitacione::class);
    // }





    protected $fillabel = [
        'num_servicio',
        'operador',
        'status_servicio',
        'id_habitacion',
        'nombre_habitacion',
        'detalle_habitacion',
        'tipo_habitacion',
        'horario',
        'fecha_entrada',
        'hora_entrada',
        'fecha_salida',
        'hora_salida',
        'tasaDolar',
        'porDolar',
        'tasaPeso',
        'porPeso',
        'tasaTransPunto',
        'porTransPunto',
        'tasaMixto',
        'porMixto',
        'tasaEfectivo',
        'porEfectivo',
        'tasaDolarHabitacion',
        'porDolarHabitacion',
        'tasaPesoHabitacion',
        'porPesoHabitacion',
        'num_Punto',
        'num_Trans',
        'modo_pago',
        'tipo_pago',
        'is_cambio',
        'precio_costo',
        'cantidad',
        'dinero_dejado',
        'excedente_nuevo',
        'pago_con_excedente',
        'total_venta',
        'estado',
        'nombre_cliente',
        'cedula_cliente',
        'direccion_cliente',
        'telefono_cliente',
        'limite_fecha',
        'limite_monto',
        'persona_id ',
        'user_id ',
        'caja_id '
    ];

    protected $dates = [
        'fecha_hora',

    ];


    protected $guarded = [];
}
