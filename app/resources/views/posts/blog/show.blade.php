@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('meta')
    @include('posts.blog._meta_tags')
@endsection
@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-8">
                <!-- featured image -->
                <div class="post-feature-image border border-bottom-0 lf-toggle-border-color">
                    <img src="{{ get_featured_image($post->featured_image) }}"
                         alt="{{ $post->title }}"
                         class="img-fluid">
                </div>
                <!-- post content -->
                <div class="post-content mb-1">
                    <div class="card lf-toggle-border-color lf-toggle-bg-card mb-1">
                        <div class="card-body py-2">
                            <!-- post terms -->
                            @include('posts.blog._terms')
                        </div>
                    </div>
                    <div class="card lf-toggle-border-color lf-toggle-bg-card">
                        <div class="card-body">
                            <h2 class="post-title mb-4">{{ $post->title }}</h2>
                            <div class="lf-post-content">
                                {{ view_html($post->content) }}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- share -->
                <div class="post-share">
                    @component('posts.blog._share')
                        @slot('url')
                            {{ url()->current() }}
                        @endslot
                        @slot('title')
                            {{ $post->title }}
                        @endslot
                        @slot('image')
                            {{ $post->featured_image }}
                        @endslot
                    @endcomponent
                </div>
                <!-- post comment -->
                @include('posts.blog._comments')
            </div>
            <div class="col-lg-4">
                <!-- blog sidebar -->
                @include('posts.blog.sidebar', ['recentPosts' => $recentPosts, 'activeCategories' => $activeCategories])
            </div>
        </div>
    </div>
@endsection
@section('style')
    @include('posts.blog._style')
@endsection
@section('script')
    @include('posts.blog._script')
@endsection
