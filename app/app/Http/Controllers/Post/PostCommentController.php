<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\CommentRequest;
use App\Models\Post\Post;
use App\Models\Post\PostComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostCommentController extends Controller
{
    public function store(CommentRequest $request, Post $post): RedirectResponse
    {
        $attributes = $request->only('content');
        $attributes['post_id'] = $post->id;
        $attributes['user_id'] = Auth::id();

        if (PostComment::create($attributes)){
            return redirect()->back()->with(RESPONSE_TYPE_SUCCESS, __('Successfully comment added!'));
        }
        return redirect()->back()->withInput()->with(RESPONSE_TYPE_SUCCESS, __('Failed to add comment!'));
    }
}
