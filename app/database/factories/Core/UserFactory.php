<?php

namespace Database\Factories\Core;

use App\Models\Core\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'assigned_role' => USER_ROLE_USER,
            'username' => $this->faker->userName,
            'email' => $this->faker->unique()->email,
            'password' => Hash::make('user'),
            'is_accessible_under_maintenance' => $this->faker->boolean,
            'is_email_verified' => $this->faker->boolean,
            'is_super_admin' => false,
            'status' => $this->faker->randomElement([STATUS_INACTIVE, STATUS_ACTIVE, STATUS_DELETED]),
        ];
    }
}
