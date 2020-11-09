<?php

/** @var Factory $factory */

use App\Models\Order\Order;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'user_id' => $faker->uuid,
        'trade_coin' => 'BTC',
        'base_coin' => 'USD',
        'category' => ORDER_CATEGORY_LIMIT,
        'type' => $faker->randomElement([ORDER_TYPE_BUY, ORDER_TYPE_SELL]),
        'price' => $faker->randomFloat(6000, 8000),
        'amount' => $faker->randomFloat(0.001, 2),
        'status' => STATUS_PENDING
    ];
});
