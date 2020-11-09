<?php

/** @var Factory $factory */

use App\Models\Core\UserPreference;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(UserPreference::class, function (Faker $faker) {
    return [
        'user_id' => $faker->uuid,
        'default_language' => null,
    ];
});
