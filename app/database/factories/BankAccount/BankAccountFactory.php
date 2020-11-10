<?php

namespace Database\Factories\BankAccount;

use App\Models\BankAccount\BankAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankAccountFactory extends Factory
{
    protected $model = BankAccount::class;

    public function definition()
    {
        return [
            'user_id' => null,
            'country_id' => $this->faker->numberBetween(1, 246),
            'bank_name' => $this->faker->company,
            'iban' => $this->faker->iban(),
            'swift' => $this->faker->swiftBicNumber,
            'reference_number' => $this->faker->bankAccountNumber,
            'account_holder' => $this->faker->name,
            'bank_address' => $this->faker->address,
            'account_holder_address' => $this->faker->address,
            'is_verified' => ACTIVE,
            'is_active' => ACTIVE,
        ];
    }
}
