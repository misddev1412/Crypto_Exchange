@extends('layouts.user')
@section('title', __('User Activity'))
@php
($has_sidebar = true)
@endphp
@section('content')
<div class="content-area card">
    <div class="card-innr">
        @include('layouts.messages')
        <div class="card-head d-flex justify-content-between">
            <h4 class="card-title card-title-md">{{__('Account Activities Log')}}</h4>
            <div class="float-right">
                <input type="hidden" id="activity_action" value="{{ route('user.ajax.account.activity.delete') }}">
                <a href="javascript:void(0)" class="btn btn-auto btn-primary btn-xs activity-delete" data-id="all">{{__('Clear All')}}</a>
            </div>
        </div>
        <div class="card-text">
            <p>{{__('Here is your recent activities. You can clear this log as well as disable the feature from profile settings tabs.')}} </p>
        </div>
        <div class="gaps-1x"></div>
        <table class="data-table dt-init activity-table" data-items="10">
            <thead>
            <tr>
                <th class="activity-time"><span>{{__('Date')}}</span></th>
                <th class="activity-device"><span>{{__('Device')}}</span></th>
                <th class="activity-browser"><span>{{__('Browser')}}</span></th>
                <th class="activity-ip"><span>{{__('IP')}}</span></th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody id="activity-log">
            @forelse($activities as $activity)
                @php
                    $browser = explode('/', $activity->browser);
                    $device = explode('/', $activity->device);
                    $ip = ($activity->ip == '::1' || $activity->ip == '127.0.0.1') ? 'localhost' : $activity->ip ;
                @endphp
                <tr class="data-item activity-{{ $activity->id }}">
                    <td class="data-col">{{ _date($activity->created_at) }}</td>
                    <td class="data-col d-none d-sm-table-cell">{{ end($device) }}</td>
                    <td class="data-col">{{ $browser[0] }}</td>
                    <td class="data-col">{{ $ip }}</td>
                    <td class="data-col"><a href="javascript:void(0)" class="activity-delete fs-16" data-id="{{ $activity->id }}" title="Delete"><em class="ti-trash"></em></a></td>
                </tr>
            @empty

            @endforelse
            </tbody>
        </table>
    </div>{{-- .card-innr --}}
</div>{{-- .card --}}
@endsection