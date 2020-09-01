<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SessionCaja extends Model
{
    public $timestamps = true;
    protected $fillabel = [
        'status'
    ];

    protected $guarded = [];

    //creamos un metodo no estatico para llamarlo como un objeto y poder
    // llamarlo desde cualquier lado no dentro de la clase como lo static function
    public function totalVentas(){
        return $this->id;
    }

    //Creamos un metodo integrador
    public static function buscarOrCrearIDSession($session_caja_id) {
        if ($session_caja_id) {
            //si existe Buscamos el id de la session_caja
            return SessionCaja::buscarPorSession($session_caja_id);
        }else {
            //si no existe Bamos a crear un session_caja_id
            return SessionCaja::crearSinSession();
        }
    }

    //ahora creamos un metodo para que nos realice las acciones correspondientes
        //los metodos deben hacer una sola cosa

    //este metodo nos ba a buscar la session_caja id
    public static function buscarPorSession($session_caja_id) {
        return SessionCaja::find($session_caja_id);
    }

    //ahora creamos el metodo para crear la session caja
    public static function crearSinSession(){
        // $sessionCaja = new SessionCaja;
        // $sessionCaja->status = 'Abierta';
        // $sessionCaja->save();
        // return $sessionCaja;

        return SessionCaja::create([
            'status' => 'Abierta'
        ]);
    }
}
