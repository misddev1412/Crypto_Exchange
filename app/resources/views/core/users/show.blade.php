@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-md-3 mb-4">
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
                        <h4 class="card-title my-auto">{{ view_html(__('Basic Details of :user', ['user' => '<strong>' . $user->profile->full_name . '</strong>'])) }}</h4>
                        <div class="card-link">
                            <a href="{{ route('admin.users.index') }}"
                               class="btn btn-info btn-sm back-button"><i class="fa fa-reply"></i></a>
                        </div>
                    @endslot

                    <div class="table-responsive">
                        @component('components.table', ['type' => 'borderless', 'class' => 'lf-toggle-text-color'])
                            <tr class="border-top-0">
                                <th>{{ __('Name') }}</th>
                                <td>{{ $user->profile->full_name }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('User Role') }}</th>
                                <td>{{ \Illuminate\Support\Str::title($user->assigned_role) }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Email') }}</th>
                                <td>{{ $user->email }} <span
                                        class="badge badge-{{ config('commonconfig.email_status.' . $user->is_email_verified . '.color_class') }}">{{ verified_status($user->is_email_verified) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('Username') }}</th>
                                <td>{{ $user->username }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Address') }}</th>
                                <td>{{ $user->profile->address }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Account Status') }}</th>
                                <td>
                        <span
                            class="badge badge-{{ config('commonconfig.account_status.' . $user->status . '.color_class') }}">{{ account_status($user->status) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('Financial Status') }}</th>
                                <td>
                        <span
                            class="badge badge-{{ config('commonconfig.financial_status.' . $user->is_financial_active . '.color_class') }}">{{ financial_status($user->is_financial_active) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('Maintenance Access Status') }}</th>
                                <td>
                        <span
                            class="badge badge-{{ config('commonconfig.maintenance_accessible_status.' . $user->is_accessible_under_maintenance . '.color_class') }}">{{ maintenance_accessible_status($user->is_accessible_under_maintenance) }}</span>
                                </td>
                            </tr>
                        @endcomponent
                    </div>

                    @slot('footer')
                        <a href="{{ route('admin.users.edit', $user->id) }}"
                           class="btn btn-sm btn-info btn-sm-block">{{ __('Edit Information') }}</a>
                        <a href="{{ route('admin.users.edit.status', $user->id) }}"
                           class="btn btn-sm btn-danger btn-sm-block">{{ __('Edit Status') }}</a>
                    @endslot
                @endcomponent
            </div>
        </div>
    </div>
@endsection
@section('style')
    <style>
        .list-group-item {
            background: transparent;
        }
    </style>
@endsection
