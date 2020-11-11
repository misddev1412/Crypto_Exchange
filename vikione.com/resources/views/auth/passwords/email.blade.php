@extends('layouts.auth')
@section('title', __('Reset password'))
@section('content')
@if( recaptcha() )
@push('header')
<script>
    grecaptcha.ready(function () { grecaptcha.execute('{{ recaptcha('site') }}', { action: 'forget' }).then(function (token) { if(token) { document.getElementById('recaptcha').value = token; } }); });
</script>
@endpush
@endif
<div class="page-ath-form">

    <h2 class="page-ath-heading">{{ __('Reset password') }} <span>{{ __("If you forgot your password, well, then we'll email you instructions to reset your password.") }}</span></h2>
    @include('layouts.messages')
    <form method="POST" action="{{ route('password.email') }}" class="forgot-pass-form validate validate-modern">
        @csrf
        <div class="input-item">
            <input type="email" placeholder="{{ __('Your Email Address') }}" name="email" value="{{ old('email') }}" class="input-bordered" required>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                @if( recaptcha() )
                <input type="hidden" name="recaptcha" id="recaptcha">
                @endif
                <button type="submit" class="btn btn-primary btn-block">{{ __('Send Reset Link') }}</button>
            </div>
            <div>
                <a href="{{ route('login') }}">{{ __('Return to login') }}</a>
            </div>
        </div>
        <div class="gaps-0-5x"></div>
    </form>

</div>
@endsection
