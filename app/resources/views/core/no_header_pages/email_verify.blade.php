@extends('layouts.master',['headerLess'=>true, 'activeSideNav' => active_side_nav()])
@section('title', __('Verification'))
@section('content')
    @component('components.auth', ['pageTitle' => __('Get Email Verification Link')])
        <form id="email-verification-form" action="{{ route('verification.send') }}" method="post">
            @csrf
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fa fa-envelope"></i></div>
                </div>
                <input type="email" class="{{ form_validation($errors, 'email') }}"
                       name="email" placeholder="Email" value="{{ old('email') }}">
                <span class="invalid-feedback" data-name="email">{{ $errors->first('email') }}</span>
            </div>

            @if( env('APP_ENV') != 'local' && settings('display_google_captcha') )
                <div class="input-group mb-3">
                    <div>
                        {{ view_html(NoCaptcha::display()) }}
                    </div>
                    <span class="invalid-feedback">{{ $errors->first('g-recaptcha-response') }}</span>
                </div>
            @endif

            <div class="form-group">
                <button type="submit" class="btn btn-block btn-info font-size-14 font-weight-bold form-submission-button">{{ __('Send') }}</button>
            </div>
        </form>
        <div class="text-center pt-3">
            <a href="{{ route('login') }}">{{ __('Login') }}</a>
        </div>
        @if(settings('require_email_verification'))
            <div class="text-center pt-1">
                <a href="{{ route('forget-password.index') }}">{{ __('Forgot Password?') }}</a>
            </div>
        @endif
    @endcomponent
@endsection

@section('script')
    {{--@if( env('APP_ENV') == 'production' && settings('display_google_captcha') )--}}
    {{ view_html(NoCaptcha::renderJs()) }}
    {{--@endif--}}

    <script>
        "use strict";

        $(document).ready(function () {
            $('#email-verification-form').cValidate({
                rules: {
                    'email': 'required|email|max:255',
                }
            });
        });
    </script>
@endsection
