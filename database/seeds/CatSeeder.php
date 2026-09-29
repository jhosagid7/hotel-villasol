<?php

use Illuminate\Database\Seeder;
use App\Cat;

class CatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $records = [
            [
                'id' => 1,
                'nombre' => 'ESTANDAR SUITE',
                'descripcion' => 'ESTANDAR SUITE',
                'estado' => 'Activa',
            ],
            [
                'id' => 2,
                'nombre' => 'SUITE',
                'descripcion' => 'SUITE',
                'estado' => 'Activa',
            ],
            [
                'id' => 3,
                'nombre' => 'PREMIUN SUITE',
                'descripcion' => 'PREMIUN SUITE',
                'estado' => 'Activa',
            ],
            [
                'id' => 4,
                'nombre' => 'EXECUTIVE SUITE',
                'descripcion' => 'EXECUTIVE SUITE',
                'estado' => 'Activa',
            ],
            [
                'id' => 5,
                'nombre' => 'LUXURY SUITE',
                'descripcion' => 'LUXURY SUITE',
                'estado' => 'Activa',
            ],
            [
                'id' => 6,
                'nombre' => 'DOBLE',
                'descripcion' => 'DOBLE',
                'estado' => 'Activa',
            ],
            [
                'id' => 7,
                'nombre' => 'SUITE DE LUJO',
                'descripcion' => 'SUITE DE LUJO',
                'estado' => 'Activa',
            ],
        ];

        foreach ($records as $record) {
            App\Cat::updateOrCreate(
                ['id' => $record['id']],
                $record
            );
        }
    }
}
