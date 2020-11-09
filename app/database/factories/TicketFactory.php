<?php

/** @var Factory $factory */

use App\Models\Core\User;
use App\Models\Ticket\Ticket;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Ticket::class, function (Faker $faker) {
    return [
        'id' => $faker->uuid,
        'user_id' => User::inRandomOrder()->first()->id,
        'assigned_to' => User::where('assigned_role', USER_ROLE_ADMIN)->inRandomOrder()->first()->id,
        'title' => $faker->sentence,
        'content' => $faker->paragraph,
        'status' => $faker->randomElement(array_keys(ticket_status())),
    ];
});
