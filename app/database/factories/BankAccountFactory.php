<?php

/** @var Factory $factory */

use App\Models\BankAccount\BankAccount;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(BankAccount::class, function (Faker $faker) {
    return [
        'user_id' => null,
        'country_id' => $faker->numberBetween(1, 246),
        'bank_name' => $faker->company,
        'iban' => $faker->iban(),
        'swift' => $faker->swiftBicNumber,
        'reference_number' => $faker->bankAccountNumber,
        'account_holder' => $faker->name,
        'bank_address' => $faker->address,
        'account_holder_address' => $faker->address,
        'is_verified' => ACTIVE,
        'is_active' => ACTIVE,
    ];
});
