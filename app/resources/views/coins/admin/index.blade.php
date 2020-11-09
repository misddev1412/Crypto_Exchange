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
                                <th>{{ __('Icon') }}</th>
                                <th class="all">{{ __('Coin') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th class="text-center">{{ __('Active Status') }}</th>
                                <th>{{ __('Created Date') }}</th>
                                <th class="text-right all no-sort">{{ __('Action') }}</th>
                            </tr>
                        @endslot

                        @foreach($dataTable['items'] as $key=>$coin)
                            <tr>
                                <td>
                                    @if(!is_null(get_coin_icon($coin->icon)))
                                        <img src="{{ get_coin_icon($coin->icon) }}" alt="Item Emoji" class="img-table cm-center">
                                    @else
                                        <i class="fa fa-money fa-lg text-green"></i>
                                    @endif
                                </td>
                                <td>{{ $coin->symbol }}</td>
                                <td>{{ $coin->name }}</td>
                                <td>{{ coin_types($coin->type) }}</td>
                                <td class="text-center">
                                    <span class="font-size-12 py-1 px-2 badge badge-{{ config('commonconfig.active_status.' . $coin->is_active . '.color_class') }}">{{ active_status($coin->is_active) }}
                                    </span>
                                </td>
                                <td>{{ $coin->created_at->toFormattedDateString() }}</td>

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
                                            @if(has_permission('coins.revenue-graph'))
                                                <a href="{{ route('coins.revenue-graph', $coin->symbol) }}" class="dropdown-item"><i class="fa fa-line-chart"></i> {{ __('Revenue Graph') }}</a>
                                            @endif

                                            @if(has_permission('coins.edit'))
                                                <a href="{{ route('coins.edit', $coin->symbol) }}" class="dropdown-item"><i class="fa fa-pencil"></i> {{ __('Edit') }}</a>
                                            @endif

                                            @if(has_permission('coins.reset-addresses') && $coin->type === COIN_TYPE_CRYPTO)
                                                <a data-form-id="update-{{ $coin->symbol }}" data-form-method="PUT" href="{{ route('coins.reset-addresses', $coin->symbol) }}" class="dropdown-item confirmation" data-alert="{{__('This will remove all addresses related to this coin and future deposits won\'t work with the current addresses. Do you want to continue?')}}"><i class="fa fa-trash-o"></i> {{ __('Remove Addresses') }}</a>
                                            @endif

                                                @if(has_permission('coins.toggle-status'))
                                                <a data-form-id="update-{{ $coin->symbol }}" data-form-method="PUT" href="{{ route('coins.toggle-status', $coin->symbol) }}" class="dropdown-item confirmation" data-alert="{{__('Do you want to change this stock item\'s status?')}}"><i class="fa fa-edit"></i> {{ __('Change Status') }}</a>
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
    <style>
        .img-sm {
            width: 30px !important;
            height: 30px !important;
        }
    </style>
@endsection

@section('script')
    @include('layouts.includes.list-js')
@endsection
