<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostCategoryRequest;
use App\Models\Post\PostCategory;
use App\Services\Core\DataTableService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PostCategoryController extends Controller
{
    public function index(): View
    {
        $searchFields = [
            ['name', __('Name')],
        ];

        $orderFields = [
            ['name', __('Name')],
            ['is_active', __('Status')],
        ];
        $data['title'] = __('Post Categories');

        $queryBuilder = PostCategory::orderBy('created_at', 'desc');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->create($queryBuilder);

        return view('posts.admin.categories.index', $data);
    }

    public function create(): View
    {
        $data['title'] = __('Post Category Create');

        return view('posts.admin.categories.create', $data);
    }

    public function store(PostCategoryRequest $request): RedirectResponse
    {
        $attributes = $request->only('name', 'is_active');

        try {
            PostCategory::create($attributes);
        } catch (Exception $exception) {
            $message = __('Failed to create new post category!');

            if ($exception->getCode() == 23000) {
                $message = __('Failed to create post category for duplicate entry!');
            }

            return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, $message);
        }
        return redirect()->route('post-categories.index')->with(RESPONSE_TYPE_SUCCESS, __('Post category successfully created!'));
    }

    public function edit(PostCategory $postCategory): View
    {
        $data['title'] = __('Post Edit');
        $data['postCategory'] = $postCategory;

        return view('posts.admin.categories.edit', $data);
    }

    public function update(PostCategoryRequest $request, PostCategory $postCategory): RedirectResponse
    {
        $attributes = $request->only('name', 'is_active');
        try {
            $postCategory->update($attributes);
        } catch (Exception $exception) {
            $message = __('Failed to update post category!');

            if ($exception->getCode() == 23000) {
                $message = __('Failed to update post category for duplicate entry!');
            }

            return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, $message);
        }
        return redirect()->route('post-categories.index')->with(RESPONSE_TYPE_SUCCESS, __('Post category successfully updated!'));
    }
}
