@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-md-12">
                <!-- DEPOSIT DETAILS -->
                <div class="card lf-toggle-bg-card lf-toggle-border-color mb-5 p-4">
                    <div class="card-header lf-toggle-border-color px-0 pb-4 pt-0">
                        <h3 class="w-card-title">
                            {{ __('Deposit Details') }}
                        </h3>
                    </div>
                    <div class="card-body px-0 pt-4 pb-0">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="pl-0">{{__("User")}}</th>
                                    <td>{{ $deposit->user->profile->full_name }}</td>
                                    <th class="pl-0">{{ __('Wallet') }}</th>
                                    <td>{{ $wallet->coin->name }} ({{ $wallet->coin->symbol }})</td>
                                    <th class="pl-0">{{__("Status")}}</th>
                                    <td>
                                        <span
                                            class="badge badge-{{ config('commonconfig.transaction_status.'.$deposit->status.'.color_class') }}">{{ transaction_status($deposit->status) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pl-0">{{__("Amount")}}</th>
                                    <td>{{ $deposit->amount }}</td>
                                    @if($deposit->address)
                                        <th class="pl-0">{{ __('Address') }}</th>
                                        <td>{{ $deposit->address }}</td>
                                    @endif
                                    @if($deposit->bank_account_id)
                                        <th class="pl-0">{{ __('Bank') }}</th>
                                        <td>{{ $deposit->bankAccount->bank_name }}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <th class="pl-0">{{__("Fee")}}</th>
                                    <td>{{ $deposit->system_fee ?: 'N/A' }}</td>
                                    <th class="pl-0">{{ __('Txn Id') }}</th>
                                    <td>{{ $deposit->txn_id ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>


            @if($deposit->api === API_BANK)
                <!-- USER BANK DETAILS -->
                    <div class="card lf-toggle-bg-card lf-toggle-border-color p-4 mb-5">
                        <div class="card-header lf-toggle-border-color px-0 pb-4 pt-0">
                            <h3 class="w-card-title">
                                {{ __('User Bank Details') }}
                            </h3>
                        </div>
                        <div class="card-body px-0 pt-4 pb-0">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="pl-0">{{__("Bank Name")}}</th>
                                        <td>{{ $deposit->bankAccount->bank_name }}</td>
                                        <th class="pl-0">{{ __('Bank Address') }}</th>
                                        <td>{{ $deposit->bankAccount->bank_address }}</td>
                                    </tr>
                                    <tr>
                                        <th class="pl-0">{{ __('Account Holder') }}</th>
                                        <td>{{ $deposit->bankAccount->account_holder }}</td>
                                        <th class="pl-0">{{ __('Account Number') }}</th>
                                        <td>{{ $deposit->bankAccount->reference_number }}</td>
                                    </tr>
                                    <tr>
                                        <th class="pl-0">{{__("SWIFT")}}</th>
                                        <td>{{ $deposit->bankAccount->swift }}</td>
                                        <th class="pl-0">{{ __('IBAN') }}</th>
                                        <td>{{ $deposit->bankAccount->iban }}</td>
                                    </tr>
                                    <tr>
                                        <th class="pl-0">{{__("Status")}}</th>
                                        <td>
                                            <span
                                                class="badge badge-{{ config('commonconfig.verification_status.'.$deposit->bankAccount->is_verified.'.color_class') }}">
                                            {{ verified_status($deposit->bankAccount->is_verified) }}
                                            </span>
                                        </td>
                                        <th class="pl-0">{{ __('Country') }}</th>
                                        <td>{{ $deposit->bankAccount->country->name }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- select system bank -->
                @if($deposit->status === STATUS_PENDING)
                    {{ Form::open(['url' => route('user.wallets.deposits.update', ['wallet' => $wallet->symbol, 'deposit' => $deposit->id]), 'method' => 'put', 'files' => true, 'class' => 'validator', 'id' => 'depositForm']) }}
                    <!-- USER BANK DETAILS -->
                        <div class="card lf-toggle-bg-card lf-toggle-border-color p-4">
                            <div class="card-header lf-toggle-border-color px-0 pb-4 pt-0">
                                <h3 class="w-card-title">
                                    {{ __("System Bank Details") }}
                                </h3>
                            </div>
                            <div class="card-body px-0 pt-4 pb-0">
                                <!-- banks -->
                                @if(count($systemBanks) > 0)
                                    <h5>
                                        {{ __('Select a bank to deposit') }}
                                    </h5>
                                    <div class="row">
                                        @foreach($systemBanks as $systemBank)
                                            <div class="{{ count($systemBanks) == 1? 'col-md-12': 'col-md-6' }}">
                                                <label class="d-block lf-cursor-pointer h-100" for="{{ $systemBank->id }}">
                                                    {{ Form::radio('system_bank_id', $systemBank->id, null,
                                                ['id' => $systemBank->id,'class' => 'd-none bank-list']) }}
                                                    <div class="card lf-toggle-bg-card lf-toggle-border-color bank-card my-3 h-100">
                                                        <div class="card-header lf-toggle-border-color py-4">
                                                            <h3 class="w-card-title">
                                                                {{ $systemBank->bank_name }}
                                                            </h3>
                                                        </div>

                                                        <div class="card-body p-4">
                                                            <div class="table-responsive">
                                                                <table class="table table-borderless">
                                                                    <tr>
                                                                        <th>{{ __("Bank Name") }}</th>
                                                                        <td>
                                                                            <span class="mx-3">:</span> {{ $systemBank->bank_name }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>{{ __("Account Holder") }}</th>
                                                                        <td>
                                                                            <span class="mx-3">:</span> {{ $systemBank->account_holder }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>{{  __("Account Number") }}</th>
                                                                        <td>
                                                                            <span class="mx-3">:</span> {{ $systemBank->reference_number }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>{{  __("IBAN") }}</th>
                                                                        <td>
                                                                            <span class="mx-3">:</span> {{ $systemBank->iban }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>{{  __("SWIFT") }}</th>
                                                                        <td>
                                                                            <span class="mx-3">:</span> {{ $systemBank->swift }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>{{  __("Country") }}</th>
                                                                        <td>
                                                                            <span class="mx-3">:</span> {{ $systemBank->country->name }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>{{  __("Address") }}</th>
                                                                        <td>
                                                                            <span class="mx-3">:</span> {{ $systemBank->bank_address }}
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </label>
                                                <!-- bank card -->
                                            </div>
                                        @endforeach
                                        <div class="col-12">
                                            <span class="invalid-feedback"
                                                  data-name="system_bank_id">{{ $errors->first('system_bank_id') }}</span>
                                        </div>
                                    </div>
                            @endif

                            <!-- Receipt Upload -->
                                <div class="form-group my-4">
                                    {{-- {{ Form::label('receipt', __('Upload Receipt')) }}
                                     <span class="mx-2">
                                         :
                                     </span>
                                     {{ Form::file('receipt', ['id' => 'receipt']) }}
                                     <span class="invalid-feedback" data-name="receipt">{{ $errors->first('receipt') }}</span>--}}
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview fileinput-exists thumbnail"></div>
                                        <div class="mt-2">
                                            <span class="btn btn-sm btn-success btn-file">
                                                <span class="fileinput-new">{{ __('Upload Receipt') }}</span>
                                                <span class="fileinput-exists">{{ __('Change') }}</span>
                                                {{ Form::file('receipt', ['class' => 'btn btn-default border','id' => 'receipt']) }}
                                            </span>
                                            <a href="javascript:" class="btn btn-sm btn-danger fileinput-exists"
                                               data-dismiss="fileinput">{{ __('Remove') }}</a>
                                        </div>
                                    </div>
                                    <p class="help-block text-muted">{{ __('Upload bank deposit receipt. Only image is acceptable.') }}</p>
                                    <span class="invalid-feedback" data-name="receipt">{{ $errors->first('receipt') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group bank-deposit-btn">
                            {{ Form::submit(__("Submit"), ['class' => 'btn bg-info text-white btn-block btn-lg lf-toggle-border-color border-top-0 form-submission-button']) }}
                        </div>
                    {{ Form::close() }}
                @endif

                <!-- receipt -->
                @if($deposit->receipt)
                    <!-- SELECTED BANK DETAILS -->
                        <div class="card lf-toggle-bg-card lf-toggle-border-color p-4 mb-5">
                            <div class="card-header lf-toggle-border-color px-0 pb-4 pt-0">
                                <h3 class="w-card-title">
                                    {{ __('System Bank Details') }}
                                </h3>
                            </div>
                            <div class="card-body px-0 pt-4 pb-0">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th class="pl-0">{{__("Bank Name")}}</th>
                                            <td>{{ $deposit->systemBankAccount->bank_name }}</td>
                                            <th class="pl-0">{{ __('Bank Address') }}</th>
                                            <td>{{ $deposit->systemBankAccount->bank_address }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0">{{ __('Account Holder') }}</th>
                                            <td>{{ $deposit->systemBankAccount->account_holder }}</td>
                                            <th class="pl-0">{{ __('Account Number') }}</th>
                                            <td>{{ $deposit->systemBankAccount->reference_number }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0">{{__("SWIFT")}}</th>
                                            <td>{{ $deposit->systemBankAccount->swift }}</td>
                                            <th class="pl-0">{{ __('IBAN') }}</th>
                                            <td>{{ $deposit->systemBankAccount->iban }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- receipt -->
                        <div class="card lf-toggle-bg-card lf-toggle-border-color p-4">
                            <div class="card-header lf-toggle-border-color px-0 pb-4 pt-0">
                                <h3 class="w-card-title">
                                    {{ __('Bank Receipt') }}
                                </h3>
                            </div>
                            <div class="card-body px-0 pt-4 pb-0">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="d-flex">
                                            <div class="bg-primary text-white icon-box mr-3 align-self-start d-none d-sm-none d-md-flex">
                                                <i class="fa fa-bank m-auto"></i>
                                            </div>
                                            <figure>
                                                <img class="img-fluid" src="{{ get_deposit_receipt($deposit->receipt) }}" alt="">
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/jasny-bootstrap/css/jasny-bootstrap.min.css') }}">
    <style>
        .w-card-title {
            font-size: 20px;
            font-weight: 400;
            margin: 0;
            padding-right: 40px;
        }

        .card-header {
            background-color: transparent;
        }

        .bank-card {
            border-radius: 10px;
        }

        .bank-card > .card-header {
            position: relative;
            padding-left: 90px;
        }

        .bank-card > .card-header::before {
            content: "\f19c";
            font-family: FontAwesome;
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

        .icon-box {
            font-size: 80px;
            text-align: center;
            padding: 2rem;
        }

        .thumbnail {
            width: 152px;
            max-height: 152px;
        }

        .thumbnail img {
            max-width: 100%;
        }

        .thumbnail i {
            font-size: 50px;
        }

        .bank-list + div {
            position: relative;
        }

        .bank-list + div::after {
            display: none;
            content: "\f14a";
            color: #29bb08;
            font-family: FontAwesome;
            font-size: 40px;
            position: absolute;
            right: 15px;
            top: 5px;
            z-index: 999999;
        }

        .bank-list:checked + div::after {
            display: initial;
        }

        .bank-list:checked + div {
            background-color: rgba(69, 188, 247, 0.15);
        }

        .bank-card:hover {
            background-color: rgba(69, 188, 247, 0.15);
        }

    </style>
@endsection

@section('script')
    @if($deposit->status === STATUS_PENDING)
        <script src="{{ asset('plugins/cvalidator/cvalidator-language-en.js') }}"></script>
        <script src="{{ asset('plugins/cvalidator/cvalidator.js') }}"></script>
        <script src="{{ asset('plugins/jasny-bootstrap/js/jasny-bootstrap.min.js') }}"></script>
        <script>
            "use strict";

            $(document).ready(function () {
                var form = $('#depositForm').cValidate({
                    rules: {
                        'system_bank_id': 'required',
                        'receipt': 'required|image|max:2048',
                    }
                });

                $(document).on('click', 'input[name="system_bank_id"] +label', function () {
                    console.log($('input[name="system_bank_id"]').prop('checked'));
                })

                $('.fileinput').on('clear.bs.fileinput', function () {
                    form.reFormat('receipt');
                });
            });
        </script>
    @endif
@endsection
