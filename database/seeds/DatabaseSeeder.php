<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call(JhosagidPermissionInfoSeeder::class);
        $this->call(EmpresaSeeder::class);
        $this->call(SucursalSeeder::class);
        $this->call(ConfigSucursalSeeder::class);
        $this->call(LevelSeeder::class);
        $this->call(CatSeeder::class);
        $this->call(HorarioSeeder::class);
        $this->call(PrecioSeeder::class);
        $this->call(HabitacioneSeeder::class);
        $this->call(TasaSeeder::class);
        $this->call(DenominacionSeeder::class);
        $this->call(PersonaSeeder::class);
        $this->call(BancosSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
