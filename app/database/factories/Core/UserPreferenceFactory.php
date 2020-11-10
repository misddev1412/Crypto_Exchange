<?php

namespace Database\Factories\Core;

use App\Models\Core\UserPreference;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserPreferenceFactory extends Factory
{
    protected $model = UserPreference::class;

    public function definition()
    {
        return [
            'user_id' => $this->faker->uuid,
            'default_language' => null,
        ];
    }
}
