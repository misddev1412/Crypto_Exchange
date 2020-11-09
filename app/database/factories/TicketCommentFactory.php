<?php

/** @var Factory $factory */

use App\Models\Core\User;
use App\Models\Ticket\TicketComment;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(TicketComment::class, function (Faker $faker) {
    return [
        'user_id' => User::inRandomOrder()->first()->id,
        'content' => $faker->sentence,
        'created_at' => $faker->dateTimeThisMonth
    ];
});
