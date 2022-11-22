<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Configura;
use Carbon\Carbon;
use Helper;
use Log;
use App\User;
use App\Cuota;

class CorreoCuotas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'correo:cuotas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía un correo a los usuarios cuyas cuotas vayan a caducar pronto';

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
        //FUNCION NO OPERATIVA DEVIDO A MEDIDAS DE SEGURIDAD EN EL SERVIDOR
    }
}
