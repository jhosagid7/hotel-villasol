<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use  App\Permission\Traits\UserTrait;
use App\Permission\Models\Role;
class User extends Authenticatable
{
    use Notifiable, UserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public function cajas(){
        return $this->hasMany(Caja::class);
    }

    public function servicios(){
        return $this->hasMany(Servicio::class);
    }

    public function creditos_pagados(){
        return $this->hasMany(Credito_Pagado::class);
    }



    public function ventas()
    {
        return $this->hasManyThrough(Venta::class, Caja::class);
    }

    public function ingresos(){
        return $this->hasMany(Ingreso::class);
    }


    public function persona(){
        return $this->hasOneThrough(Persona::class, Ingreso::class);
    }

    public function Transactions(){
        return $this->hasMany(Transactions::class);
    }

    public function scopeOperador($query, $operador){
        if($operador)
        return $query->where('user_id', '=', "$operador");
    }

    public function historialExcedentes(){
        return $this->hasMany(HistorialExcedente::class);
    }

    public function detalle_pago_oficina(){
        return $this->hasMany(DetallePagoOficina::class);
    }

    public function historial_creditos(){
        return $this->hasMany(HistorialCreditoCaja::class);
    }

}
