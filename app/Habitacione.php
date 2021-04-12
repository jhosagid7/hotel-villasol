<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Habitacione;

class Habitacione extends Model
{
    protected $fillabel = [
        'cat_id',
        'level_id',
        'nonbre',
        'estado'
    ];


    protected $guarded = [];

    public function cat()
    {
        return $this->belongsTo(Cat::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
    public static function getHabitacionesId($id) {
        // $user = Auth::user();
        // Get the currently authenticated user's ID...
        // $id = Auth::id();

        return Habitacione::where('level_id', $id)
               ->orderBy('id', 'desc')
               ->get();
        // return Sessioncaja::find($session_caja_id);
    }

}
