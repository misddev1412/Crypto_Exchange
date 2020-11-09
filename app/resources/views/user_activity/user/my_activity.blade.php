@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-12">
                {{ $dataTable['filters'] }}
                {{ $dataTable['advanceFilters'] }}
                <div class="my-4">
                    @component('components.table',['class'=> 'lf-data-table'])
                        @slot('thead')
                            <tr class="bg-primary text-white">
                                <th class="all">{{ __('Note') }}</th>
                                <th class="all">{{ __('Ip Address') }}</th>
                                <th class="min-desktop">{{ __('Device') }}</th>
                                <th class="min-desktop">{{ __('Location') }}</th>
                                <th class="min-desktop">{{ __('Browser') }}</th>
                                <th class="none">{{ __('Operating System') }}</th>
                                <th class="min-desktop">{{ __('Date') }}</th>
                            </tr>
                        @endslot
                        @foreach($dataTable['items'] as $key=>$activity)
                            <tr>
                                <td>{{ $activity->note }}</td>
                                <td>{{ $activity->ip_address }}</td>
                                <td>{{ $activity->device }}</td>
                                <td>{{ $activity->location }}</td>
                                <td>{{ $activity->browser }}</td>
                                <td>{{ $activity->operating_system }}</td>
                                <td>{{ $activity->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    @endcomponent
                </div>
                {{ $dataTable['pagination'] }}
            </div>
        </div>
    </div>
@endsection

@section('style')
    @include('layouts.includes.list-css')
@endsection

@section('script')
    @include('layouts.includes.list-js')
@endsection
