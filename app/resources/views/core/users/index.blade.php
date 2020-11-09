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
                                <th class="all">{{ __('Email') }}</th>
                                <th class="min-phone-l">{{ __('First Name') }}</th>
                                <th class="min-phone-l">{{ __('Last Name') }}</th>
                                <th class="min-phone-l">{{ __('User Group') }}</th>
                                <th class="min-phone-l">{{ __('Username') }}</th>
                                <th class="none">{{ __('Registered Date') }}</th>
                                <th class="text-center min-phone-l">{{ __('Status') }}</th>
                                <th class="text-right all no-sort">{{ __('Action') }}</th>
                            </tr>
                        @endslot

                        @foreach($dataTable['items'] as $key=>$user)
                            <tr>
                                <td>
                                    @if(has_permission('admin.users.show'))
                                        <a href="{{ route('admin.users.show', $user->id) }}">{{ $user->email }}</a>
                                    @else
                                        {{ $user->email }}
                                    @endif
                                </td>
                                <td>{{ $user->profile->first_name }}</td>
                                <td>{{ $user->profile->last_name }}</td>
                                <td>{{  ucwords($user->assigned_role)}}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                <td class="text-center">
                                    <span class="font-size-12 py-1 px-2 badge badge-{{ config('commonconfig.account_status.' . $user->status . '.color_class') }}">{{ account_status($user->status) }}
                                    </span>
                                </td>
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
                                            @if(has_permission('admin.users.show'))
                                                <a class="dropdown-item"
                                                   href="{{ route('admin.users.show',$user->id)}}"><i
                                                        class="fa fa-eye"></i> {{ __('Show') }}</a>
                                            @endif
                                            @if(has_permission('admin.users.edit'))
                                                <a class="dropdown-item"
                                                   href="{{ route('admin.users.edit',$user->id)}}"><i
                                                        class="fa fa-pencil-square-o fa-lg"></i> {{ __('Edit Info') }}
                                                </a>
                                            @endif
                                            @if(has_permission('admin.users.edit.status'))
                                                <a class="dropdown-item"
                                                   href="{{ route('admin.users.edit.status',$user->id)}}"><i
                                                        class="fa fa-pencil-square fa-lg"></i> {{ __('Edit Status') }}
                                                </a>
                                            @endif
                                            @if(has_permission('admin.users.wallets.index'))
                                                <a class="dropdown-item"
                                                   href="{{ route('admin.users.wallets.index',$user->id)}}"><i
                                                        class="fa fa-list-alt fa-lg"></i> {{ __('View Wallet') }}
                                                </a>
                                            @endif
                                            @if(has_permission('admin.users.open-orders.index'))
                                                <a class="dropdown-item"
                                                   href="{{ route('admin.users.open-orders.index',$user->id)}}"><i
                                                        class="fa fa-list fa-lg"></i> {{ __('View Active Orders') }}
                                                </a>
                                            @endif
                                            @if(has_permission('admin.users.trade-history.index'))
                                                <a class="dropdown-item"
                                                   href="{{ route('admin.users.trade-history.index', $user->id)}}"><i
                                                        class="fa fa-list fa-lg"></i> {{ __('View Trading History') }}
                                                </a>
                                            @endif
                                            @if(has_permission('admin.users.activities'))
                                                <a class="dropdown-item"
                                                   href="{{ route('admin.users.activities', $user->id)}}"><i
                                                        class="fa fa-list fa-lg"></i> {{ __('Activities') }}
                                                </a>
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
