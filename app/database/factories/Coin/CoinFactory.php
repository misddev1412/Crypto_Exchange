<?php

namespace Database\Factories\Coin;

use App\Models\Coin\Coin;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoinFactory extends Factory
{
    protected $model = Coin::class;

    public function definition()
    {
        return [
            'symbol' => 'BTC',
            'name' => 'Bitcoin',
            'type' => COIN_TYPE_CRYPTO,
        ];
    }
}
