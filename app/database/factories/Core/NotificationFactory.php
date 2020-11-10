<?php

namespace Database\Factories\Core;

use App\Models\Core\Notification;
use App\Models\Core\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'message' => $this->faker->sentence
        ];
    }
}
