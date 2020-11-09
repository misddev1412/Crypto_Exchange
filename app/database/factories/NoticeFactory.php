<?php
/** @var Factory $factory */

use App\Models\Core\Notice;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Notice::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'type' => $faker->randomElement(array_keys(notices_types())),
        'visible_type' => $faker->randomElement([NOTICE_VISIBLE_TYPE_PUBLIC, NOTICE_VISIBLE_TYPE_PRIVATE]),
        'start_at' => $faker->dateTimeThisMonth('now'),
        'end_at' => $faker->dateTimeBetween('+1 days', '+1 months'),
        'is_active' => $faker->boolean,
        'created_by' => 1
    ];
});
