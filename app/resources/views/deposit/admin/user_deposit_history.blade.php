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
                                <th class="all">{{ __('Amount') }}</th>
                                <th class="min-desktop">{{ __('System Fee') }}</th>
                                <th class="min-desktop">{{ __('Address/Bank') }}</th>
                                <th class="all">{{ __('Status') }}</th>
                                <th class="none">{{ __('Txn Id') }}</th>
                                <th class="none">{{ __('Ref ID') }}</th>
                            </tr>
                        @endslot

                        @foreach($dataTable['items'] as $deposit)

                                <tr>
                                    <td>{{ $deposit->created_at }}</td>
                                    <td>{{ $deposit->amount }} <span class="strong">{{ $deposit->symbol }}</span></td>
                                    <td>{{ $deposit->system_fee }} {{ $deposit->symbol }}</td>
                                     <td>
                                         @if ($deposit->api === API_BANK)
                                             {{ $deposit->bankAccount->bank_name }}
                                         @else
                                             {{ $deposit->address }}
                                         @endif
                                     </td>
                                    <td>
                                    <span class="badge badge-{{ config('commonconfig.transaction_status.' . $deposit->status . '.color_class') }}">{{ transaction_status($deposit->status) }}
                                    </span>
                                    </td>
                                    <td>{{ $deposit->txn_id }}</td>
                                    <td>{{ $deposit->id }}</td>
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
