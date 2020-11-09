<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post\Post;
use App\Models\Post\PostCategory;
use App\Models\Post\PostComment;
use App\Services\Blog\GetPostCategoryService;
use App\Services\Blog\GetRecentPostService;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $data['title'] = __('Blog');
        $data['posts'] = Post::where('is_published', ACTIVE)
            ->with('postCategory')
            ->withCount('comments')
            ->orderBy('created_at', 'desc')
            ->simplePaginate(12);

        $data['featuredPosts'] = Post::where('is_featured', ACTIVE)
            ->where('is_published', ACTIVE)
            ->with('postCategory')
            ->withCount('comments')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $this->__getSidebarData($data);


        return view('posts.blog.index', $data);
    }

    public function show($slug): View
    {
        $data['post'] = Post::where([
            'slug' => $slug,
            'is_published' => ACTIVE
        ])->withCount('comments')->with('postCategory')->firstOrFail();

        $data['title'] = $data['post']->title;

        $data['comments'] = PostComment::query()
            ->select(['id', 'post_comment_id', 'user_id', 'content', 'created_at'])
            ->where('post_id', $data['post']->id)
            ->whereNull('post_comment_id')
            ->orderByDesc('created_at')
            ->with('user.profile', 'commentReplies.user.profile')
            ->simplePaginate(5);

        $this->__getSidebarData($data);

        return view('posts.blog.show', $data);
    }

    protected function __getSidebarData(&$data)
    {
        $data['recentPosts'] = Post::select(['id', 'title', 'slug', 'created_at'])
            ->where('is_published', ACTIVE)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $data['activeCategories'] = PostCategory::select(['name', 'slug'])
            ->where('is_active', ACTIVE)
            ->orderBy('name', 'asc')
            ->get();
    }
}
