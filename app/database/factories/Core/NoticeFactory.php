<?php

namespace Database\Factories\Core;

use App\Models\Core\Notice;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoticeFactory extends Factory
{
    protected $model = Notice::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'type' => $this->faker->randomElement(array_keys(notices_types())),
            'visible_type' => $this->faker->randomElement([NOTICE_VISIBLE_TYPE_PUBLIC, NOTICE_VISIBLE_TYPE_PRIVATE]),
            'start_at' => $this->faker->dateTimeThisMonth('now'),
            'end_at' => $this->faker->dateTimeBetween('+1 days', '+1 months'),
            'is_active' => $this->faker->boolean,
            'created_by' => 1
        ];
    }
}
