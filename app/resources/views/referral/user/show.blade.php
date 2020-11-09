@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                {{ $dataTable['filters'] }}
                <div class="my-4">
                    @component('components.table',['class'=> 'lf-data-table'])
                        @slot('thead')
                            <tr class="bg-primary text-white">
                                <th class="all">{{ __('Icon') }}</th>
                                <th class="all">{{ __('Wallet') }}</th>
                                <th class="all text-right">{{ __('Amount') }}</th>
                            </tr>
                        @endslot

                        @foreach($dataTable['items'] as $referrerEarning)
                            <tr>
                                <td>
                                    <img class="img-sm img-table" src="{{ get_coin_icon($referrerEarning->coin->icon) }}" alt="{{ $referrerEarning->coin->symbol }}">
                                </td>
                                <td>{{ $referrerEarning->symbol }}</td>
                                <td class="text-right">{{ $referrerEarning->amount }} {{ $referrerEarning->symbol }}</td>
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
