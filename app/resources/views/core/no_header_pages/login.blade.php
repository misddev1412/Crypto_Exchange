@extends('layouts.master',['headerLess'=>true, 'activeSideNav' => active_side_nav()])
@section('title', __('Login'))
@section('content')
    @component('components.auth', ['pageTitle' => __('Login Your Account')])
        <form id="login-form" action="{{ route('login.post') }}" method="post">
            @csrf
            <div class="input-group mb-md-3 mb-2">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fa fa-user"></i></div>
                </div>
                <input type="text" class="form-control" name="username"
                       placeholder="Username/Email" value="{{ old('username') }}">
                <span class="invalid-feedback" data-name="username">{{ $errors->first('username') }}</span>
            </div>
            <div class="input-group mb-md-3 mb-2">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fa fa-lock"></i></div>
                </div>
                <input type="password" class="form-control" name="password"
                       placeholder="Password">
                <span class="invalid-feedback" data-name="password">{{ $errors->first('password') }}</span>
            </div>

            @if( env('APP_ENV') != 'local' && settings('display_google_captcha') )
                <div class="input-group mb-md-3 mb-2">
                    <div>
                        {{ view_html(NoCaptcha::display()) }}
                    </div>
                    <span class="invalid-feedback">{{ $errors->first('g-recaptcha-response') }}</span>
                </div>
            @endif

            <div class="checkbox mb-md-3 mb-2">
                <div class="lf-checkbox">
                    <input id="rememberMe" type="checkbox" name="remember_me">
                    <label for="rememberMe">{{ __('Remember Me') }}</label>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-info btn-block font-size-14 font-weight-bold form-submission-button">{{ __('Login') }}</button>
            </div>
        </form>

        <div class="text-center pt-2">
            <a class="txt2" href="{{ route('forget-password.index') }}">{{ __('Forgot Password?') }}</a>
        </div>
        <div class="text-center pt-1">
            <a class="txt2" href="{{ route('register.index') }}">{{ __('Create your Account') }}</a>
        </div>
    @endcomponent
@endsection

@section('script')
    @if( env('APP_ENV') == 'production' && settings('display_google_captcha') )
        {{ view_html(NoCaptcha::renderJs()) }}
    @endif

    <script src="{{ asset('plugins/cvalidator/cvalidator-language-en.js') }}"></script>
    <script src="{{ asset('plugins/cvalidator/cvalidator.js') }}"></script>
    <script>
        "use strict";

        $(document).ready(function () {
            $('#login-form').cValidate({
                rules: {
                    'username': 'required|max:255',
                    'password': 'required|between:6,32'
                }
            });
        });

    </script>
@endsection


