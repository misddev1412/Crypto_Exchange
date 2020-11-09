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
                                <th class="all">{{ __('Icon') }}</th>
                                <th class="all text-center">{{ __('Wallet') }}</th>
                                <th class="min-desktop text-center">{{ __('Last Earning At') }}</th>
                                <th class="all text-right">{{ __('Amount') }}</th>
                            </tr>
                        @endslot

                        @foreach($dataTable['items'] as $referralEarning)
                            <tr>
                                <td>
                                    <img class="img-sm img-table" src="{{ get_coin_icon($referralEarning->coin->icon) }}" alt="Coin Icon">
                                </td>
                                <td class="text-center">{{ $referralEarning->symbol }}</td>
                                <td class="text-center">{{ $referralEarning->last_earning_at }}</td>
                                <td class="text-right">{{ $referralEarning->amount }} {{ $referralEarning->symbol }}</td>
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
