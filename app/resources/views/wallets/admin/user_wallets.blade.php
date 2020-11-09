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
                                <th class="all">{{ __('Wallet') }}</th>
                                <th>{{ __('Wallet Name') }}</th>
                                <th>{{ __('Total Balance') }}</th>
                                <th>{{ __('On Order') }}</th>
                                <th class="text-right all no-sort">{{ __('Action') }}</th>
                            </tr>
                        @endslot

                        @foreach($dataTable['items'] as $key=>$wallet)
                            <tr>
                                <td>
                                    <img src="{{ get_coin_icon($wallet->coin->icon) }}" alt="Item Emoji" class="img-table cm-center">
                                    <span class="ml-2">{{ $wallet->coin->symbol }}</span>
                                </td>
                                <td>{{ $wallet->coin->name }}</td>
                                <td>{{ $wallet->primary_balance }}</td>
                                <td>{{ $wallet->on_order_balance }}</td>
                                <td class="lf-action text-right">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-info dropdown-toggle"
                                                data-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-gear"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" role="menu">
                                            @if( has_permission('admin.users.wallets.deposits.index'))
                                                <a class="dropdown-item"
                                                   href="{{ route('admin.users.wallets.deposits.index', ['user' => $wallet->user->id, 'wallet' => $wallet->id]) }}"><i
                                                        class="fa fa-magic"></i> {{ __('Deposit History') }}</a>
                                            @endif
                                            @if( has_permission('admin.users.wallets.withdrawals.index'))
                                                <a class="dropdown-item"
                                                   href="{{ route('admin.users.wallets.withdrawals.index', ['user' => $wallet->user->id, 'wallet' => $wallet->id]) }}"><i
                                                        class="fa fa-history"></i> {{ __('Withdrawal History') }}</a>
                                            @endif
                                            @if( has_permission('admin.users.wallets.adjust-amount.create'))
                                                <a class="dropdown-item"
                                                   href="{{ route('admin.users.wallets.adjust-amount.create', ['user' => $wallet->user->id, 'wallet' => $wallet->id]) }}"><i
                                                        class="fa fa-calculator"></i> {{ __('Adjust Amount') }}</a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
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
