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
                                <th class="min-desktop">{{ __('Category') }}</th>
                                <th class="all">{{ __('Price') }}</th>
                                <th class="min-desktop">{{ __('Amount') }}</th>
                                <th class="min-desktop">{{ __('Total') }}</th>
                                <th class="none">{{ __('Stop/Rate') }}</th>
                                <th class="none">{{ __('User') }}</th>
                                <th class="min-desktop text-right">{{ __('Date') }}</th>
                            </tr>
                        @endslot

                        @foreach($dataTable['items'] as $transaction)
                            <tr>
                                <td>{{ $transaction->coin_symbol }}/{{ $transaction->base_coin_symbol }}</td>
                                <td>{{ order_type($transaction->order_type) }}</td>
                                <td>{{ order_categories($transaction->category) }}</td>
                                <td>{{ $transaction->price }}</td>
                                <td>{{ $transaction->amount }}</td>
                                <td>
                                    {{ bcadd($transaction->fee,$transaction->referral_earning) }}
                                    ({{ $transaction->is_maker == 1 ?
                                            number_format($transaction->maker_fee, 2) . '%' :
                                            number_format($transaction->taker_fee, 2) . '%' }})
                                </td>
                                <td>{{ $transaction->total }}</td>
                                <td>
                                    @if(has_permission('admin.users.show'))
                                        <a href="{{ route('admin.users.show', $transaction->user_id) }}">{{ $transaction->email }}</a>
                                    @else
                                        {{ $transaction->email }}
                                    @endif
                                </td>
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
