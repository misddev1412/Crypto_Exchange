<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Core\ApplicationSetting;
use Faker\Generator as Faker;

$factory->define(ApplicationSetting::class, function (Faker $faker) {
    return [
        'slug' => $faker->name,
        'value' => $faker->name
    ];
});
