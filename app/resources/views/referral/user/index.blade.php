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
                                <th class="min-desktop">{{ __('First Name') }}</th>
                                <th class="min-desktop">{{ __('Last Name') }}</th>
                                <th class="min-desktop">{{ __('Registration Date') }}</th>
                                <th class="text-right all no-sort">{{ __('Action') }}</th>
                            </tr>
                        @endslot

                        @foreach($dataTable['items'] as $referralUser)
                            <tr>
                                <td>{{ $referralUser->profile->first_name }}</td>
                                <td>{{ $referralUser->profile->last_name }}</td>
                                <td>{{ $referralUser->created_at->diffForHumans() }}</td>
                                <td class="text-right">
                                    <a class="btn btn-info btn-sm"
                                       href="{{ route('referral.users.earnings', $referralUser->id) }}">{{ __("View Earning") }}</a>
                                </td>
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
