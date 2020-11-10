<?php

namespace Database\Factories\Coin;

use App\Models\Coin\CoinPair;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoinPairFactory extends Factory
{
    protected $model = CoinPair::class;

    public function definition()
    {
        return [
            'trade_coin' => 'BTC',
            'base_coin' => 'USD',
            'last_price' => $this->faker->numberBetween(7000, 10000),
        ];
    }
}
