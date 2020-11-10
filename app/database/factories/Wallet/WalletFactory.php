<?php

namespace Database\Factories\Wallet;

use App\Models\Wallet\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class WalletFactory extends Factory
{
    protected $model = Wallet::class;

    public function definition()
    {
        return [
            'user_id' => Str::uuid(),
            'coin_id' => Str::uuid(),
            'primary_balance' => 100000,
            'is_system_wallet' => INACTIVE
        ];
    }
}
