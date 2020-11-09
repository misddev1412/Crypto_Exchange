@extends('layouts.master',['headerLess'=>true, 'activeSideNav' => active_side_nav()])

@section('title', __('Unauthorized'))

@section('content')
    @component('components.auth')
        <div class="mx-lg-4">
            <h2 class="text-center text-danger font-size-48">401</h2>
            <h4 class="text-center text-warning mb-4">{{ (isset($exception) && $exception->getMessage()) ? $exception->getMessage() : __('Unauthorized!')  }}</h4>
            <p class="text-center pb-3">{{ __('You are not authorized to access this page.') }}</p>
            @guest
                <a href="{{ route('home') }}" class="btn btn-success btn-block">{{ __('Go Home') }}</a>
            @endguest
            @auth
                <a href="{{ route('profile.index') }}" class="btn btn-success btn-block">{{ __('Go Profile') }}</a>
            @endauth
        </div>
    @endcomponent
@endsection
