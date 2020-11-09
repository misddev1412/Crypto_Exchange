@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="offset-3 col-md-6">
                @component('components.form_box')
                    @slot('title', __('Edit Post Category'))
                    @slot('indexUrl', route('post-categories.index'))
                    {{ Form::model($postCategory, ['route'=>['post-categories.update', $postCategory],
                        'method' => 'put', 'class'=>'form-horizontal', 'id' => 'categoryForm']) }}
                    @include('posts.admin.categories._form')
                    {{ Form::close() }}
                @endcomponent
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('posts.admin.categories._script')
@endsection
