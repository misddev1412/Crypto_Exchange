@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-md-12">
                <!-- WITHDRAWAK DETAILS -->
                <div class="card lf-toggle-bg-card lf-toggle-border-color p-4 mb-5">
                    <div class="card-header lf-toggle-border-color px-0 pb-3 pt-0">
                        <div class="d-block">
                            <h3 class="w-card-title d-inline-block">
                                {{ $title }}
                            </h3>
                            @if(has_permission('user.wallets.withdrawals.destroy') &&
                        in_array($withdrawal->status, [STATUS_PROCESSING, STATUS_PENDING, STATUS_REVIEWING]))
                                <a href="{{ route('user.wallets.withdrawals.destroy', ['wallet' => $withdrawal->symbol, 'withdrawal' => $withdrawal->id]) }}"
                                   class="d-inline-block pull-right btn btn-danger confirmation"
                                   data-form-id="cancel-{{ $withdrawal->id }}" data-form-method="DELETE"
                                   data-alert="{{__('Do you want to cancel this withdrawal?')}}">{{ __('Cancel') }}</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body px-0 pt-3 pb-0">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="pl-0">{{__("User")}}</th>
                                    <td>{{ $withdrawal->user->profile->full_name }}</td>
                                    <th class="pl-0">{{ __('Wallet') }}</th>
                                    <td>{{ $withdrawal->coin->name }} ({{ $withdrawal->symbol }})</td>
                                    <th class="pl-0">{{__("Status")}}</th>
                                    <td>
                                        <span
                                            class="badge badge-{{ config('commonconfig.transaction_status.'.$withdrawal->status.'.color_class') }}">{{ transaction_status($withdrawal->status) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pl-0">{{__("Amount")}}</th>
                                    <td>{{ $withdrawal->amount }}</td>
                                    @if($withdrawal->address)
                                        <th class="pl-0">{{ __('Address') }}</th>
                                        <td>{{ $withdrawal->address }}</td>
                                    @endif
                                    @if($withdrawal->bank_account_id)
                                        <th class="pl-0">{{ __('Bank') }}</th>
                                        <td>{{ $withdrawal->bankAccount->bank_name }}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <th class="pl-0">{{__("Fee")}}</th>
                                    <td>{{ $withdrawal->system_fee ?: 'N/A' }}</td>
                                    <th class="pl-0">{{ __('Txn Id') }}</th>
                                    <td>{{ $withdrawal->txn_id ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

            @if($withdrawal->api === API_BANK)
                <!-- USER BANK DETAILS -->
                    <div class="card lf-toggle-bg-card lf-toggle-border-color p-4 mb-5">
                        <div class="card-header lf-toggle-border-color px-0 pb-3 pt-0">
                            <h3 class="w-card-title">
                                {{ __('User Bank Details') }}
                            </h3>
                        </div>
                        <div class="card-body px-0 pt-4 pb-0">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="pl-0">{{__("Bank Name")}}</th>
                                        <td>{{ $withdrawal->bankAccount->bank_name }}</td>
                                        <th class="pl-0">{{ __('Bank Address') }}</th>
                                        <td>{{ $withdrawal->bankAccount->bank_address }}</td>
                                        <th class="pl-0">{{__("Status")}}</th>
                                        <td>
                                            <span
                                                class="badge badge-{{ config('commonconfig.verification_status.'.$withdrawal->bankAccount->is_verified.'.color_class') }}">
                                            {{ verified_status($withdrawal->bankAccount->is_verified) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="pl-0">{{ __('Account Holder') }}</th>
                                        <td>{{ $withdrawal->bankAccount->account_holder }}</td>
                                        <th class="pl-0">{{ __('Account Number') }}</th>
                                        <td>{{ $withdrawal->bankAccount->reference_number }}</td>
                                        <th class="pl-0">{{ __('Country') }}</th>
                                        <td>{{ $withdrawal->bankAccount->country->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="pl-0">{{__("SWIFT")}}</th>
                                        <td>{{ $withdrawal->bankAccount->swift }}</td>
                                        <th class="pl-0">{{ __('IBAN') }}</th>
                                        <td>{{ $withdrawal->bankAccount->iban }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('style')
    <style>
        .w-card-title {
            font-size: 20px;
            font-weight: 400;
            margin: 0;
        }

        .card-header {
            background-color: transparent;
        }

        .bank-card > .card-header {
            position: relative;
            padding-left: 90px;
        }

        .bank-card > .card-header::before {
            content: "\f19c";
            font-family: FontAwesome, serif;
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 72px;
            font-size: 28px;
            line-height: 72px;
            text-align: center;
            background-color: #105859;
            color: #fff;
            border-radius: 10px 0 0 0;
        }

        .bank-card .table td, .bank-card .table th {
            padding: 0.5rem 0;
        }

        .bank-card .table th {
            width: 35%;
        }

        .form-group.bank-deposit-btn > .btn {
            font-weight: bold;
            border-radius: 0 0 20px 20px !important;
            padding: 1rem;
            font-size: 20px;
        }
    </style>
@endsection


