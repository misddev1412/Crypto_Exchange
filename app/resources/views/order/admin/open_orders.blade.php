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
                                <th class="min-desktop text-center">{{ __('Stop/Rate') }}</th>
                                <th class="min-desktop text-right">{{ __('Date') }}</th>
                            </tr>
                        @endslot

                        @foreach($dataTable['items'] as $order)
                            <tr>
                                <td>{{ sprintf('%s / %s',$order->trade_coin, $order->base_coin) }}</td>
                                <td class="text-center {{ $order->type === ORDER_TYPE_BUY ? 'text-success' : 'text-danger' }}">{{ order_type($order->type) }}</td>
                                <td class="text-center">{{ order_categories($order->category) }}</td>
                                <td class="text-right">{{ $order->price }}
                                    <span class="strong">{{ $order->base_coin }}</span></td>
                                <td class="text-right">{{ $order->amount }}
                                    <span class="strong">{{ $order->trade_coin }}</span></td>
                                <td class="text-right">{{ bcmul($order->amount, $order->price) }}
                                    <span class="strong">{{ $order->base_coin }}</span></td>
                                <td class="text-center">
                                    @if(!is_null($order->stop_limit))
                                        {{ $order->stop_limit }}
                                        <span class="strong">{{ $order->base_coin }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-right">{{ $order->created_at }}</td>
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
