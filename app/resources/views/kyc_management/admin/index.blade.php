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
                                <th class="min-phone-l">{{ __('Type') }}</th>
                                <th class="min-phone-l">{{ __('Status') }}</th>
                                <th class="text-right all no-sort sorting">{{ __('Action') }}</th>
                            </tr>
                        @endslot
                        @foreach($dataTable['items'] as $kycVerification)
                            <tr>
                                <td>
                                    @if(has_permission('admin.users.show'))
                                        <a href="{{ route('admin.users.show', $kycVerification->id) }}">{{ $kycVerification->user->email }}</a>
                                    @else
                                        {{ $kycVerification->user->email }}
                                    @endif
                                </td>
                                <td>{{ $kycVerification->type ? kyc_type($kycVerification->type) : '-' }}</td>
                                <td>
                                    <span class="badge badge-{{ config('commonconfig.kyc_status.' . $kycVerification->status . '.color_class') }}">{{ kyc_status($kycVerification->status) }}</span>
                                </td>
                                <td class="lf-action text-right">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-info dropdown-toggle"
                                                data-toggle="dropdown">
                                            <i class="fa fa-gear"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            @if(has_permission('kyc-management.show'))
                                                <a href="{{ route('kyc-management.show',$kycVerification->id)}}"
                                                   class="dropdown-item"><i class="fa fa-eye"></i> {{ __('Show') }}</a>
                                            @endif
                                        </ul>
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
