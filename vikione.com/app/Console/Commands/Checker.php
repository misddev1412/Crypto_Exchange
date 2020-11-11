<?php

namespace App\Console\Commands;

use App\Models\PaymentMethod;
use Illuminate\Console\Command;

class Checker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rate:check {base}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crypto-Compare currency rate checker';

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
        $base = strtoupper($this->argument('base'));
        $data = PaymentMethod::automatic_rate($base);

        info(json_encode($data));

        $this->info('Data saved to log...');
    }
}
