<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\CommentRequest;
use App\Models\Post\Post;
use App\Models\Post\PostComment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ReplyCommentController extends Controller
{
    public function store(CommentRequest $request, Post $post, PostComment $comment): JsonResponse
    {
        $attributes = $request->only('content');
        $attributes['post_id'] = $post->id;
        $attributes['post_comment_id'] = $comment->id;
        $attributes['user_id'] = Auth::id();

        if (PostComment::create($attributes)) {
            return response()->json(['jsonResponse' => ['status' => RESPONSE_TYPE_SUCCESS, 'message' => __('Successfully reply added!')]]);
        }
        return response()->json(['jsonResponse' => [RESPONSE_TYPE_SUCCESS, __('Failed to add reply!')]]);
    }
}
