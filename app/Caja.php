<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $fillabel     = [
        'codigo',
        'fecha',
        'hora_cierre',
        'hora',
        'mes',
        'year',
        'monto_dolar',
        'monto_peso',
        'monto_bolivar',
        'monto_dolar_cierre',
        'monto_peso_cierre',
        'monto_bolivar_cierre',
        'estado',
        'caja',
        'tasaActualVenta',
        'margenActualVenta',
        'user_id',
        'sucursal_id',
        'sessioncaja_id'
    ];

    //Ahora especificamos los campos guarded
    protected $guarded=[];

    protected $dates = [
        'fecha',
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ventas(){
        return $this->hasMany(Venta::class);
    }

    public function pagos_vueltos_extra(){
        return $this->hasMany(Pago_Vuelto::class);
    }

    public function horas_extras(){
        return $this->hasMany(Horas_extra::class);
    }

    public function excedente_anterior(){
        return $this->hasMany(Excedentes_Pendientes_Caja_Anterior::class);
    }

    public function excedente_actual(){
        return $this->hasMany(Excedentes_Recibidos_Caja_Actual::class);
    }

    public function historial_vueltos_pendientes(){
        return $this->hasMany(Historial_Vueltos_Pendiente::class);
    }

    public function pago_ventas()
    {
        return $this->hasManyThrough(Pago_Venta::class, Venta::class);
    }

    public function personas()
    {
        return $this->hasManyThrough(Persona::class, Venta::class);
    }

    public function articulo_ventas()
    {
        return $this->hasManyThrough(Articulo_Venta::class, Venta::class);
    }

    public function servicios(){
        return $this->hasMany(Servicio::class);
    }

    public function detalle_creditos(){
        return $this->hasMany(Detalle_credito::class);
    }

    public function creditos_pagados(){
        return $this->hasMany(Credito_Pagado::class);
    }


    public function creditos()
    {
        return $this->hasManyThrough(Credito::class, Detalle_credito::class);
    }

    public function cortesias()
    {
        return $this->hasManyThrough(Cortesia::class, Servicio::class);
    }

    public function pago_servicios()
    {
        return $this->hasManyThrough(Pago_Servicio::class, Servicio::class);
    }

    public function pago_vueltos()
    {
        return $this->hasManyThrough(Pago_Vuelto::class, Servicio::class);
    }

    public function pago_creditos()
    {
        return $this->hasManyThrough(Pago_Credito::class, Detalle_credito::class);
    }



    //este metodo nos ba a verificar si existe una caja abierta en el modelo Caja
    public static function buscarCaja() {
        // $user = Auth::user();
        // Get the currently authenticated user's ID...
        $id = Auth::id();

        return Caja::where('estado', 'Abierta')
                ->where('user_id','=' ,$id)
               ->orderBy('id', 'desc')
               ->first();
        // return Sessioncaja::find($session_caja_id);
    }
}
