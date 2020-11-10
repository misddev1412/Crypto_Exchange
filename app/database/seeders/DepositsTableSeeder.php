<?php

namespace Database\Seeders;

use App\Models\Deposit\WalletDeposit;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DepositsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('wallet_deposits')->truncate();
        Schema::enableForeignKeyConstraints();

        WalletDeposit::factory()->count(100)->create();
    }
}
