<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Coin\Coin;
use Faker\Generator as Faker;

$factory->define(Coin::class, function (Faker $faker) {
    return [
        'symbol' => 'BTC',
        'name' => 'Bitcoin',
        'type' => COIN_TYPE_CRYPTO,
    ];
});
