@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="offset-md-1 col-md-10">
                @component('components.card', [
                    'class' => 'lf-toggle-bg-card lf-toggle-border-color',
                    'headerClass' => "bg-primary text-white d-flex justify-content-between",
                    'footerClass' => "bg-primary text-white",
                ])
                    @slot('header')
                        <h4 class="card-title my-auto">
                            {{ __('Edit Page') }}
                        </h4>
                        <div class="card-link">
                            <a href="{{ route('pages.index') }}"
                               class="btn btn-info btn-sm back-button"><i class="fa fa-reply"></i></a>
                        </div>
                    @endslot

                    {{ Form::model($page, ['route'=>['pages.update', $page], 'method' => 'put', 'id' => 'pageForm']) }}
                    @include('pages.page_management._form')
                    {{ Form::close() }}
                @endcomponent
            </div>
        </div>
    </div>
@endsection

@section('style')
    @include('pages.page_management._style')
@endsection
@section('script')
    @include('pages.page_management._script')
@endsection
