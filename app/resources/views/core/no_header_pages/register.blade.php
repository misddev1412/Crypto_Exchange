@extends('layouts.master',['headerLess'=>true, 'activeSideNav' => active_side_nav()])
@section('title', __('Register'))
@section('content')
    @component('components.auth', ['pageTitle' => __('Create Your Account')])
        <form id="registerForm" action="{{ route('register.store') }}" method="post">
            @csrf
            <div class="form-row">
                <div class="col-md-6">
                    <div class="input-group mb-md-3 mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text lf-w-40px"><i class="fa fa-user"></i></div>
                        </div>
                        <input type="text"
                               class="{{ form_validation($errors, 'first_name') }}"
                               name="first_name"
                               value="{{ old('first_name') }}"
                               placeholder="First Name">
                        <span class="invalid-feedback" data-name="first_name">{{ $errors->first('first_name') }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group mb-md-3 mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text lf-w-40px"><i class="fa fa-user"></i></div>
                        </div>
                        <input type="text"
                               class="{{ form_validation($errors, 'last_name') }}"
                               name="last_name"
                               value="{{ old('last_name') }}"
                               placeholder="Last Name">
                        <span class="invalid-feedback" data-name="last_name">{{ $errors->first('last_name') }}</span>
                    </div>
                </div>
            </div>

            <div class="input-group mb-md-3 mb-2">
                <div class="input-group-prepend">
                    <div class="input-group-text lf-w-40px"><i class="fa fa-user"></i></div>
                </div>
                <input type="text"
                       class="{{ form_validation($errors, 'username') }}"
                       name="username"
                       value="{{ old('username') }}"
                       placeholder="Username">
                <span class="invalid-feedback" data-name="username">{{ $errors->first('username') }}</span>
            </div>

            <div class="input-group mb-md-3 mb-2">
                <div class="input-group-prepend">
                    <div class="input-group-text lf-w-40px"><i class="fa fa-envelope"></i></div>
                </div>
                <input type="text"
                       class="{{ form_validation($errors, 'email') }}"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="Email">
                <span class="invalid-feedback" data-name="email">{{ $errors->first('email') }}</span>
            </div>

            <div class="input-group mb-md-3 mb-2">
                <div class="input-group-prepend">
                    <div class="input-group-text lf-w-40px"><i class="fa fa-lock"></i></div>
                </div>
                <input type="password"
                       class="{{ form_validation($errors, 'password') }}"
                       name="password"
                       placeholder="Password">
                <span class="invalid-feedback" data-name="password">{{ $errors->first('password') }}</span>
            </div>

            <div class="input-group mb-md-3 mb-2">
                <div class="input-group-prepend">
                    <div class="input-group-text lf-w-40px"><i class="fa fa-lock"></i></div>
                </div>
                <input type="password"
                       class="{{ form_validation($errors, 'password_confirmation') }}"
                       name="password_confirmation"
                       placeholder="Confirm Password">
                <span class="invalid-feedback"
                      data-name="password_confirmation">{{ $errors->first('password_confirmation') }}</span>
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
                    <input id="rememberMe"
                           type="checkbox"
                           name="check_agreement">
                    <label for="rememberMe"
                           class="lf-toggle-text-color"> {{  __('Accept our terms and conditions.') }}</label>
                </div>
                <span class="invalid-feedback"
                      data-name="check_agreement">{{ $errors->first('check_agreement') }}</span>
            </div>

            <div class="form-group">
                <button type="submit"
                        class="btn btn-block btn-info font-size-14 font-weight-bold form-submission-button">{{ __('Register') }}</button>
            </div>
            @if(request()->has('ref') && settings('referral'))
                <input type="hidden" name="referral_id" value="{{ request()->get('ref') }}">
                <span class="invalid-feedback" data-name="referral_id">{{ $errors->first('referral_id') }}</span>
            @endif
        </form>
        <div class="text-center pt-2">
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
    @if( env('APP_ENV') == 'production' && settings('display_google_captcha') )
        {{ view_html(NoCaptcha::renderJs()) }}
    @endif

    <script src="{{ asset('plugins/cvalidator/cvalidator-language-en.js') }}"></script>
    <script src="{{ asset('plugins/cvalidator/cvalidator.js') }}"></script>
    <script>
        "use strict";

        $(document).ready(function () {
            $('#registerForm').cValidate({
                rules: {
                    'first_name': 'required|alphaSpace|between:2,255',
                    'last_name': 'required|alphaSpace|between:2,255',
                    'username': 'required|max:255',
                    'email': 'required|email|between:5,255',
                    'password': 'required|between:6,32',
                    'password_confirmation': 'required|same:password',
                    'check_agreement': 'required',
                }
            });
        });

    </script>
@endsection
