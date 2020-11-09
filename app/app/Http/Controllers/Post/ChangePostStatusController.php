<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ChangePostStatusController extends Controller
{
    public function change(Post $post): RedirectResponse
    {
        if ($post->toggleStatus('is_published')) {
            return redirect()->back()->with(RESPONSE_TYPE_SUCCESS, __('Successfully post status changed.'));
        }
        return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to change status. Please try again.'));
    }
}
