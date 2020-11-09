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
                            <th class="all">{{ __('Email') }}</th>
                            <th class="min-desktop">{{ __('Bank') }}</th>
                            <th class="min-desktop">{{ __('Wallet') }}</th>
                            <th class="min-desktop text-right">{{ __('Amount') }}</th>
                            <th class="none">{{ __('Ref ID') }}</th>
                            <th class="text-right all no-sort">{{ __('Action') }}</th>
                        </tr>
                    @endslot

                    @foreach($dataTable['items'] as $bankDeposit)
                        <tr>
                            <td>{{ $bankDeposit->created_at }}</td>
                            <td>
                                @if (has_permission('admin.users.show'))
                                    <a target="_blank" href="{{ route('admin.users.show', $bankDeposit->user_id) }}">{{ $bankDeposit->user->email }}</a>
                                @else
                                    {{ $bankDeposit->user->email }}
                                @endif
                            </td>
                            <td>{{ $bankDeposit->bankAccount->bank_name }}</td>
                            <td>{{ $bankDeposit->symbol }}</td>
                            <td class="text-right">{{ $bankDeposit->amount }}</td>
                            <td>{{ $bankDeposit->id }}</td>
                            <td class="lf-action text-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-info dropdown-toggle"
                                            data-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-gear"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" role="menu">
                                        @if( has_permission('admin.review.bank-deposits.show'))
                                            <a class="dropdown-item"
                                               href="{{ route('admin.review.bank-deposits.show',$bankDeposit->id) }}"><i
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
