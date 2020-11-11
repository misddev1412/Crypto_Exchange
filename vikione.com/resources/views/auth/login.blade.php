@extends('layouts.auth')
@section('title', __('Sign-in'))
@section('content')
@if( recaptcha() )
@push('header')
<script>
    grecaptcha.ready(function () { grecaptcha.execute('{{ recaptcha('site') }}', { action: 'login' }).then(function (token) { if(token) { document.getElementById('recaptcha').value = token; } }); });
</script>
@endpush
@endif
<div class="page-ath-form">
    <h2 class="page-ath-heading text-center">{{ __('Sign in') }}<small>{{ __('with your') }} {{ site_info('name') }}
            {{ __('Account') }}</small></h2>
    <form class="login-form validate validate-modern"
        action="{{ (is_maintenance() ? route('admin.login') : route('login')) }}" method="POST">
        @csrf
        @include('layouts.messages')
        <div class="input-item">
            <input type="email" placeholder="{{ __('Your Email') }}" data-msg-required="{{ __('Required.') }}"
                class="input-bordered{{ $errors->has('email') ? ' input-error' : '' }}" name="email"
                value="{{ old('email') }}" required autofocus>
        </div>
        <div class="input-item">
            <input type="password" placeholder="{{ __('Password') }}" minlength="6"
                data-msg-required="{{ __('Required.') }}"
                data-msg-minlength="{{ __('At least :num chars.', ['num' => 6]) }}"
                class="input-bordered{{ $errors->has('password') ? ' input-error' : '' }}" name="password" required>
        </div>
        @if(! is_maintenance())
        <div class="d-flex justify-content-between align-items-center">
            <div class="input-item text-left">
                <input class="input-checkbox input-checkbox-md" type="checkbox" name="remember" id="remember-me"
                    {{ old('remember') ? 'checked' : '' }}>
                <label for="remember-me">{{ __('Remember Me') }}</label>
            </div>
            <div>
                <a href="{{ route('password.request') }}">{{ __('Forgot password?')}}</a>
                <div class="gaps-2x"></div>
            </div>
        </div>
        @endif
        @if( recaptcha() )
        <input type="hidden" name="recaptcha" id="recaptcha">
        @endif
        <button type="submit" class="btn btn-primary btn-block">{{__('Sign In')}}</button>
    </form>
    @if(! is_maintenance())
    @if(Schema::hasTable('settings'))
    @if (
    (get_setting('site_api_fb_id', env('FB_CLIENT_ID', '')) != '' && get_setting('site_api_fb_secret',
    env('FB_CLIENT_SECRET', '')) != '') ||
    (get_setting('site_api_google_id', env('GOOGLE_CLIENT_ID', '')) != '' && get_setting('site_api_google_secret',
    env('GOOGLE_CLIENT_SECRET', '')) != '')
    )
    <div class="sap-text"><span>{{__('Or Sign in with')}}</span></div>
    <ul class="row guttar-20px guttar-vr-20px">
        @if(get_setting('site_api_fb_id', env('FB_CLIENT_ID', '')) != '' && get_setting('site_api_fb_secret',
        env('FB_CLIENT_SECRET', '')) != '')
        <li class="col"><a href="{{ route('social.login', 'facebook') }}"
                class="btn btn-outline btn-dark btn-facebook btn-block"><em
                    class="fab fa-facebook-f"></em><span>{{__('Facebook')}}</span></a></li>
        @endif
        @if(get_setting('site_api_google_id', env('GOOGLE_CLIENT_ID', '')) != '' &&
        get_setting('site_api_google_secret', env('GOOGLE_CLIENT_SECRET', '')) != '')
        <li class="col"><a href="{{ route('social.login', 'google') }}"
                class="btn btn-outline btn-dark btn-google btn-block"><em
                    class="fab fa-google"></em><span>{{__('Google')}}</span></a></li>
        @endif
    </ul>
    @endif
    @endif

    <div class="gaps-4x"></div>
    <div class="form-note">
        {{__('Don’t have an account?')}} <a href="{{ route('register') }}"> <strong>{{__('Sign up here')}}</strong></a>
    </div>
    @endif
</div>

@endsection