@extends('layouts.master',['headerLess'=>true, 'activeSideNav' => active_side_nav()])
@section('title', __('Suspended Account'))
@section('content')

    @component('components.auth')
        <div class="mx-lg-4">
            <h2 class="text-center text-danger font-size-48">{{  __('Account Suspended!')  }}</h2>
            <p class="text-center pb-3">{{ __('Please contact administrator to get back your account.') }}</p>
            @guest
                <a href="{{ route('home') }}" class="btn btn-success btn-block">{{ __('Go Home') }}</a>
            @endguest
            @auth
                <a href="{{ route('profile.index') }}" class="btn btn-success btn-block">{{ __('Go Profile') }}</a>
            @endauth
        </div>
    @endcomponent
@endsection
