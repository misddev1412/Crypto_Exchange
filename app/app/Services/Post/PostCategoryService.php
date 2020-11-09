<?php


namespace App\Services\Post;


use App\Models\Post\PostCategory;

class PostCategoryService
{
    public function activeCategories(){
        return PostCategory::where('is_active', ACTIVE)->get()->pluck('name', 'slug')->toArray();
    }
}
