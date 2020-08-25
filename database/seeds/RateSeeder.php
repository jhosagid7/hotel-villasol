<?php

use App\Rate;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //hacemos truncate a las tablas que tienen modelos pero con eloquent
        Rate::truncate();


        //creamos nuestro registro para la tasa Dolar
        //Tasa Dolar
        $TasaDolar=Rate::create([
            'nombre'=>'Dolar',
            'tasa'=> 0,
            'porcentaje_ganancia'=>0,
            'estado'=>'Activo'
        ]);

        //creamos nuestro registro para la tasa Peso
        //Tasa Peso
        $TasaPeso=Rate::create([
            'nombre'=>'Peso',
            'tasa'=> 0,
            'porcentaje_ganancia'=>0,
            'estado'=>'Activo'
        ]);

        //creamos nuestro registro para la tasa Transferencia_Punto
        //Tasa Transferencia_Punto
        $TasaTransferencia_Punto=Rate::create([
            'nombre'=>'Transferencia_Punto',
            'tasa'=> 0,
            'porcentaje_ganancia'=>0,
            'estado'=>'Activo'
        ]);

        //creamos nuestro registro para la tasa Mixto
        //Tasa Mixto
        $TasaMixto=Rate::create([
            'nombre'=>'Mixto',
            'tasa'=> 0,
            'porcentaje_ganancia'=>0,
            'estado'=>'Activo'
        ]);

        //creamos nuestro registro para la tasa Efectivo
        //Tasa Efectivo
        $TasaEfectivo=Rate::create([
            'nombre'=>'Efectivo',
            'tasa'=> 0,
            'porcentaje_ganancia'=>0,
            'estado'=>'Activo'
        ]);
    }
}
