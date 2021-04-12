<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{

    public function Ventas(){
        return $this->hasMany(Venta::class);
    }



    public function ingresos(){
        return $this->hasMany(Ingreso::class);
    }

    public function creditos(){
        return $this->hasMany(Credito::class);
    }

    public function creditosPagados(){
        return $this->hasMany(Credito_Pagado::class);
    }

    public function user(){
        return $this->hasOneThrough(User::class,Ingreso::class);
    }

    public function servicios(){
        return $this->hasMany(Servicio::class);
    }

    // protected $table = 'persona';

    // protected $primaryKey = 'idpersona';

    // public $timestamps = false;

    protected $fillabel = [
        'tipo_persona',
        'nombre',
        'tipo_documento',
        'num_documento',
        'direccion',
        'telefono',
        'email',
        'isCortesia',
        'Credito',
        'limite_fecha',
        'limite_monto',
        'imagen'
    ];

    protected $guarded = [];

    public function setIsCortesiaAttribute($value){
        $this->attributes['isCortesia'] = ($value == 'on' ? '1' : null);
    }

    public function setIsCreditoAttribute($value){
        $this->attributes['isCredito'] = ($value == 'on' ? '1' : null);
    }


}
