<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('backup:clean')->everyMinute();
        $schedule->command('backup:run')->everyMinute();

        // $schedule->command('backup:c')->hourly()
        // ->timezone('America/Caracas')
        // ->between('7:00', '22:00');

        // $schedule->call(function () {
        //     DB::table('recent_users')->delete();
        // })->daily();

        // $schedule->call('App\Http\Controllers\CajaController@index')
        // ->everyMinute()
        // ->sendOutputTo('cron-output.txt');

        // $schedule->exec('node /home/forge/script.js')->everyMinute();
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
