<?php

namespace Database\Factories\Ticket;

use App\Models\Core\User;
use App\Models\Ticket\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition()
    {
        return [
            'id' => $this->faker->uuid,
            'user_id' => User::inRandomOrder()->first()->id,
            'assigned_to' => User::where('assigned_role', USER_ROLE_ADMIN)->inRandomOrder()->first()->id,
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(array_keys(ticket_status())),
        ];
    }
}
