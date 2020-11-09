<?php

/** @var Factory $factory */

use App\Models\Coin\CoinPair;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(CoinPair::class, function (Faker $faker) {
    return [
        'trade_coin' => 'BTC',
        'base_coin' => 'USD',
        'last_price' => $faker->numberBetween(7000, 10000),
    ];
});
