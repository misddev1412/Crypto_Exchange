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
                            <th class="all">{{ __('Date') }}</th>
                            <th class="min-desktop">{{ __('Address/Bank') }}</th>
                            <th class="all">{{ __('Amount') }}</th>
                            <th class="all">{{ __('Status') }}</th>
                            <th class="none">{{ __('Ref ID') }}</th>
                            <th class="none">{{ __('Txn Id') }}</th>
                            <th class="none">{{ __('System Fee') }}</th>
                            <th class="all text-right no-sort">{{ __('Action') }}</th>
                        </tr>
                    @endslot

                    @foreach($dataTable['items'] as $withdrawal)
                        <tr>
                            <td>{{ $withdrawal->created_at }}</td>
                            @if($withdrawal->api == API_BANK)
                                <td>{{ $withdrawal->bankAccount->bank_name }}</td>
                            @else
                                <td>{{ $withdrawal->address }}</td>
                            @endif
                            <td>{{ $withdrawal->amount }} <span class="strong">{{ $withdrawal->symbol }}</span></td>
                            <td>
                                    <span class="font-size-12 py-1 px-2 badge badge-{{ config('commonconfig.transaction_status.' . $withdrawal->status . '.color_class') }}">{{ transaction_status($withdrawal->status) }}
                                    </span>
                            </td>

                            <td>{{ $withdrawal->id }}</td>
                            <td>{{ $withdrawal->txn_id }}</td>
                            <td>{{ $withdrawal->system_fee }} {{ $withdrawal->symbol }}</td>
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
                                        @if(has_permission('user.wallets.withdrawals.show'))
                                            <a href="{{ route('user.wallets.withdrawals.show',['wallet' => $withdrawal->symbol, 'withdrawal' => $withdrawal->id ]) }}"
                                               class="dropdown-item"><i class="fa fa-eye"></i> {{ __('Show') }}</a>
                                        @endif
                                    </div>
                                </div>
                            </td>
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
