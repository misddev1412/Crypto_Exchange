<div class="post-terms my-3 clearfix">
    <div class="pull-left">
        {{ __('Category') }} : <a href="{{ route('blog.category', ['postCategory' => $post->category_slug]) }}">{{ $post->postCategory->name }}</a>
    </div>
    <div class="pull-right px-3">
        <i class="fa fa-calendar-o"></i> {{ $post->created_at->diffForHumans() }}
    </div>
    <div class="pull-right">
        <i class="fa fa-comment-o"></i> {{ __(':count comments', ['count' => $post->comments_count]) }}
    </div>
</div>
