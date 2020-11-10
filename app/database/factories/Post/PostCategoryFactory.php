<?php

namespace Database\Factories\Post;

use App\Models\Post\PostCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostCategoryFactory extends Factory
{
    protected $model = PostCategory::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(random_int(1, 3), true)
        ];
    }
}
