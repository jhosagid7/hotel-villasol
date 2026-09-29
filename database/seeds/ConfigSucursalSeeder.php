<?php

use Illuminate\Database\Seeder;
use App\Config_sucursal;

class ConfigSucursalSeeder extends Seeder
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
                'precioHorasExtra' => '5.00',
                'minutosMaximosCobrar' => 30,
                'sucursal_id' => 1,
            ],
        ];

        foreach ($records as $record) {
            App\Config_sucursal::updateOrCreate(
                ['id' => $record['id']],
                $record
            );
        }
    }
}
