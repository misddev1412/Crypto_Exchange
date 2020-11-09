<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Post\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(random_int(5,10)),
        'content' => $faker->sentences(random_int(3,10), true),
        'is_published' => $faker->boolean(80),
        'is_featured' => $faker->boolean(10),
    ];
});
