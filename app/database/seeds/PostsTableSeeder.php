<?php

use App\Models\Core\User;
use App\Models\Post\Post;
use App\Models\Post\PostCategory;
use App\Models\Post\PostComment;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('post_comments')->truncate();
        DB::table('posts')->truncate();
        DB::table('post_categories')->truncate();
        Schema::enableForeignKeyConstraints();

        $faker = Factory::create();
        $categories = factory(PostCategory::class, 7)->create();
        $posts = collect([]);
        $comments = collect([]);

        foreach ($categories as $category) {
            foreach (range(1, random_int(5, 10)) as $key) {
                $title = $faker->sentence(random_int(5, 10));
                $postArray = [
                    'id' => Str::uuid()->toString(),
                    'user_id' => User::inRandomOrder()->first()->id,
                    'category_slug' => $category->slug,
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'content' => $faker->sentences(random_int(3, 10), true),
                    'is_published' => $faker->boolean(80),
                    'is_featured' => $faker->boolean(10),
                    'updated_at' => $faker->dateTimeThisMonth()->format('Y-m-d H:i:s'),
                    'created_at' => $faker->dateTimeThisMonth()->format('Y-m-d H:i:s'),
                ];
                $posts->push($postArray);

                $commentIds = [];
                foreach (range(1, random_int(5, 15)) as $item) {
                    $id = Str::uuid()->toString();
                    $commentArray = [
                        'id' => $id,
                        'user_id' => $faker->boolean(25) ? $postArray['user_id'] : User::inRandomOrder()->first()->id,
                        'post_id' => $postArray['id'],
                        'post_comment_id' => $faker->boolean(40) ? $faker->randomElement($commentIds) : null,
                        'content' => $faker->sentences(random_int(1,2), true),
                        'created_at' => $faker->dateTimeThisMonth()->format('Y-m-d H:i:s'),
                        'updated_at' => $faker->dateTimeThisMonth()->format('Y-m-d H:i:s')
                    ];
                    $comments->push($commentArray);
                    $commentIds[] = $id;
                }
            }
        }

        Post::insert($posts->toArray());
        PostComment::insert($comments->toArray());
    }
}
