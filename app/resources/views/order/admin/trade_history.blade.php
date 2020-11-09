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
                                <th class="all">{{ __('Market') }}</th>
                                <th class="min-desktop text-center">{{ __('Type') }}</th>
                                <th class="min-desktop text-center">{{ __('Category') }}</th>
                                <th class="all text-right">{{ __('Price') }}</th>
                                <th class="min-desktop text-right">{{ __('Amount') }}</th>
                                <th class="min-desktop text-right">{{ __('Total') }}</th>
                                <th class="min-desktop text-center">{{ __('Fee') }}</th>
                                <th class="min-desktop text-right">{{ __('Date') }}</th>
                            </tr>
                        @endslot

                        @foreach($dataTable['items'] as $tradeHistory)
                            <tr>
                                <td>{{ sprintf('%s / %s',$tradeHistory->trade_coin, $tradeHistory->base_coin) }}</td>
                                <td class="text-center {{ $tradeHistory->order_type === ORDER_TYPE_BUY ? 'text-success' : 'text-danger' }}">{{ order_type($tradeHistory->order_type) }}</td>
                                <td class="text-center">{{ order_categories($tradeHistory->order->category) }}</td>
                                <td class="text-right">{{ $tradeHistory->price }}
                                    <span class="strong">{{ $tradeHistory->base_coin }}</span></td>
                                <td class="text-right">{{ $tradeHistory->amount }}
                                    <span class="strong">{{ $tradeHistory->trade_coin }}</span></td>
                                <td class="text-right">{{ $tradeHistory->total }}
                                    <span class="strong">{{ $tradeHistory->base_coin }}</span></td>
                                <td class="text-center">{{ $tradeHistory->fee }} {{ $tradeHistory->order_type === ORDER_TYPE_BUY ? $tradeHistory->base_coin : $tradeHistory->trade_coin }}</td>
                                <td class="text-right">{{ $tradeHistory->created_at }}</td>
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
