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
                            <th class="min-desktop">{{ __('Date') }}</th>
                            <th class="min-desktop">{{ __('Payment Method') }}</th>
                            <th class="min-desktop">{{ __('Address') }}</th>
                            <th class="min-desktop">{{ __('Wallet') }}</th>
                            <th class="min-desktop text-right">{{ __('Amount') }}</th>
                            <th class="all text-center">{{ __('Status') }}</th>
                            <th class="none">{{ __('Ref ID') }}</th>
                            <th class="text-right all no-sort">{{ __('Action') }}</th>
                        </tr>
                    @endslot

                    @foreach($dataTable['items'] as $deposit)
                        <tr>
                            <td>{{ $deposit->created_at }}</td>
                            <td>{{ coin_apis($deposit->api) }}</td>
                            <td>{{ $deposit->address ?: '-' }}</td>
                            <td>{{ $deposit->symbol }}</td>
                            <td class="text-right">{{ $deposit->amount }}</td>
                            <td class="text-center">
                                <span
                                    class="font-size-12 py-1 px-2 badge badge-{{ get_color_class($deposit->status,'transaction_status') }}">
                                    {{ transaction_status($deposit->status) }}
                                </span>
                            </td>
                            <td>{{ $deposit->id }}</td>
                            <td class="lf-action text-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-info dropdown-toggle"
                                            data-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-gear"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" role="menu">
                                        @if( has_permission('admin.history.deposits.show'))
                                            <a class="dropdown-item"
                                               href="{{ route('admin.history.deposits.show',$deposit->id) }}"><i
                                                    class="fa fa-eye-slash text-success mr-2"></i>{{ __('Show') }}</a>
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
