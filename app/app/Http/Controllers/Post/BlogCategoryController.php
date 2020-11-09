<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post\Post;
use App\Models\Post\PostCategory;
use App\Services\Blog\GetPostCategoryService;
use App\Services\Blog\GetRecentPostService;
use App\Services\Core\DataTableService;
use Illuminate\View\View;

class BlogCategoryController extends Controller
{
    public function index(PostCategory $postCategory): View
    {
        $data['title'] = __('Blog');
        $data['posts'] = Post::where('is_published', ACTIVE)
            ->where('category_slug', $postCategory->slug)
            ->with('postCategory', 'comments')
            ->orderBy('id', 'desc')
            ->paginate(PAGINATION_ITEM_PER_PAGE);

        $data['recentPosts'] = app(GetRecentPostService::class)->getLastFiveActivePost();
        $data['activeCategories'] = app(GetPostCategoryService::class)->getActiveCategories();

        return view('posts.blog.category_posts', $data);
    }
}
