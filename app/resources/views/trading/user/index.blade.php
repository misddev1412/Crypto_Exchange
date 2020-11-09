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
                                <th class="min-desktop">{{ __('Type') }}</th>
                                <th class="all">{{ __('Price') }}</th>
                                <th class="min-desktop">{{ __('Amount') }}</th>
                                <th class="min-desktop">{{ __('Total') }}</th>
                                <th class="min-desktop">{{ __('Fee') }}</th>
                                <th class="min-desktop text-right">{{ __('Date') }}</th>
                            </tr>
                        @endslot

                        @foreach($dataTable['items'] as $transaction)
                            <tr>
                                <td>{{ $transaction->trade_pair }}</td>
                                <td>{{ order_type($transaction->order_type) }}</td>
                                <td>{{ $transaction->price }}</td>
                                <td>{{ $transaction->amount }}</td>
                                <td>{{ $transaction->total }}</td>
                                <td>{{ $transaction->fee }}</td>

                                <td class="text-right">{{ $transaction->created_at->toFormattedDateString() }}</td>
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
