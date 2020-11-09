<div class="post-comment my-5">
    <h3 class="border-bottom lf-toggle-border-color pb-2 mb-4">{{ __('Comment') }}</h3>
    @if(has_permission('posts.comment'))
        <div class="comment-form border-bottom lf-toggle-border-color pb-4 mb-4">
            {{ Form::open(['route'=>['posts.comment', $post->id], 'method' => 'post', 'class'=>'form-horizontal comment-form', 'id' => 'commentForm']) }}
            <div class="form-group">
                {{ Form::textarea('content', null, ['class' => 'form-control', 'placeholder' => __('Write a comment')]) }}
                <span class="invalid-feedback" data-name="content">{{ $errors->first('content') }}</span>
            </div>
            {{--submit buttn--}}
            <div class="form-group">
                {{ Form::submit(__('Comment'),['class'=>'btn lf-card-btn btn-info form-submission-button']) }}
            </div>
            {{ Form::close() }}
        </div>
    @endif

<!-- comment list -->
    @if(count($comments) > 0)
        <div class="comments">
            @foreach($comments->sortBy('created_at') as $comment)
                @if(is_null($comment->post_comment_id))
                    <div class="comment-item my-3">
                        <div class="d-flex">
                            <div class="comment-avatar">
                                <img src="{{ get_avatar($comment->user->avatar) }}"
                                     alt="{{ $comment->user->profile->full_name }}"
                                     class="img-icon">
                            </div>
                            <div class="comment-content p-3 border ml-2 lf-toggle-border-color lf-toggle-bg-card">
                                <h6>{{ $comment->user->profile->full_name }}</h6>
                                <p>
                                    {{ $comment->content }}
                                </p>
                                <div class="comment-terms clearfix small">
                                    <div class="pull-left text-muted">
                                        <i class="fa fa-clock-o"></i> {{ $comment->created_at->diffForHumans() }}
                                    </div>
                                    <div class="pull-right">
                                        <a href="#comment{{ $comment->id }}" data-url="{{ route('posts.comment.reply', [$post->id, $comment->id]) }}"
                                           class="mr-3 comment-reply-btn">{{ __('Reply') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(count($comment->commentReplies) > 0)
                    @foreach($comment->commentReplies as $reply)
                        <div class="comment-item sub-comment my-3">
                            <div class="d-flex">
                                <div class="comment-avatar">
                                    <img src="{{ get_avatar($reply->user->avatar) }}"
                                         alt="{{ $reply->user->profile->full_name }}"
                                         class="img-icon">
                                </div>
                                <div class="comment-content p-3 border ml-2 border ml-2 lf-toggle-border-color lf-toggle-bg-card">
                                    <h6>{{ $reply->user->profile->full_name }}</h6>
                                    <p>
                                        {{ $reply->content }}
                                    </p>
                                    <div class="comment-terms small">
                                        <div class="text-muted">
                                            <i class="fa fa-clock-o"></i> {{ $comment->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            @endforeach

            <div class="my-5 float-right">
                {{ $comments->links('vendor.pagination.blog') }}
            </div>
        </div>
    @endif
</div>
