@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-12">
                {{ $dataTable['filters'] }}
                {{ $dataTable['advanceFilters'] }}
                @component('components.table',['class'=> 'lf-data-table'])
                    @slot('thead')
                        <tr class="bg-primary text-white">
                            <th class="min-desktop">{{ __('Ref ID') }}</th>
                            <th class="all">{{ __('Amount') }}</th>
                            <th class="all">{{ __('Status') }}</th>
                            <th class="none">{{ __('Address') }}</th>
                            <th class="none">{{ __('Txn Id') }}</th>
                            <th class="none">{{ __('System Fee') }}</th>
                            <th class="min-desktop">{{ __('Date') }}</th>
                        </tr>
                    @endslot

                    @foreach($dataTable['items'] as $withdrawal)
                        <tr>
                            @if($withdrawal->api == API_BANK)
                                <td><a target="_blank" data-toggle="tooltip" data-placement="top" title="{{ __('View Bank Deposit') }}"
                                       href="{{ route('user.wallets.withdrawals.show', ['wallet' => $withdrawal->symbol, 'withdrawal' =>  $withdrawal->id] ) }}">{{ $withdrawal->id }}</a>
                                </td>
                            @else
                                <td>{{ $withdrawal->id }}</td>
                            @endif
                            <td>{{ $withdrawal->amount }} <span class="strong">{{ $withdrawal->symbol }}</span></td>
                            <td>
                                    <span class="badge badge-{{ config('commonconfig.transaction_status.' . $withdrawal->status . '.color_class') }}">{{ transaction_status($withdrawal->status) }}
                                    </span>
                            </td>
                            <td>{{ $withdrawal->address }}</td>
                            <td>{{ $withdrawal->txn_id }}</td>
                            <td>{{ $withdrawal->system_fee }} {{ $withdrawal->symbol }}</td>
                            <td>{{ $withdrawal->created_at->toFormattedDateString() }}</td>
                        </tr>
                    @endforeach
                @endcomponent
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
