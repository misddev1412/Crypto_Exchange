<div class="container featured-post-container my-5">
    @if(count($featuredPosts) > 0)
        <div class="row no-gutters">
            @isset($featuredPosts[0])
                <div class="col-md-8 pr-md-1 mb-1">
                    <div class="lf-featured-post-1">
                        <img src="{{ get_featured_image($featuredPosts[0]->featured_image) }}"
                             alt="{{ $featuredPosts[0]->title }}"
                             class="lf-featured-post-img">
                        <a href="{{ route('blog.show', ['slug' => $featuredPosts[0]->slug]) }}"></a>
                        <div class="lf-featured-post-content px-4 py-3  text-white">
                            <a class="p-1 bg-info text-white featured-post-category"
                               href="{{ route('blog.category', ['postCategory' => $featuredPosts[0]->category_slug]) }}">{{ $featuredPosts[0]->postCategory->name }}</a>
                            <div class="lf-featured-post-title py-2">
                                <h3 class="title m-0">
                                    <a class="text-white"
                                       href="{{ route('blog.show', ['slug' => $featuredPosts[0]->slug]) }}">{{ $featuredPosts[0]->title }}</a>
                                </h3>
                            </div>
                            <div class="lf-featured-post-terms">
                            <span>
                                <i class="fa fa-calendar-o"></i> {{ $featuredPosts[0]->created_at->diffForHumans() }}
                            </span>
                                <span class="ml-3">
                                <i class="fa fa-comment-o"></i>  {{ __(':count comments', ['count' => $featuredPosts[0]->comments_count]) }}
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endisset
            <div class="col-md-4">
                @isset($featuredPosts[1])
                    <div class="lf-featured-post-2 mb-1">
                        <img src="{{ get_featured_image($featuredPosts[1]->featured_image) }}"
                             alt="{{ $featuredPosts[1]->title }}"
                             class="lf-featured-post-img">
                        <a class="text-white"
                           href="{{ route('blog.show', ['slug' => $featuredPosts[1]->slug]) }}"></a>
                        <div class="lf-featured-post-content p-3 text-white">
                            <p class="mb-1">
                                <a class="p-1 bg-info text-white small featured-post-category"
                                   href="{{ route('blog.category', ['postCategory' => $featuredPosts[1]->category_slug]) }}">{{ $featuredPosts[1]->postCategory->name }}</a>
                            </p>
                            <div class="lf-featured-post-title mb-3 mt-1">
                                <h3 class="title m-0">
                                    <a class="text-white"
                                       href="{{ route('blog.show', ['slug' => $featuredPosts[1]->slug]) }}">{{ $featuredPosts[1]->title }}</a>
                                </h3>
                            </div>
                            <div class="lf-featured-post-terms">
                            <span>
                                <i class="fa fa-calendar-o"></i> {{ $featuredPosts[1]->created_at->diffForHumans() }}
                            </span>
                                <span class="ml-3">
                                <i class="fa fa-comment-o"></i>  {{ __(':count comments', ['count' => $featuredPosts[1]->comments_count]) }}
                            </span>
                            </div>
                        </div>
                    </div>
                @endif
                @isset($featuredPosts[2])
                    <div class="lf-featured-post-2 mb-1">
                        <img src="{{ get_featured_image($featuredPosts[2]->featured_image) }}"
                             alt="{{ $featuredPosts[2]->title }}"
                             class="lf-featured-post-img">
                        <a href="{{ route('blog.show', ['slug' => $featuredPosts[2]->slug]) }}"></a>
                        <div class="lf-featured-post-content p-3 text-white">
                            <p class="mb-1">
                                <a class="p-1 bg-info text-white small featured-post-category"
                                   href="{{ route('blog.category', ['postCategory' => $featuredPosts[2]->category_slug]) }}">{{ $featuredPosts[2]->postCategory->name }}</a>
                            </p>
                            <div class="lf-featured-post-title mb-3 mt-1">
                                <h3 class="title m-0">
                                    <a class="text-white"
                                       href="{{ route('blog.show', ['slug' => $featuredPosts[2]->slug]) }}">{{ $featuredPosts[2]->title }}</a>
                                </h3>
                            </div>
                            <div class="lf-featured-post-terms">
                        <span>
                            <i class="fa fa-calendar-o"></i> {{ $featuredPosts[2]->created_at->diffForHumans() }}
                        </span>
                                <span class="ml-3">
                            <i class="fa fa-comment-o"></i>  {{ __(':count comments', ['count' => $featuredPosts[2]->comments_count]) }}
                        </span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
