<?php

use App\Caja;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CajaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //limpiar las tablas antes de llenarlas (truncate)
        //primero desactivamos las restricciones de llaves foranias
        DB::statement("SET foreign_key_checks=0");

        //hacemos truncate a las tablas que tienen modelos pero con eloquent
        Caja::truncate();

        //ahor habilitamos los freignKey
        DB::statement("SET foreign_key_checks=1");

        $sucursal = Caja::create([
            'nombre' => 'Caja 1',
            'descripcion' => 'Caja principal',
            'estado' => 'Cerrada',
            'sucursal_id' => 1
        ]);

        $sucursal = Caja::create([
            'nombre' => 'Caja 2',
            'descripcion' => 'Caja Sucursal',
            'estado' => 'Cerrada',
            'sucursal_id' => 1
        ]);

        

    }
}
