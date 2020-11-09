<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post\PostCategory;
use Illuminate\Http\RedirectResponse;

class ChangePostCategoryStatusController extends Controller
{
    public function change(PostCategory $postCategory): RedirectResponse
    {
        if ($postCategory->toggleStatus()) {
            return redirect()->back()->with(RESPONSE_TYPE_SUCCESS, __('Successfully post category status changed.'));
        }
        return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to change status. Please try again.'));
    }
}
