<?php

namespace Database\Factories\Core;

use App\Models\Core\Navigation;
use Illuminate\Database\Eloquent\Factories\Factory;

class NavigationFactory extends Factory
{
    protected $model = Navigation::class;

    public function definition()
    {
        return [
            'slug' => $this->faker->name,
            'items' => '[]'
        ];
    }
}
