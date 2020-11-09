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
                                <th class="all">{{ __('Bank Name') }}</th>
                                <th>{{ __('IBAN') }}</th>
                                <th>{{ __('SWIFT / BIC') }}</th>
                                <th>{{ __('Verification Status') }}</th>
                                <th class="min-desktop-l">{{ __('Bank Address') }}</th>
                                <th>{{ __('Account Holder') }}</th>
                                <th class="min-desktop-l">{{ __('Account Holder Address') }}</th>
                                <th>{{ __('Active Status') }}</th>
                                <th class="min-desktop-l">{{ __('Created Date') }}</th>
                                <th class="text-right all no-sort">{{ __('Action') }}</th>
                            </tr>
                        @endslot

                        @foreach($dataTable['items'] as $key=>$bankAccount)
                            <tr>
                                <td>{{ $bankAccount->bank_name }}</td>
                                <td>{{ $bankAccount->iban }}</td>
                                <td>{{ $bankAccount->swift }}</td>
                                <td>
                                    <span class="badge badge-{{ get_color_class($bankAccount->is_verified, 'verification_status') }} px-3 py-2">
                                        {{ verification_status($bankAccount->is_verified) }}
                                    </span>

                                    @if( $bankAccount->is_verified == INACTIVE )
                                        <span class="badge badge-info px-3 py-2"
                                              data-toggle="tooltip"
                                              data-placement="top"
                                              title="{{ __('Deposit a small amount for verification.') }}"
                                        >
                                            <i class="fa fa-info"></i>
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $bankAccount->bank_address }}</td>
                                <td>{{ $bankAccount->account_holder }}</td>
                                <td>{{ $bankAccount->account_holder_address }}</td>
                                <td class="text-center">{{ view_html($bankAccount->is_active ? '<i class="fa fa-check text-green"></i>' :  '<i class="fa fa-close text-red"></i>') }}</td>
                                <td>{{ $bankAccount->created_at->toFormattedDateString() }}</td>
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
                                            @if($bankAccount->is_verified == INACTIVE && has_permission( 'bank-accounts.edit'))
                                                <a href="{{ route( 'bank-accounts.edit', $bankAccount->id) }}"
                                                   class="dropdown-item"><i class="fa fa-pencil"></i> {{ __('Edit') }}
                                                </a>
                                            @endif
                                            @if(has_permission('bank-accounts.toggle-status'))
                                                <a data-form-id="update-{{ $bankAccount->id }}"
                                                   data-form-method="PUT"
                                                   href="{{ route('bank-accounts.toggle-status', $bankAccount->id) }}"
                                                   class="dropdown-item confirmation"
                                                   data-alert="{{__('Do you want to change this bank\'s status?')}}"><i
                                                        class="fa fa-edit"></i> {{ __('Change Status') }}</a>
                                            @endif

                                            @if($bankAccount->is_verified == INACTIVE && has_permission('bank-accounts.destroy'))
                                                <a data-form-id="destroy-{{ $bankAccount->id }}"
                                                   data-form-method="DELETE"
                                                   href="{{ route( 'bank-accounts.destroy', $bankAccount->id) }}"
                                                   class="dropdown-item confirmation"
                                                   data-alert="{{__('Do you want to delete this bank account?')}}"><i class="fa fa-trash"></i> {{ __('Delete') }}
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
