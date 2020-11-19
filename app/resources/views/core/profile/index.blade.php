@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        @component('components.profile', ['user' => $user])
            <div class="table-responsive-sm">
                <table class="table table-borderless font-size-14">
                    <tbody>
                    <tr>
                        <td>{{ __('Name') }}</td>
                        <td><strong class="pr-3">:</strong> {{ $user->profile->full_name }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('User Role') }}</td>
                        <td><strong class="pr-3">:</strong> {{ $user->role->name }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('Email') }}</td>
                        <td><strong class="pr-3">:</strong> {{ $user->email }}
                            @if( settings('require_email_verification') == ACTIVE )
                                <small
                                    class="ml-3 py-1 px-2 badge badge-{{ config("commonconfig.email_status.{$user->is_email_verified}.color_class") }}">{{ verified_status($user->is_email_verified) }}</small>
                                @if(!$user->is_email_verified)
                                    <a class="btn-link pull-right"
                                       href="{{ route('verification.form') }}">{{ __('Verify Account') }}</a>
                                @endif
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>{{ __('Username') }}</td>
                        <td><strong class="pr-3">:</strong> {{ $user->username }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('Address') }}</td>
                        <td><strong class="pr-3">:</strong> {{ $user->profile->address }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('Phone') }}</td>
                        <td><strong class="pr-3">:</strong> {{ $user->profile->phone }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('Account Status') }}</td>
                        <td><strong class="pr-3">:</strong>
                            <small
                                class=" py-1 px-2 badge badge-{{ config("commonconfig.account_status.{$user->status}.color_class") }}">{{ account_status($user->status) }}</small>
                        </td>
                    </tr>
                    <tr>
                        <td>{{ __('Financial Status') }}</td>
                        <td><strong class="pr-3">:</strong>
                            <small
                                class=" py-1 px-2 badge badge-{{ config("commonconfig.financial_status.{$user->is_financial_active}.color_class") }}">{{ financial_status($user->is_financial_active) }}</small>
                        </td>
                    </tr>
                    <tr>
                        <td>{{ __('Maintenance Access Status') }}</td>
                        <td><strong class="pr-3">:</strong>
                            <small
                                class=" py-1 px-2 badge badge-{{ config("commonconfig.maintenance_accessible_status.{$user->is_accessible_under_maintenance}.color_class") }}">{{ maintenance_accessible_status($user->is_accessible_under_maintenance) }}</small>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            @slot('button')
                <a href="{{ route('profile.edit') }}"
                   class="btn lf-card-btn btn-info">{{ __('Edit Profile') }}</a>
            @endslot
        @endcomponent
    </div>
@endsection

@section('style')
    @include('layouts.includes._avatar_and_loader_style')
@endsection
