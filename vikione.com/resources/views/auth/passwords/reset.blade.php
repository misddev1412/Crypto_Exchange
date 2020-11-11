@extends('layouts.auth')
@section('title', __('Change Password'))
@section('content')

<div class="page-ath-form">
    <h2 class="page-ath-heading">{{ __('Change Password') }}</small></h2>
    <form method="POST" action="{{ route('password.update') }}" class="reset-pass-form validate validate-modern">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        @include('layouts.messages')
        <div class="input-item">
            <input type="email" placeholder="{{ __('Your Email') }}"  name="email" value="{{ '' }}" class="input-bordered" required>
        </div>
        <div class="input-item">
            <input type="password" placeholder="{{ __('New Password') }}" name="password" id="password" class="input-bordered" minlength="6" required>
        </div>
        <div class="input-item">
            <input type="password" placeholder="{{ __('Again Password') }}" name="password_confirmation" class="input-bordered" minlength="6" data-rule-equalTo="#password" required>
        </div>

        <div class="gaps"></div>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <button type="submit" class="btn btn-primary">{{ __('Reset Password') }}</button>
            </div>
            <div>
                <a href="{{ route('login') }}">{{ __('Return to login') }}</a>
            </div>
        </div>

    </form>
</div>
@endsection
