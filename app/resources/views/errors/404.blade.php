@extends('layouts.master',['headerLess'=>true, 'activeSideNav' => active_side_nav()])

@section('title', __('404 Not Found'))

@section('content')
    @component('components.auth')
        <div class="mx-lg-4">
            <h2 class="text-center text-danger font-size-48">404</h2>
            <h4 class="text-center text-danger">{{ (isset($exception) && $exception->getMessage()) ? $exception->getMessage() : __('Not Found!')  }}</h4>
            <p class="text-center pb-3">{{ __('The page you are looking for might be changed, removed or not exists. Go back and try other links') }}</p>
            <a href="{{ route('home') }}"
               class="btn btn-success btn-block">{{ __('Go Home') }}</a>
        </div>
    @endcomponent
@endsection
