<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }
    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function detalle_pago_reservaciones()
    {
        return $this->hasMany(DetallePagoReservation::class);
    }
}
