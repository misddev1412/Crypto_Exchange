@extends('layouts.master',['headerLess'=>true, 'activeSideNav' => active_side_nav()])
@section('title', __('Reset Password'))
@section('content')
    @component('components.auth', ['pageTitle' => __('Reset Password')])
        <form id="reset-password-form" action="{{ $passwordResetLink }}" method="post" id="resetPasswordForm">
            @csrf
            <div class="input-group mb-md-3 mb-2">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fa fa-lock"></i></div>
                </div>
                <input type="password" class="form-control" name="new_password" placeholder="Password">
                <span class="invalid-feedback" data-name="new_password">{{ $errors->first('new_password') }}</span>
            </div>

            <div class="input-group mb-md-3 mb-2">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fa fa-lock"></i></div>
                </div>
                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
                <span class="invalid-feedback" data-name="password_confirmation">{{ $errors->first('password_confirmation') }}</span>
            </div>

            @if( env('APP_ENV') != 'local' && settings('display_google_captcha') == ACTIVE )
                <div class="input-group mb-md-3 mb-2">
                    <div>
                        {{ view_html(NoCaptcha::display()) }}
                    </div>
                    <span class="invalid-feedback">{{ $errors->first('g-recaptcha-response') }}</span>
                </div>
            @endif

            <div class="form-group">
                <button type="submit" class="btn btn-block btn-info font-size-14 font-weight-bold form-submission-button">{{ __('Reset') }}</button>
            </div>
        </form>
        <div class="text-center pt-2">
            <a class="txt2" href="{{ route('forget-password.index') }}">{{ __('Forgot Password?') }}</a>
        </div>
        <div class="text-center pt-1">
            <a class="txt2" href="{{ route('register.index') }}">{{ __('Create your Account') }}<i
                    class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
        </div>
    @endcomponent
@endsection

@section('script')
    <script src="{{ asset('plugins/cvalidator/cvalidator-language-en.js') }}"></script>
    <script src="{{ asset('plugins/cvalidator/cvalidator.js') }}"></script>
    <script>
        "use strict";

        $(document).ready(function () {
            $('#reset-password-form').cValidate({
                rules : {
                    'new_password' : 'required|between:6,32',
                    'password_confirmation' : 'required|same:new_password',
                }
            });
        });
    </script>
@endsection
