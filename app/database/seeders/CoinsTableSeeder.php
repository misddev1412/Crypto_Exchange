<?php

namespace Database\Seeders;

use App\Jobs\Wallet\GenerateUsersWalletsJob;
use App\Models\BankAccount\BankAccount;
use App\Models\Coin\Coin;
use App\Models\Coin\CoinPair;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CoinsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('coin_pairs')->truncate();
        DB::table('coins')->truncate();
        DB::table('bank_accounts')->truncate();
        Schema::enableForeignKeyConstraints();

        $systemBank = BankAccount::factory()->create();
        $coins = [
            [
                'symbol' => 'BTC',
                'name' => 'Bitcoin',
                'type' => COIN_TYPE_CRYPTO,
                'exchange_status' => ACTIVE,
                'deposit_status' => ACTIVE,
                'withdrawal_status' => ACTIVE,
                'withdrawal_fee' => 0.00002,
                'minimum_withdrawal_amount' => 0.001,
                'api' => [
                    "selected_apis" => "BitcoinForkedApi",
                ],
            ],
            [
                'symbol' => 'USD',
                'name' => 'United States Dollar',
                'type' => COIN_TYPE_FIAT,
                'exchange_status' => ACTIVE,
                'deposit_status' => ACTIVE,
                'withdrawal_status' => ACTIVE,
                'withdrawal_fee' => 0.05,
                'minimum_withdrawal_amount' => 0.1,
                'api' => [
                    "selected_apis" => ["BankApi"],
                    "selected_banks" => [$systemBank->id]
                ]
            ]
        ];

        $createdCoins = [];

        foreach ($coins as $coin) {
            $createdCoins[] = $model = Coin::create($coin);
            if (env('QUEUE_CONNECTION', 'sync') === 'sync') {
                GenerateUsersWalletsJob::dispatchNow($model);
            } else {
                GenerateUsersWalletsJob::dispatch($model);
            }
        }

        if (!empty($createdCoins)) {
            CoinPair::factory()->create([
                'trade_coin' => Arr::first($createdCoins),
                'base_coin' => Arr::last($createdCoins),
                'is_active' => ACTIVE,
                'is_default' => ACTIVE,
            ]);
        }
    }
}
