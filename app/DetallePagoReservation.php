<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePagoReservation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reservacion()
    {
        return $this->belongsTo(Reservation::class);
    }
}
