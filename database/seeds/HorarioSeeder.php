<?php

use Illuminate\Database\Seeder;
use App\Horario;

class HorarioSeeder extends Seeder
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
                'tipo' => 'DIURNO',
                'nombre' => 'SERVICIO DIURNO ENTRE 5:00 AM. Y 9:00 PM.',
                'desde' => '05:00:00',
                'hasta' => '21:00:00',
                'restringir_desde' => '19:00:00',
                'restringir_hasta' => NULL,
                'is24Horas' => NULL,
            ],
            [
                'id' => 2,
                'tipo' => 'COMERCIAL',
                'nombre' => 'SERVICIO COMERCIAL ENTRE 5:00 PM A 12:00 AM.',
                'desde' => '17:00:00',
                'hasta' => '12:00:00',
                'restringir_desde' => '09:00:00',
                'restringir_hasta' => NULL,
                'is24Horas' => NULL,
            ],
            [
                'id' => 3,
                'tipo' => '24 HORAS',
                'nombre' => 'SERVICIO EJECUTIVO 24 HORAS',
                'desde' => NULL,
                'hasta' => NULL,
                'restringir_desde' => NULL,
                'restringir_hasta' => NULL,
                'is24Horas' => 1,
            ],
        ];

        foreach ($records as $record) {
            App\Horario::updateOrCreate(
                ['id' => $record['id']],
                $record
            );
        }
    }
}
