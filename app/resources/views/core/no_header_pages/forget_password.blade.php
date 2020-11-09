@extends('layouts.master',['headerLess'=>true, 'activeSideNav' => active_side_nav()])
@section('title', __('Reset Password'))
@section('content')
    @component('components.auth', ['pageTitle' => __('Get Password Reset Link')])
        <form id="forget-password-form" action="{{ route('forget-password.send-mail') }}" method="post">
            @csrf
            <div class="input-group mb-md-3 mb-2">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fa fa-user"></i></div>
                </div>
                <input type="text"
                       class="{{ form_validation($errors, 'email') }}"
                       name="email"
                       placeholder="Email">
                <span class="invalid-feedback" data-name="email">{{ $errors->first('email') }}</span>
            </div>

            @if( env('APP_ENV') != 'local' && settings('display_google_captcha') )
                <div class="input-group mb-md-3 mb-2">
                    <div>
                        {{ view_html(NoCaptcha::display()) }}
                    </div>
                    <span class="invalid-feedback">{{ $errors->first('g-recaptcha-response') }}</span>
                </div>
            @endif

            <div class="form-group">
                <button type="submit" class="btn btn-block btn-info font-size-14 font-weight-bold form-submission-button">{{ __('Get Password Reset Link') }}</button>
            </div>
        </form>
        <div class="text-center pt-2">
            <a href="{{ route('login') }}">{{ __('Login') }}</a>
        </div>
        @if(settings('require_email_verification'))
            <div class="text-center pt-1">
                <a href="{{ route('verification.form') }}">{{ __('Get verification email') }}</a>
            </div>
        @endif
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
            $('#forget-password-form').cValidate({
                rules: {
                    'email': 'required|email|max:255',
                }
            });
        });

    </script>
@endsection
