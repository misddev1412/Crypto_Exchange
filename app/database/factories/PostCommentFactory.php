<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Post\PostComment;
use Faker\Generator as Faker;

$factory->define(PostComment::class, function (Faker $faker) {
    return [
        'content' => $faker->sentences(random_int(1,2), true),
    ];
});
