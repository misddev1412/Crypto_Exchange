<?php

/** @var Factory $factory */

use App\Models\Core\Notification;
use App\Models\Core\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Notification::class, function (Faker $faker) {
    return [
        'user_id' => User::inRandomOrder()->first()->id,
        'message' => $faker->sentence
    ];
});
