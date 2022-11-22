<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Helper;
use Artisan;
use Carbon\Carbon;
use Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\monthlyPortal',
        'App\Console\Commands\CorreoCuotas',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        
        \Helper::emailCuotas();
        $dia = Carbon::now();
        $primeroDeMes = Carbon::now()->firstOfMonth();
        if($dia->format('d/m/Y') == $primeroDeMes->format('d/m/Y')){
            Log::debug('SE EJECUTA LA TAREA MENSUAL');
            \Helper::monthlyPortal();
        }
       
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
