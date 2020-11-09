@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-md-3">
                <!-- Profile Image -->
                @include('core.profile.user_avatar')
            </div>
            <div class="col-md-9">
                @component('components.card', [
                    'class' => 'lf-toggle-bg-card lf-toggle-border-color',
                    'headerClass' => "bg-primary text-white d-flex justify-content-between",
                    'footerClass' => "bg-primary text-white",
                ])
                    @slot('header')
                        <h4 class="card-title my-auto">
                            {{ view_html(__('Status Details of :user', ['user' => '<strong>' . $user->profile->full_name . '</strong>'])) }}
                        </h4>
                        <div class="card-link">
                            <a href="{{ route('admin.users.index') }}"
                               class="btn btn-info btn-sm back-button"><i class="fa fa-reply"></i></a>
                        </div>
                    @endslot

                    {{ Form::model($user,['route'=>['admin.users.update.status',$user->id],'class'=>'form-horizontal user-form','method'=>'put', 'id' => 'userForm']) }}
                    @include('core.users._edit_status_form')
                    {{ Form::close() }}

                    @slot('footer')
                        <a href="{{ route('admin.users.show', $user->id) }}"
                           class="btn btn-sm btn-info btn-sm-block">{{ __('View Information') }}</a>
                        <a href="{{ route('admin.users.edit', $user->id) }}"
                           class="btn btn-sm btn-warning btn-sm-block">{{ __('Edit Information') }}</a>
                    @endslot
                @endcomponent
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
                    'is_email_verified' : 'required|in:{{ array_to_string(verified_status()) }}',
                    'status' : 'required',
                    'is_financial_active' : 'required|in:{{ array_to_string(financial_status()) }}',
                    'is_accessible_under_maintenance' : 'required|in:{{ array_to_string(maintenance_accessible_status()) }}',
                }
            });
        });
    </script>
@endsection
