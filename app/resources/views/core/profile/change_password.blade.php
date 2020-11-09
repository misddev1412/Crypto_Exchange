@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        @component('components.profile', ['user' => $user])
            {{ Form::open(['route'=>['profile.update-password'],'class'=>'form-horizontal validator','method'=>'put', 'id' => 'passwordChangeForm']) }}
            {{--password--}}
            <div class="form-group row">
                <label for="password" class="col-md-4 control-label pt-2 required">{{ __('Current Password') }}</label>
                <div class="col-md-8">
                    {{ Form::password('password', ['class'=>form_validation($errors, 'password'), 'placeholder' => __('Enter current password'), 'id' => 'password']) }}
                    <span class="invalid-feedback" data-name="password">{{ $errors->first('password') }}</span>
                </div>
            </div>

            {{--new password--}}
            <div class="form-group row">
                <label for="new_password" class="col-md-4 control-label pt-2 required">{{ __('New Password') }}</label>
                <div class="col-md-8">
                    {{ Form::password('new_password', ['class'=>form_validation($errors, 'new_password'), 'placeholder' => __('Enter new password'), 'id' => 'new_password']) }}
                    <span class="invalid-feedback" data-name="new_password">{{ $errors->first('new_password') }}</span>
                </div>
            </div>

            {{--email--}}
            <div class="form-group row">
                <label for="new_password_confirmation" class="col-md-4 control-label pt-2 required">{{ __('Confirm New Password') }}</label>
                <div class="col-md-8">
                    {{ Form::password('new_password_confirmation', ['class'=>form_validation($errors,
                    'new_password_confirmation'),
                    'placeholder' => __('Confirm new password'), 'id' => 'new_password_confirmation']) }}
                    <span class="invalid-feedback" data-name="new_password_confirmation">{{ $errors->first('new_password_confirmation') }}</span>
                </div>
            </div>
            {{--submit button--}}
            <div class="form-group">
                {{ Form::submit(__('Update Password'),['class'=>'btn btn-info lf-card-btn form-submission-button']) }}
                {{ Form::button('<i class="fa fa-undo"></i>',['class'=>'btn btn-danger reset-button lf-card-btn']) }}
            </div>
            {{ Form::close() }}
        @endcomponent
    </div>
@endsection

@section('style')
    @include('layouts.includes._avatar_and_loader_style')
@endsection

@section('script')
    <script src="{{ asset('plugins/cvalidator/cvalidator-language-en.js') }}"></script>
    <script src="{{ asset('plugins/cvalidator/cvalidator.js') }}"></script>
    <script>
        "use strict";

        $(document).ready(function () {
            var form =$('#passwordChangeForm').cValidate({
                rules : {
                    'password' : 'required',
                    'new_password' : 'required|between:6,32',
                    'new_password_confirmation' : 'required|same:new_password',
                },
            });
        });
    </script>
@endsection
