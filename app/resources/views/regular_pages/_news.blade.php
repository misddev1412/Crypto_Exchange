<section class="tm-news section-padding" id="tm-latest-news">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                @component('regular_pages.components.section_title', ['align' => 'center'])
                    <span class='text-danger'>L</span>atest {{ __("News") }}
                    @slot('subtite')
                        {{ __('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet blanditiis doloremque dolores doloribus esse iusto laborum mollitia porro rem sapiente') }}
                    @endslot
                @endcomponent
            </div>
        </div>

        <div class="row no-gutters" v-if="totalNews > 0">
            <div class="col-md-4 tm-news-img-area text-center">
                <img v-bind:src="activeNews.avatar"
                     v-bind:alt="activeNews.author" class="img-fluid">
                <div class="p-3 text-right d-none d-md-block">
                    <p><a class="text-info" href="{{ route('blog.index') }}">{{ __('View All News') }}</a></p>
                </div>
            </div>
            <div class="col-md-8">
                <div class="text-right d-none d-sm-none d-md-flex">
                    <div class="name my-auto ml-auto">
                        <h3 class="font-size-22 m-0">@{{ activeNews.author }}</h3>
                        <p class="font-size-18 m-0">@{{ activeNews.authorRole }}</p>
                    </div>
                    <div class="date bg-danger my-auto ml-3 text-center p-3">
                        <p class="font-size-30 font-weight-bold m-0 line-height-medium text-white">@{{ activeNews.date }}</p>
                        <p class="font-size-24 font-weight-bold m-0 line-height-standard text-white">@{{ activeNews.month }}</p>
                    </div>
                </div>
                <div class="bg-secondary pt-4 pb-4 pr-4 mt-md-5 tm-news-content">
                    <h3 class="font-size-30 line-height-medium title">
                        <a v-bind:href="activeNews.url">
                            @{{ activeNews.title }}
                        </a>
                    </h3>
                    <p>
                      @{{ activeNews.message }}
                    </p>
                    <p class="font-weight-bold">
                        <a v-bind:href="activeNews.url" class="tm-news-read-more-btn text-danger d-flex">
                            {{ __('Learn More') }}
                        </a>
                    </p>

                    <div class="tm-btn-group text-right" v-if="totalNews > 1">
                        <p class="d-sm-block d-md-none"><a href="{{ route('blog.index') }}">{{ __('View All News') }}</a></p>
                        <button class="btn btn-sm btn-danger font-size-18" v-on:click="prev()">
                            <i class="fa fa-long-arrow-left"></i>
                        </button>
                        <button class="btn btn-sm btn-danger font-size-18" v-on:click="next()">
                            <i class="fa fa-long-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
