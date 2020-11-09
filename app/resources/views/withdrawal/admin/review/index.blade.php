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
                                <th class="all">{{ __('Date') }}</th>
                                <th class="min-desktop">{{ __('Email') }}</th>
                                <th class="all">{{ __('Actual Amount') }}</th>
                                <th class="all">{{ __('System Fee') }}</th>
                                <th class="min-desktop">{{ __('Amount To Be Send') }}</th>
                                <th class="min-desktop">{{ __('Payment Method') }}</th>
                                <th class="none">{{ __('Address/Bank') }}</th>
                                <th class="none">{{ __('Ref ID') }}</th>
                                <th class="text-right">{{ __("Action") }}</th>
                            </tr>
                        @endslot

                        @foreach($dataTable['items'] as $withdrawal)
                            <tr>
                                <td>{{ $withdrawal->created_at }}</td>
                                <td><a target="_blank" href="{{ route('admin.users.show', $withdrawal->user_id) }}">{{ $withdrawal->user->email }}</a></td>
                                <td>{{ $withdrawal->amount }} {{ $withdrawal->symbol }}</td>
                                <td>{{ $withdrawal->system_fee }} {{ $withdrawal->symbol }}</td>
                                <td>{{ bcsub($withdrawal->amount, $withdrawal->system_fee) }} {{ $withdrawal->symbol }}</td>
                                <td>{{ coin_apis($withdrawal->api) }}</td>
                                <td>{{ $withdrawal->api === API_BANK ? $withdrawal->bankAccount->bank_name : $withdrawal->id }}</td>
                                <td>{{ $withdrawal->id }}</td>
                                <td class="lf-action text-right">
                                    <div class="btn-group">
                                        <button type="button"
                                                class="btn btn-sm btn-info dropdown-toggle"
                                                data-toggle="dropdown"
                                                aria-expanded="false">
                                            <i class="fa fa-gear"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right"
                                             role="menu">
                                            <a href="{{ route('admin.review.withdrawals.show', $withdrawal->id) }}"
                                               class="dropdown-item">
                                                <i class="fa fa-eye-slash"></i> {{ __('Show') }}</a>
                                        </div>
                                    </div>
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
