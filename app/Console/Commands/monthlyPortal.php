<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Mail\Email;
use Illuminate\Support\Facades\Mail;
use App\Configura;
use Carbon\Carbon;
use Helper;
use Log;
use App\User;
use App\Cuota;
use App\Vocalia;

class monthlyPortal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monthly:portal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tareas que se ejecutan el día 1 de cada mes. Relacionadas con Tesorería principalmente.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // TAREAS QUE SE REALIZARAN CADA MES
        // Hay que crear las siguientes tareas:
        // calculo y apunte de lo asignado a cada vocalia
        //



    }
}
