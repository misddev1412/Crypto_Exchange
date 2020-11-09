@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="offset-md-2 col-md-8">
                <div class="card lf-toggle-bg-card lf-toggle-border-color">
                    <div class="card-body">
                        <div class="lf-post-thumbnail border lf-toggle-border-color border-bottom-0">
                            <img src="{{ get_featured_image($post->featured_image) }}" alt="{{ $post->title }}" class="img-fluid">
                        </div>
                        <div class="lf-post-terms bg-info p-3 mb-4">
                            <span class="mr-3">
                                {{ __('Category') }} : {{ $post->postCategory->name }}
                            </span>
                            <span class="mr-3">
                                <i class="fa fa-comment-o"></i> {{ __(':count comments', ['count' => count($post->comments)]) }}
                            </span>
                            <span>
                                <i class="fa fa-calendar-o"></i> 23 jun 2019
                            </span>
                        </div>
                        <div class="lf-post-content mb-5">
                            <h2 class="mb-4 lf-post-title">{{ $post->title }}</h2>
                            <div class="lf-post-description lf-post-content">
                                {{ view_html($post->content) }}
                            </div>
                        </div>
                        <div class="my-4">
                            @if(has_permission('posts.edit'))
                                <a href="{{ route('posts.edit', $post) }}" class="btn lf-card-btn btn-info">{{ __('Edit Post') }}</a>
                            @endif
                            @if(has_permission('posts.index'))
                                <a href="{{ route('posts.index') }}" class="btn btn-danger lf-card-btn">{{ __('Post Lists') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
    <style>
        .lf-post-title {
            font-size: 30px;
            font-weight: 400;
            line-height: 1.4;
        }
    </style>
@endsection
@section('script')
    @include('posts.admin.posts._script')
@endsection
