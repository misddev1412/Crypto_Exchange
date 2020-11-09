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
                                    <td>{{ $deposit->symbol }}</td>
                                    <th class="pl-0">{{__("Status")}}</th>
                                    <td>
                                        <span
                                            class="badge badge-{{ get_color_class($deposit->status, 'transaction_status') }}">
                                            {{ transaction_status($deposit->status) }}</span>
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
                    <div class="card lf-toggle-bg-card lf-toggle-border-color mb-5 p-4">
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
                                                class="badge badge-{{ get_color_class($deposit->bankAccount->is_verified, 'verification_status') }}">
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

                    <!-- receipt -->
                @if($deposit->receipt)
                    <!-- SELECTED BANK DETAILS -->
                        <div class="card lf-toggle-bg-card lf-toggle-border-color mb-5 p-4">
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
                                            <img class="img-fluid" src="{{ get_deposit_receipt($deposit->receipt) }}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($deposit->status === STATUS_REVIEWING)
                                <div class="card-footer lf-toggle-border-color text-right mt-3">
                                    @if(has_permission(replace_current_route_action('destroy')))
                                        <a class="btn btn-info" data-target="#deposit-amount-adjust-modal" data-toggle="modal"
                                           href="{{ route(replace_current_route_action('destroy'), $deposit->id) }}"> {{ __("Change Amount") }}</a>
                                    @endif
                                    @if(has_permission(replace_current_route_action('destroy')))
                                        <a data-form-method="delete"
                                           data-form-id="review-bank-depoist-{{ $deposit->id }}"
                                           data-alert="{{ __("Are you sure to cancel this deposit?") }}"
                                           class="btn btn-danger confirmation ml-1"
                                           href="{{ route(replace_current_route_action('destroy'), $deposit->id) }}"> {{ __("Cancel") }}</a>
                                    @endif

                                    @if(replace_current_route_action('update'))
                                        <a data-form-method="put"
                                           data-form-id="review-bank-depoist-{{ $deposit->id }}"
                                           data-alert="{{ __("Are you sure to approve this deposit?") }}"
                                           class="btn btn-success confirmation ml-1"
                                           href="{{ route(replace_current_route_action('update'), $deposit->id) }}"> {{ __("Approve") }}</a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
    @if($deposit->status == STATUS_REVIEWING)
        @include('deposit.admin._amount_adjust_form')
    @endif
@endsection

@section('style')
    <style>
        .w-card-title {
            font-size: 20px;
            font-weight: 400;
            margin: 0;
        }

        .card-header.lf-toggle-border-color {
            background-color: transparent;
        }

        .bank-card {
            border-radius: 10px;
        }

        .bank-card > .card-header.lf-toggle-border-color {
            position: relative;
            padding-left: 90px;
        }

        .bank-card > .card-header.lf-toggle-border-color::before {
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

        .bank-card.my-3.active {
            background-color: #dcf7fe;
        }

        .icon-box {
            font-size: 80px;
            text-align: center;
            padding: 2rem;
        }
    </style>
@endsection

@section('script')
    @if($deposit->status == STATUS_REVIEWING)
    <script src="{{ asset('plugins/cvalidator/cvalidator-language-en.js') }}"></script>
    <script src="{{ asset('plugins/cvalidator/cvalidator.js') }}"></script>
    <script>
        "use strict";

        $(document).ready(function () {
            var form = $("#adjust-amount-form");
            var modal = $("#deposit-amount-adjust-modal");

            form.cValidate({
                rules : {
                    'amount' : 'required|numeric|between:0.00000001, 99999999999.99999999|decimalScale:11,8',
                }
            });

            form.on("submit", function (e) {
                e.preventDefault();
                var thisForm = $(this);
                var url = thisForm.attr('action');
                var formMessage = thisForm.find("#form-message");

                axios.post(url, {
                    amount: thisForm.find("input[name = 'amount']").val(),
                    system_fee: thisForm.find("input[name = 'system_fee']").val()
                })
                .then(function (response) {
                    var responseData = response.data;
                    if(responseData.status === "{{ RESPONSE_TYPE_SUCCESS }}")
                    {
                        form[0].reset();
                        formMessage.removeClass('alert-danger');
                        formMessage.addClass('alert-success');

                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                    else {
                        formMessage.removeClass('alert-success');
                        formMessage.addClass('alert-danger');
                    }

                    formMessage.text(responseData.message);
                })
                .catch(function (error) {
                    jQuery.each( error.response.data.errors, function( i, val ) {
                        var selector = thisForm.find('[data-name = "'+i+'"]');
                        selector.text(val[0])
                    });
                });
            });

            modal.on("hidden.bs.modal", function () {
                form[0].reset();
                form.find('[data-name = "amount"]').text('');
                form.find('[data-name = "system_fee"]').text('');
            });
        });
    </script>
    @endif
    <script>
        "use strict";

        $(document).ready(function () {
            var bankSelect = $("input[name='system_bank_id']");
            var bankCard = $(".bank-card");

            bankSelect.prop('checked', false);

            bankCard.on("click", function () {
                $(this).find("input[name='system_bank_id']").prop('checked', true);
                bankCard.removeClass("active");
                $(this).addClass("active");
            });

            bankSelect.on("change", function () {
                bankCard.removeClass("active");
                $(this).parents(".bank-card").addClass("active");
            });
        });
    </script>
@endsection
