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
                            {{ view_html(__('Basic Details of :user', ['user' => '<strong>' . $user->profile->full_name . '</strong>'])) }}
                        </h4>
                        <div class="card-link">
                            <a href="{{ route('admin.users.index') }}"
                               class="btn btn-info btn-sm back-button"><i class="fa fa-reply"></i></a>
                        </div>
                    @endslot

                    {{ Form::model($user->profile,['route'=>['admin.users.update',$user->id],'class'=>'user-form','method'=>'put', 'id' => 'userForm']) }}
                    @include('core.users._edit_form')
                    {{ Form::close() }}

                    @slot('footer')
                        <a href="{{ route('admin.users.show', $user->id) }}"
                           class="btn btn-sm btn-info btn-sm-block">{{ __('View Information') }}</a>
                        <a href="{{ route('admin.users.edit.status', $user->id) }}"
                           class="btn btn-sm btn-danger btn-sm-block">{{ __('Edit Status') }}</a>
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
            $('#userForm').cValidate({
                rules : {
                    'first_name' : 'required|alphaSpace|between:2,255',
                    'last_name' : 'required|alphaSpace|between:2,255',
                    'address' : 'max:500',
                    'assigned_role' : 'required',
                }
            });
        });
    </script>
@endsection
