<?php

namespace Database\Factories\Core;

use App\Models\Core\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'permissions' => [],
            'accessible_routes' => [],
            'is_active' => $this->faker->boolean,
        ];
    }
}
