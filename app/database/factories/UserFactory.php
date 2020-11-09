<?php
/** @var Factory $factory */

use App\Models\Core\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'assigned_role' => USER_ROLE_USER,
        'username' => $faker->userName,
        'email' => $faker->unique()->email,
        'password' => Hash::make('user'),
        'is_accessible_under_maintenance' => $faker->boolean,
        'is_email_verified' => $faker->boolean,
        'is_super_admin' => false,
        'status' => $faker->randomElement([STATUS_INACTIVE, STATUS_ACTIVE, STATUS_DELETED]),
    ];
});
