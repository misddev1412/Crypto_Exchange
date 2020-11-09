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
                                <th class="all">{{ __('Name') }}</th>
                                <th>{{ __('Coin') }}</th>
                                <th>{{ __('Base Coin') }}</th>
                                <th>{{ __('Last Price') }}</th>
                                <th class="text-center">{{ __('Active Status') }}</th>
                                <th class="text-center">{{ __('Default Status') }}</th>
                                <th class="none">{{ __('Created Date') }}</th>
                                <th class="all no-sort">{{ __('Action') }}</th>
                            </tr>
                        @endslot

                        @foreach($dataTable['items'] as $coinPair)
                            <tr>
                                <td>{{ $coinPair->name }}</td>
                                <td>{{ $coinPair->trade_coin }}</td>
                                <td>{{ $coinPair->base_coin }}</td>
                                <td>{{ $coinPair->last_price }}</td>
                                <td class="text-center">
                                    <span
                                        class="font-size-12 py-1 px-2 badge badge-{{ config('commonconfig.active_status.' . $coinPair->is_active . '.color_class') }}">{{ active_status($coinPair->is_active) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($coinPair->is_default)
                                        <span
                                            class="font-size-12 py-1 px-2 badge badge-{{ config('commonconfig.active_status.' . $coinPair->is_default . '.color_class') }}">{{ __('Default') }}
                                    </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $coinPair->created_at }}</td>

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
                                            @if(has_permission('coin-pairs.edit'))
                                                <a href="{{ route('coin-pairs.edit', $coinPair->name) }}"
                                                   class="dropdown-item"><i
                                                        class="fa fa-pencil"></i> {{ __('Edit') }}</a>
                                            @endif
                                            @if(
                                                has_permission('coin-pairs.toggle-status') &&
                                                $coinPair->is_default != ACTIVE
                                            )
                                                <a data-form-id="update-{{ $coinPair->name }}"
                                                   data-form-method="PUT"
                                                   href="{{ route('coin-pairs.toggle-status', $coinPair->name) }}"
                                                   data-alert="{{__("Do you want to change this stock pair's status?")}}"
                                                   class="dropdown-item confirmation"><i
                                                        class="fa fa-edit"></i> {{ __('Change Status') }}</a>
                                            @endif

                                            @if(
                                                has_permission('coin-pairs.make-status-default') &&
                                                $coinPair->is_default != ACTIVE &&
                                                $coinPair->is_active == ACTIVE
                                            )
                                                <a data-form-id="update-default-{{ $coinPair->name }}"
                                                   data-form-method="PUT"
                                                   href="{{ route('coin-pairs.make-status-default', $coinPair->name) }}"
                                                   data-alert="{{__("Do you want to make this stock pair  default?")}}"
                                                   class="dropdown-item confirmation">
                                                    <i class="fa fa-edit"></i> {{ __('Make Default Pair') }}
                                                </a>
                                            @endif

                                            @if(
                                                has_permission('coin-pairs.destroy') &&
                                                $coinPair->is_default != ACTIVE
                                            )
                                                <a data-form-id="delete-{{ $coinPair->name }}"
                                                   data-form-method="DELETE"
                                                   href="{{ route('coin-pairs.destroy', $coinPair->name) }}"
                                                   data-alert="{{__('Do you want to delete this stock item?')}}"
                                                   class="dropdown-item confirmation"><i
                                                        class="fa fa-trash-o"></i> {{ __('Delete') }}</a>
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
