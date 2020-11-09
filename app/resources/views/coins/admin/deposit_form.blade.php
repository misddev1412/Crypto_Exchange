@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        @component('components.coin', ['coin' => $coin])
            {{  Form::model($coin, ['route'=>['coins.deposit.update', $coin->symbol], 'method' => 'put',
                        'class'=>'form-horizontal validator', 'id' => 'depositForm']) }}
            {{--deposit_fee--}}
            <div class="form-group">
                <label
                    for="deposit_fee"
                    class="control-label required">{{ __('Deposit Fee') }}
                </label>
                <div class="input-group">
                    {{ Form::text('deposit_fee',  null, ['class'=> form_validation($errors, 'deposit_fee'),
                    'id' => 'deposit_fee', 'placeholder' => __('ex: 0.01')]) }}
                    <span class="input-group-addon">
                                    {{ Form::select('deposit_fee_type', fee_types(),  null, ['class'=>'form-control no-select',
                                    'id' => 'deposit_fee_type']) }}
                                </span>
                </div>
                <span class="invalid-feedback" data-name="deposit_fee_type">{{ $errors->first('deposit_fee') }}</span>
            </div>

            {{--minimum_deposit_amount--}}
            @if($coin->type === COIN_TYPE_FIAT )
                <div class="form-group">
                    <label for="minimum_deposit_amount"
                           class="control-label required">{{ __('Minimum Deposit Amount') }}</label>

                    {{ Form::text('minimum_deposit_amount',  null, ['class'=>form_validation($errors, 'minimum_deposit_amount'),
                    'id' => 'minimum_deposit_amount', 'placeholder' => __('ex: 0.1')]) }}
                    <span class="invalid-feedback" data-name="minimum_deposit_amount">{{ $errors->first('minimum_deposit_amount') }}</span>
                </div>
            @endif

            {{--deposit_status--}}
            <div class="form-group">
                <label
                    for="transaction_status"
                    class="control-label required">{{ __('Deposit Status') }}
                </label>
                <div>
                    <div class="lf-switch">
                        {{ Form::radio('deposit_status', ACTIVE, null, ['id' => 'deposit_status-active',
                        'class' => 'lf-switch-input']) }}
                        <label
                            for="deposit_status-active"
                            class="lf-switch-label lf-switch-label-on">{{ __('Enable') }}
                        </label>

                        {{ Form::radio('deposit_status', INACTIVE, null, ['id' => 'deposit_status-inactive',
                        'class' => 'lf-switch-input']) }}
                        <label
                            for="deposit_status-inactive"
                            class="lf-switch-label lf-switch-label-off">{{ __('Disable') }}
                        </label>
                    </div>
                    <span class="invalid-feedback" data-name="deposit_status">{{ $errors->first('deposit_status') }}</span>
                </div>
            </div>
            {{--submit button--}}
            <div class="form-group">
                {{ Form::submit(__('Update And Save'),['class'=>'btn btn-info form-btn form-submission-button']) }}
                {{ Form::reset(__('Reset'),['class'=>'btn btn-danger form-btn']) }}
            </div>
            {{ Form::close() }}
        @endcomponent
    </div>
@endsection

@section('style')
    @include('coins.admin._style')
    @include('layouts.includes._avatar_and_loader_style')
@endsection

@section('script')
    <script src="{{ asset('plugins/cvalidator/cvalidator-language-en.js') }}"></script>
    <script src="{{ asset('plugins/cvalidator/cvalidator.js') }}"></script>
    <script>
        "use strict";

        $(document).ready(function () {
            var a = $('#depositForm').cValidate({
                rules: {
                    'deposit_fee': 'required|numeric|decimalScale:6,8',
                    'deposit_fee_type': 'in:{{ array_to_string(fee_types()) }}',
                    'deposit_status': 'required',
                    'minimum_deposit_amount': "{{ $coin->type === COIN_TYPE_FIAT ? 'required|numeric|decimalScale:6,8' : ''}}",
                },
                messages: {}
            });
        });
    </script>
@endsection
