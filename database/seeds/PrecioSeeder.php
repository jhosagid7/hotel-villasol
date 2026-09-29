<?php

use Illuminate\Database\Seeder;
use App\Precio;

class PrecioSeeder extends Seeder
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
                'horario_id' => 1,
                'cat_id' => 1,
                'precio' => '16.000',
            ],
            [
                'id' => 2,
                'horario_id' => 1,
                'cat_id' => 2,
                'precio' => '18.000',
            ],
            [
                'id' => 3,
                'horario_id' => 1,
                'cat_id' => 3,
                'precio' => '18.000',
            ],
            [
                'id' => 4,
                'horario_id' => 1,
                'cat_id' => 4,
                'precio' => '20.000',
            ],
            [
                'id' => 5,
                'horario_id' => 1,
                'cat_id' => 5,
                'precio' => '20.000',
            ],
            [
                'id' => 6,
                'horario_id' => 2,
                'cat_id' => 1,
                'precio' => '22.000',
            ],
            [
                'id' => 7,
                'horario_id' => 2,
                'cat_id' => 2,
                'precio' => '22.000',
            ],
            [
                'id' => 8,
                'horario_id' => 2,
                'cat_id' => 3,
                'precio' => '24.000',
            ],
            [
                'id' => 9,
                'horario_id' => 2,
                'cat_id' => 4,
                'precio' => '24.000',
            ],
            [
                'id' => 10,
                'horario_id' => 2,
                'cat_id' => 5,
                'precio' => '24.000',
            ],
            [
                'id' => 11,
                'horario_id' => 2,
                'cat_id' => 6,
                'precio' => '25.000',
            ],
            [
                'id' => 12,
                'horario_id' => 3,
                'cat_id' => 1,
                'precio' => '29.000',
            ],
            [
                'id' => 13,
                'horario_id' => 3,
                'cat_id' => 2,
                'precio' => '29.000',
            ],
            [
                'id' => 14,
                'horario_id' => 3,
                'cat_id' => 3,
                'precio' => '31.000',
            ],
            [
                'id' => 15,
                'horario_id' => 3,
                'cat_id' => 4,
                'precio' => '31.000',
            ],
            [
                'id' => 16,
                'horario_id' => 3,
                'cat_id' => 5,
                'precio' => '33.000',
            ],
            [
                'id' => 17,
                'horario_id' => 3,
                'cat_id' => 6,
                'precio' => '35.000',
            ],
            [
                'id' => 18,
                'horario_id' => 1,
                'cat_id' => 6,
                'precio' => '22.000',
            ],
            [
                'id' => 19,
                'horario_id' => 3,
                'cat_id' => 7,
                'precio' => '35.000',
            ],
        ];

        foreach ($records as $record) {
            App\Precio::updateOrCreate(
                ['id' => $record['id']],
                $record
            );
        }
    }
}
