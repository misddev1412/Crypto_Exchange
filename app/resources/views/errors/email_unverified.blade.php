@extends('layouts.master',['headerLess'=>true, 'activeSideNav' => active_side_nav()])
@section('title', __('Unverified Account'))
@section('content')
    @component('components.auth')
        <div class="mx-lg-4">
            <h2 class="text-center text-danger font-size-48">{{ __('Email Unverified!')  }}</h2>
            <p class="text-center pb-3">{{ __('Please verify your email address to explore permitted access paths in full.') }}</p>
            @guest
                <a href="{{ route('home') }}" class="btn btn-success btn-block">{{ __('Go Home') }}</a>
            @endguest
            @auth
                <a href="{{ route('profile.index') }}" class="btn btn-success btn-block">{{ __('Go Profile') }}</a>
            @endauth

            <a href="{{route('verification.form')}}"
               class="btn btn-success btn-block">{{ __('Resend verification link') }}</a>
        </div>
    @endcomponent
@endsection
