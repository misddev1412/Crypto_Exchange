@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="offset-md-1 col-md-10">
                @component('components.form_box')
                    @slot('title', __('Create Post'))
                    @slot('indexUrl', route('posts.index'))
                    {{ Form::open(['route'=>'posts.store', 'id' => 'postForm', 'files' => true]) }}
                    @include('posts.admin.posts._form')
                    {{ Form::close() }}
                @endcomponent
            </div>
        </div>
    </div>
@endsection

@section('style')
    @include('posts.admin.posts._style')
@endsection
@section('script')
    @include('posts.admin.posts._script')
@endsection
