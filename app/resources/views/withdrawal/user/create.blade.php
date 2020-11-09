@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container py-5" id="app">
        <div class="row">
            <div class="col-md-3 text-center text-light">
                <div class="card lf-toggle-border-color lf-toggle-bg-card">
                    <div class="card-header bg-primary">
                        <h3 class="text-bold text-lg-center text-light m-0 font-weight-bold">{{ $wallet->symbol }}</h3>
                        <p class="text-sm-center text-light mb-0">({{ $wallet->coin->name }})</p>
                    </div>
                    <div class="card-body">
                        <img src="{{ get_coin_icon($wallet->coin->icon) }}" alt="{{ $wallet->symbol }}"
                             class="img-rounded img-fluid lf-w-120px">
                    </div>
                    <div class="card-footer bg-success">
                        <h4 class="m-0">{{ __("Balance :balance", ['balance' => $wallet->primary_balance]) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card lf-toggle-border-color lf-toggle-bg-card">
                    <div class="card-body">
                        @if($wallet->coin->withdrawal_status == ACTIVE)
                            {!! Form::open(['route'=>['user.wallets.withdrawals.store', $wallet->symbol], 'method' => 'post', 'class'=>'form-horizontal validator dark-text-color', 'id' => 'withdrawalForm']) !!}
                            @if($wallet->coin->type === COIN_TYPE_CRYPTO)
                                @include('withdrawal.user._crypto_form')
                            @else
                                @include('withdrawal.user._fiat_from')
                            @endif

                            {{--amount--}}
                            <div class="form-group {{ $errors->has('amount') ? 'has-error' : '' }}">
                                <label for="amount"
                                       class="control-label required">{{ __('Amount') }}</label>
                                <div>
                                    {{ Form::text('amount',  old('amount', null), ['class'=>'form-control lf-toggle-bg-input lf-toggle-border-color', 'id' =>'amount', 'placeholder' => __('ex: 20.99')]) }}
                                    <span class="invalid-feedback" data-name="amount">{{ $errors->first('amount') }}</span>
                                </div>
                            </div>

                            {{--withdrawal_policy--}}
                            <div class="form-group {{ $errors->has('withdrawal_policy') ? 'has-error' : '' }}">
                                <div class="d-flex mt-4">
                                    <div class="lf-checkbox">
                                        {{ Form::checkbox('withdrawal_policy', 1, false, ['id' => 'policy']) }}
                                        <label for="policy">{{ __("Accept withdrawal's policy.") }}</label>
                                    </div>
                                    <a class="ml-2 text-info" href=""><small>{{ __("Withdrawal's policy page") }}</small></a>
                                </div>
                                <span class="invalid-feedback" data-name="withdrawal_policy">{{ $errors->first('withdrawal_policy') }}</span>
                            </div>

                            {{--submit button--}}
                            <div class="form-group">
                                {{ Form::submit(__('Withdraw Balance'),['class'=>'btn btn-info form-submission-button']) }}
                            </div>
                            {!! Form::close() !!}
                        @else
                            @include('withdrawal.user._disable_alert')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('withdrawal.user._validation_script')
    <script>
        "use strict";

        @if($wallet->coin->type === COIN_TYPE_FIAT)
        new Vue({
            el: '#app',
            data: {
                showBank: "{{ old('api') === API_BANK ? true : false }}",
            },
            methods: {
                changePaymentMethod(event) {
                    this.showBank = event.target.value == "{{ API_BANK }}" ? true : false;
                }
            }
        });
        @endif
    </script>
@endsection
