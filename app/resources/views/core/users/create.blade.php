@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="offset-md-3 col-md-6">
                <div class="bg-primary text-white clearfix p-3 lf-toggle-border-color border border-bottom-0">
                    <h5 class="float-left">{{ __('Create New User') }}</h5>
                    <div class="float-right">
                        <a href="{{ route('admin.users.index') }}"
                           class="btn btn-info btn-sm back-button"><i class="fa fa-reply"></i></a>
                    </div>
                </div>
                <div class="card lf-toggle-border-color lf-toggle-bg-card">
                    <div class="card-body">
                        {{ Form::open(['route'=>'admin.users.store', 'method' => 'post', 'class'=>'form-horizontal user-form', 'id' => 'userForm']) }}
                        @include('core.users._create_form')
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('plugins/cvalidator/cvalidator-language-en.js') }}"></script>
    <script src="{{ asset('plugins/cvalidator/cvalidator.js') }}"></script>
    <script>
        "use strict";

        $(document).ready(function () {
            var form =$('#userForm').cValidate({
                rules : {
                    'first_name' : 'required|alphaSpace|between:2,255',
                    'last_name' : 'required|alphaSpace|between:2,255',
                    'email' : 'required|email|between:5,255',
                    'username' : 'required|max:255',
                    'address' : 'max:500',
                    'assigned_role' : 'required',
                    'is_email_verified' : 'required|in:{{ array_to_string(verified_status()) }}',
                    'is_active' : 'required',
                    'is_financial_active' : 'required|in:{{ array_to_string(financial_status()) }}',
                    'is_accessible_under_maintenance' : 'required|in:{{ array_to_string(maintenance_accessible_status()) }}',
                }
            });
        });
    </script>
@endsection
