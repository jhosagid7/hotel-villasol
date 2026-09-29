<?php

use Illuminate\Database\Seeder;
use App\Level;

class LevelSeeder extends Seeder
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
                'sucursal_id' => 1,
                'nombre' => 'ALA DERECHA',
                'estado' => 'Activo',
            ],
            [
                'id' => 2,
                'sucursal_id' => 1,
                'nombre' => 'EDIF. ARRIBA',
                'estado' => 'Activo',
            ],
            [
                'id' => 3,
                'sucursal_id' => 1,
                'nombre' => 'EDIF. ABAJO',
                'estado' => 'Activo',
            ],
            [
                'id' => 4,
                'sucursal_id' => 1,
                'nombre' => 'ALA IZQUIERDA',
                'estado' => 'Activo',
            ],
            [
                'id' => 5,
                'sucursal_id' => 1,
                'nombre' => 'FONDO PLANTA',
                'estado' => 'Activo',
            ],
        ];

        foreach ($records as $record) {
            App\Level::updateOrCreate(
                ['id' => $record['id']],
                $record
            );
        }
    }
}
