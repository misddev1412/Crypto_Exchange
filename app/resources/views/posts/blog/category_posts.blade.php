@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="{{ (count($activeCategories) <= 0 && count($recentPosts) <= 0)? 'col-lg-12': 'col-lg-8' }}">
                @include('posts.blog._posts')
            </div>
            @if(count($activeCategories) > 0 || count($recentPosts) > 0)
            <div class="col-lg-4">
                <!-- blog sidebar -->
                @include('posts.blog.sidebar', ['recentPosts' => $recentPosts, 'activeCategories' => $activeCategories])
            </div>
            @endif
        </div>
    </div>
@endsection

@section('style')
    @include('posts.blog._style')
@endsection
