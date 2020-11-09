@extends('layouts.master',['headerLess'=>true, 'activeSideNav' => active_side_nav()])
@section('title', __('2Fa Verification'))
@section('content')

    @component('components.auth', ['pageTitle' => __('Google 2 Factor Authentication')])
        <form id="google2fa" action="{{ route('profile.google-2fa.verify') }}" method="post">
            {{ csrf_field() }}
            <div class="form-group has-feedback {{ $errors->has('google_app_code') ? 'has-error' : '' }}">
                <div>
                    {{ Form::text('google_app_code', null, ['class'=>'form-control text-center', 'placeholder' => __('Enter G2FA app code'), 'data-cval-name' => 'One time password','data-cval-rules' => 'required|integer']) }}
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <span class="invalid-feedback">{{ $errors->first('google_app_code') }}</span>
            </div>
            <div class="row">
                <!-- /.col -->
                <div class="col-md-12">
                    {{ Form::submit(__('Verify Google 2FA Code'), ['class'=>'btn btn-info btn-flat btn-block form-submission-button']) }}
                </div>
                <!-- /.col -->
            </div>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('logout') }}">{{ __('Logout From Account') }}</a>
        </div>
    @endcomponent
@endsection

@section('after-style')
    @include('layouts.includes._avatar_and_loader_style')
    <style>
        .cm-center {
            float: none;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
@endsection

@section('script')
    <script src="{{ asset('plugins/cvalidator/cvalidator-language-en.js') }}"></script>
    <script src="{{ asset('plugins/cvalidator/cvalidator.js') }}"></script>
    <script>
        "use strict";

        $(document).ready(function () {
            $('#google2fa').cValidate({
                rules: {
                    'google_app_code': 'required|integer',
                },
                messages: {}
            });
        });
    </script>
@endsection
