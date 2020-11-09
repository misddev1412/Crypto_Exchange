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
                            <th class="all">{{ __('Amount') }}</th>
                            <th class="all">{{ __('Type') }}</th>
                            <th class="min-desktop">{{ __('Date') }}</th>
                        </tr>
                    @endslot
                    @foreach($dataTable['items'] as $transaction)
                        <tr>
                            <td>{{ $transaction->amount }} <strong>{{ $transaction->coin }}</strong></td>
                            <td>{{ $transaction->type }}</td>
                            <td>{{ $transaction->created_at->toFormattedDateString() }}</td>
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
