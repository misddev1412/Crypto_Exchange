<?php

namespace Database\Seeders;

use App\Models\Withdrawal\WalletWithdrawal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WithdrawalsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('wallet_withdrawals')->truncate();
        Schema::enableForeignKeyConstraints();

        WalletWithdrawal::factory()->count(100)->create();
    }
}
