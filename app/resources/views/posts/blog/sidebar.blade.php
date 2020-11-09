<aside>
    <!-- latest Post -->
    @if(count($recentPosts) > 0)
        <div class="card lf-toggle-border-color lf-toggle-bg-card mb-3">
            <div class="card-header py-3">
                <h4 class="card-title">{{ __('Latest Posts') }}</h4>
            </div>

            <div class="card-body">
            @foreach($recentPosts as $recentPost)
                <!-- post -->
                    <div class="post-item-list lf-toggle-border-color">
                        <div class="row no-gutters">
                            <div class="col-3 pr-2">
                                <a href="{{ route('blog.show', ['slug' => $recentPost->slug]) }}">
                                    <img src="{{ get_featured_image($recentPost->featured_image) }}"
                                         alt="{{ $recentPost->title }}"
                                         class="img-fluid">
                                </a>
                            </div>
                            <div class="col-9">
                                <h5 class="m-0">
                                    <a href="{{ route('blog.show', ['slug' => $recentPost->slug]) }}">
                                        {{ Str::limit($recentPost->title, 20) }}
                                    </a>
                                </h5>
                                <div class="mt-1 text-muted">
                                    {{ $recentPost->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

<!-- Categories -->
    @if(count($activeCategories) > 0)
        <div class="card lf-toggle-border-color lf-toggle-bg-card mb-3">
            <div class="card-header py-3">
                <h4 class="card-title">{{ __('Top Categories') }}</h4>
            </div>

            <div class="card-body py-3">
                <ul class="m-0 p-0">
                    @foreach($activeCategories as $category)
                        <li class="lf-toggle-border-color pb-2">
                            <a href="{{ route('blog.category', ['postCategory' => $category->slug]) }}">
                                <i class="fa fa-check mr-2"></i> {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
</aside>
