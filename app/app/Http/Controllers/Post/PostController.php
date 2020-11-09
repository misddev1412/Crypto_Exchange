<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostRequest;
use App\Models\Post\Post;
use App\Services\Core\DataTableService;
use App\Services\Post\PostCategoryService;
use App\Services\Post\PostService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    protected $service;

    public function __construct(PostService $service)
    {
        $this->service = $service;
    }

    public function index(): View
    {
        $searchFields = [
            ['title', __('Title')],
        ];

        $orderFields = [
            ['title', __('Title')],
            ['is_published', __('Status')],
        ];
        $data['title'] = __('Posts');

        $queryBuilder = Post::with('postCategory')
            ->orderBy('created_at', 'desc');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->create($queryBuilder);

        return view('posts.admin.posts.index', $data);
    }

    public function create(): View
    {
        $data['title'] = __('Post Create');
        $data['postCategories'] = app(PostCategoryService::class)->activeCategories();

        return view('posts.admin.posts.create', $data);
    }

    public function store(PostRequest $request): RedirectResponse
    {
        $attributes = $this->_attributes($request);
        $attributes['user_id'] = Auth::id();

        try {
            Post::create($attributes);
        } catch (Exception $exception) {
            if ($exception->getCode() == 23000) {
                return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, __('Failed to create post for duplicate entry!'));
            }

            return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, __('Failed to create new post.'));
        }

        return redirect()->route('posts.index')->with(RESPONSE_TYPE_SUCCESS, __('The post has been created successfully.'));
    }


    public function show(Post $post): View
    {
        $data['title'] = __('Post Show');
        $data['post'] = $post;
        return view('posts.admin.posts.show', $data);
    }

    public function edit(Post $post): View
    {
        $data['title'] = __('Post Edit');
        $data['postCategories'] = app(PostCategoryService::class)->activeCategories();
        $data['post'] = $post;
        return view('posts.admin.posts.edit', $data);
    }

    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        $attributes = $this->_attributes($request);

        try {
            $post->update($attributes);
        } catch (Exception $exception) {
            if ($exception->getCode() == 23000) {
                return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, __('Failed to update post for duplicate entry!'));
            }
            return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, __('Failed to update post.'));
        }
        return redirect()->route('posts.edit', $post->id)->with(RESPONSE_TYPE_SUCCESS, __('The post has been updated Successfully.'));
    }

    public function destroy(Post $post): RedirectResponse
    {
        if ($post->delete()) {
            return redirect()->back()->with(RESPONSE_TYPE_SUCCESS, __("The post has been deleted successfully."));
        }
        return redirect()->back()->with(RESPONSE_TYPE_ERROR, __("Failed to delete the post."));
    }

    public function _attributes($request): array
    {
        $attributes = $request->only('title', 'category_slug', 'is_published', 'is_featured');
        $attributes['content'] = $request->get('editor_content');

        if ($featured_image = $this->service->_uploadThumbnail($request)) {
            $attributes['featured_image'] = $featured_image;
        }

        return $attributes;
    }
}
