<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Model::class, function (Faker $faker) {
    return [
        'user_id' => Str::uuid(),
        'coin_id' => Str::uuid(),
        'primary_balance' => 100000,
        'is_system_wallet' => INACTIVE
    ];
});
