<?php

/** @var Factory $factory */

use App\Models\Post\PostCategory;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(PostCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->words(random_int(1,3), true)
    ];
});
