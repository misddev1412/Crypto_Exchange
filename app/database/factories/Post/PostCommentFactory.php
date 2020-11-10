<?php

namespace Database\Factories\Post;

use App\Models\Post\PostComment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostCommentFactory extends Factory
{
    protected $model = PostComment::class;

    public function definition()
    {
        return [
            'content' => $this->faker->sentences(random_int(1, 2), true),
        ];
    }
}
