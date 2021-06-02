<?php

namespace App;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

class Excedente extends Model
{
    protected $fillabel = [
        'nombre_cliente',
        'cedula_cliente',
        'direccion_cliente',
        'telefono_cliente',
        'excedente',
        'persona_id'
    ];

    public function cliente(){
        return $this->belongsTo(Persona::class);
    }


    protected $guarded = [];

    public function scopeName($query, $name){
        if($name)
        return $query->where('articulos.nombre', 'LIKE', "%$name%");
    }

    public function scopeCodigo($query, $codigo){
        if($codigo)
        return $query->where('articulos.codigo', 'LIKE', "$codigo");
    }

    public function scopeVenderaL($query, $venderal){
        if($venderal)
        return $query->where('articulos.vender_al', 'LIKE', "$venderal");
    }
    public function scopeMayor($query){

        return $query->where('vender_al', '=', "Mayor");
    }
    public function scopeDetal($query){

        return $query->where('vender_al', '=', "Detal");
    }

    public function scopeActivo($query){

        return $query->where('estado', '=', "Activo");
    }

    public function scopeInactivo($query){

        return $query->where('estado', '=', "Inactivo");
    }

    public function scopeFecha($query, $fecha){

        if($fecha){
        list($fecha_inicio, $fecha_fin) = explode(" - ", $fecha);
            $fecha_inicio = Carbon::parse($fecha_inicio. '00:00:00')->format('Y-m-d H:i:s');
            $fecha_fin = Carbon::parse($fecha_fin. '23:59:59')->format('Y-m-d H:i:s');
        return $query->whereBetween('articulos.created_at', [$fecha_inicio, $fecha_fin]);
        }
    }
}
