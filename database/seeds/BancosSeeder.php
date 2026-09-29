<?php

use App\Banco;
use Illuminate\Database\Seeder;

class BancosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Banco::truncate();


        //creamos nuestro registro para la tasa Dolar
        //Tasa Dolar
        $Banco=Banco::create([
            'codigo'=>'0001',
            'nombre_banco'=>'Banco Central de Venezuela'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0003',
            'nombre_banco'=>'Banco Industrial de Venezuela C.A. Banco'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0102',
            'nombre_banco'=>'Banco de Venezuela S.A.C.A. Banco Universal'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0104',
            'nombre_banco'=>'Venezolano de Crédito S.A. Banco Universal'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0105',
            'nombre_banco'=>'Banco Mercantil C.A S.A.C.A. Banco Universal'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0108',
            'nombre_banco'=>'Banco Provincial S.A. Banco Universal'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0114',
            'nombre_banco'=>'Bancaribe C.A. Banco Universal'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0115',
            'nombre_banco'=>'Banco Exterior C.A. Banco Universal'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0116',
            'nombre_banco'=>'Banco Occidental de Descuento. Banco'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0128',
            'nombre_banco'=>'Banco Caroní C.A. Banco Universal'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0134',
            'nombre_banco'=>'Banesco Banco Universal S.A.C.A.'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0137',
            'nombre_banco'=>'Banco Sofitasa Banco Universal'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0138',
            'nombre_banco'=>'Banco Plaza Banco Universal'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0146',
            'nombre_banco'=>'Banco de la Gente Emprendedora C.A.'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0149',
            'nombre_banco'=>'Banco del Pueblo Soberano C.A. Banco de Desarrollo'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0151',
            'nombre_banco'=>'BFC Banco Fondo Común C.A Banco Universal'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0156',
            'nombre_banco'=>'100% Banco. Banco Universal C.A.'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0157',
            'nombre_banco'=>'DelSur Banco Universal C.A.'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0163',
            'nombre_banco'=>'Banco del Tesoro C.A. Banco Universal'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0166',
            'nombre_banco'=>'Banco Agrícola de Venezuela C.A. Banco Universal'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0168',
            'nombre_banco'=>'Bancrecer S.A. Banco Microfinanciero'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0169',
            'nombre_banco'=>'Mi Banco Banco Microfinanciero C.A.'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0171',
            'nombre_banco'=>'Banco Activo C.A. Banco Universal'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0172',
            'nombre_banco'=>'Bancamiga Banco Microfinanciero C.A.'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0173',
            'nombre_banco'=>'Banco Internacional de Desarrollo C.A. Banco Universal'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0174',
            'nombre_banco'=>'Banco Banplus.'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0175',
            'nombre_banco'=>'Banco Bicentenario.'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0177',
            'nombre_banco'=>'Banco Banfanb.'
        ]);

        $Banco=Banco::create([
            'codigo'=>'0191',
            'nombre_banco'=>'Banco Nacional de Crédito BNC.'
        ]);




    }
}
