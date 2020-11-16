@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        @component('components.profile', ['user' => $user])
            {{ Form::model($user->profile, ['route'=>['profile.update'],'class'=>'form-horizontal edit-profile-form','method'=>'put', 'id' => 'profileEditForm']) }}
            {{--first name--}}
            <div class="form-group row">
                <label for="first_name" class="col-md-4 control-label required">{{ __('First Name') }}</label>
                <div class="col-md-8">
                    {{ Form::text('first_name', null, ['class'=> form_validation($errors, 'first_name'), 'id' => 'first_name']) }}
                    <span class="invalid-feedback">{{ $errors->first('first_name') }}</span>
                </div>
            </div>

            {{--last name--}}
            <div class="form-group row">
                <label for="last_name" class="col-md-4 control-label required">{{ __('Last Name') }}</label>
                <div class="col-md-8">
                    {{ Form::text('last_name', null, ['class'=>form_validation($errors, 'last_name'), 'id' => 'last_name']) }}
                    <span class="invalid-feedback">{{ $errors->first('last_name') }}</span>
                </div>
            </div>

            {{--email--}}
            <div class="form-group row">
                <label class="col-md-4 control-label required">{{ __('Email') }}</label>
                <div class="col-md-8">
                    <p class="form-control">{{ $user->email }}</p>
                </div>
            </div>

            {{--username--}}
            <div class="form-group row">
                <label class="col-md-4 control-label required">{{ __('Username') }}</label>
                <div class="col-md-8">
                    <p class="form-control">{{ $user->username }}</p>
                </div>
            </div>

            {{--address--}}
            <div class="form-group row">
                <label for="address"
                       class="col-md-4 control-label">{{ __('Address') }}</label>
                <div class="col-md-8">
                    {{ Form::textarea('address',  null, ['class'=> form_validation($errors, 'address'), 'id' => 'address', 'rows'=>2]) }}
                    <span class="invalid-feedback">{{ $errors->first('address') }}</span>
                </div>
            </div>
            {{--last name--}}
            <div class="form-group row">
                <label for="phone" class="col-md-4 control-label required">{{ __('Phone') }}</label>
                <div class="col-md-8">
                    {{ Form::text('phone', null, ['class'=>form_validation($errors, 'phone'), 'id' => 'phone']) }}
                    <span class="invalid-feedback">{{ $errors->first('phone') }}</span>
                </div>
            </div>
            {{--submit button--}}
            <div class="form-group">
                {{ Form::submit(__('Update'),['class'=>'btn btn-info lf-card-btn']) }}
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
            var form =$('#profileEditForm').cValidate({
                rules : {
                    'first_name' : 'required|alphaSpace|between:2,255',
                    'last_name' : 'required|alphaSpace|between:2,255',
                    'address' : 'max:500',
                },
            });
        });
    </script>
@endsection
