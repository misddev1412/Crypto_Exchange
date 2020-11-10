<?php

namespace Database\Factories\Post;

use App\Models\Post\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(random_int(5, 10)),
            'content' => $this->faker->sentences(random_int(3, 10), true),
            'is_published' => $this->faker->boolean(80),
            'is_featured' => $this->faker->boolean(10),
        ];
    }
}
