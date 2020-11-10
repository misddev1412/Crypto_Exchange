<?php

namespace Database\Factories\Ticket;

use App\Models\Core\User;
use App\Models\Ticket\TicketComment;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketCommentFactory extends Factory
{
    protected $model = TicketComment::class;

    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'content' => $this->faker->sentence,
            'created_at' => $this->faker->dateTimeThisMonth
        ];
    }
}
