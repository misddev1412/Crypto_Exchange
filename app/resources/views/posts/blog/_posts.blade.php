@if(count($posts) > 0)
    <div class="row equal no-gutters mx-n2">
        @foreach($posts as $post)
            <div class="col-md-6 px-2 mb-3">
                <div class="card lf-toggle-bg-card lf-toggle-border-color h-100">
                    <div class="card-body p-3">
                        <div class="feature-image">
                            <a href="{{ route('blog.show', ['slug' => $post->slug]) }}">
                                <img src="{{ get_featured_image($post->featured_image) }}"
                                     alt="{{ $post->title }}"
                                     class="img-fluid">
                            </a>
                        </div>
                        <div class="blog-item-content mt-3">
                            <h4 class="blog-title">
                                <a href="{{ route('blog.show', ['slug' => $post->slug]) }}">
                                    {{ Str::limit($post->title, 50) }}
                                </a>
                            </h4>
                            <div class="blog-terms my-1 small text-muted">
                                <a href="{{ route('blog.category', ['postCategory' => $post->category_slug]) }}">{{ $post->postCategory->name }}</a>
                                -
                                <span>
                                               {{ $post->created_at->diffForHumans() }}
                                            </span>
                            </div>
                            <div class="blog-short-description">
                                <p class="my-3">
                                    {{ strip_tags(Str::limit($post->content, 70)) }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between lf-toggle-border-color p-3">
                        <a href="{{ route('blog.show', $post->slug) }}"
                           class="border-bottom border-info">
                            {{ __('Read More') }}
                        </a>
                        <div>
                            <i class="fa fa-comment-o"></i> {{ __(':count comments', ['count' => $post->comments_count]) }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="card lf-toggle-bg-card lf-toggle-border-color text-center">
        <div class="card-body">
            <h4 class="font-weight-normal">{{ __('Empty Post') }}</h4>
        </div>
    </div>
@endif

{{ $posts->links('vendor.pagination.blog') }}
