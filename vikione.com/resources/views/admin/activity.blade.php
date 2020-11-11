@extends('layouts.admin')
@section('title', 'Activity')

@section('content')
<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="main-content col-lg-12">
                @include('vendor.notice')
                <div class="card content-area content-area-mh">
                    <div class="card-innr">
                        <div class="card-head has-aside">
                            <h4 class="card-title">Recent Activity</h4>
                            <div class="card-opt">
                                <ul class="btn-grp btn-grp-block guttar-20px">
                                    <li>
                                        <input type="hidden" id="activity_action" value="{{ route('admin.ajax.profile.activity.delete') }}">
                                        <a href="javascript:void(0)" data-id="all" class="btn btn-sm btn-auto btn-primary d-sm-block d-none activity-delete"><i class="fas fa-cog mr-2"></i>Clear Log</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <table class="data-table dt-init activity-list" data-items="10">
                            <thead>
                                <tr class="data-item data-head">
                                    <th class="data-col">Date</th>
                                    <th class="data-col d-none d-sm-table-cell">Device</th>
                                    <th class="data-col">Browser</th>
                                    <th class="data-col">IP</th>
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
                                    <td class="data-col"><a href="javascript:void(0)" class="activity-delete text-dark fs-12" data-id="{{ $activity->id }}" title="delete activity"><em class="fas fa-trash"></em></a></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4">No Activity Found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>{{-- .card-innr --}}
                </div>{{-- .card --}}
            </div>{{-- .col --}}
        </div>{{-- .container --}}
    </div>{{-- .container --}}
</div>{{-- .page-content --}}
@endsection