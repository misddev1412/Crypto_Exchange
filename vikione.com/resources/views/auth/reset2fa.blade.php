@extends('layouts.auth')
@section('title', __('Reset 2FA Authentication'))
@section('content')
<div class="page-ath-form">
    <h2 class="page-ath-heading pb-0">{{ __('Reset 2FA') }}</h2>
    <p>{{ __('Hello') }} <strong>{{ $user->name }}</strong>, <br>{{ __('Please enter your account password to reset 2FA authentication.') }}</p>
    <div class="gaps-1x"></div>
    <form method="POST" action="{{ route('auth.2fa.reset') }}" class="reset-2fa-form validate-modern">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}"> 
        @include('layouts.messages')
        <div class="input-item">
            <input type="password" placeholder="{{ __('Enter Password') }}" name="password" id="password" class="input-bordered" minlength="6" data-msg-required="{{ __('Required.') }}" data-msg-minlength="{{ __('At least :num chars.', ['num' => 6]) }}" required>
        </div>

        <div class="gaps"></div>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <button type="submit" class="btn btn-primary">{{ __('Reset 2FA') }}</button>
            </div>
        </div>

    </form>
</div>
@endsection
