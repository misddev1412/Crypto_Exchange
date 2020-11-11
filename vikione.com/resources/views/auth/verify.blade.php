@extends('layouts.auth')
@section('title', 'Verify Email')
@section('content')

<div class="{{ (gws('theme_auth_layout','default')=='center-dark'||gws('theme_auth_layout', 'default')=='center-light') ? 'page-ath-form' : 'page-ath-text' }}">

    @if (session('resent'))
    <div class="alert alert-success" role="alert">
        {{ __('A fresh verification link has been sent to your email address.') }}
    </div>
    @endif

    <div class="alert alert-warning text-center">{{ __('Please verify your email address.') }}</div>
    @include('layouts.messages')
    <div class="gaps-0-5x"></div>
    {{ __('Before proceeding, please check your email for a verification link.') }}
    {{ __('If you did not receive the email, click the button to resend.') }} 
    <div class="gaps-3x"></div>
    <a class="btn btn-primary" href="{{ route('verify.resend') }}">{{ __('Resend Email') }}</a>
    <div class="gaps-1-5x"></div>
    <a class="link link-ucap link-light" href="{{ route('log-out') }}">{{ __('Sign Out') }}</a>
    
</div>
@endsection