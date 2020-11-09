@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        @component('components.coin', ['coin' => $coin])
            {{  Form::model($coin, ['route'=>['coins.withdrawal.update', $coin->symbol], 'method' => 'put', 'class'=>'form-horizontal validator', 'enctype'=>'multipart/form-data', 'id' => 'withdrawalSettingForm']) }}
            {{--minimum_withdrawal_amount--}}
            <div class="form-group">
                <label
                    for="minimum_withdrawal_amount"
                    class="control-label required">{{ __('Minimum Withdrawal Amount') }}
                </label>
                {{ Form::text('minimum_withdrawal_amount',  null,
                ['class'=> form_validation($errors, 'minimum_withdrawal_amount'),
                'id' => 'minimum_withdrawal_amount', 'placeholder' => __('ex: 0.0005')]) }}
                <span class="invalid-feedback" data-name="minimum_withdrawal_amount">{{ $errors->first('minimum_withdrawal_amount') }}</span>
            </div>

            {{--daily_withdrawal_limit--}}
            <div class="form-group">
                <label for="daily_withdrawal_limit" class="control-label required">{{ __('Daily Withdrawal Limit') }}</label>
                {{ Form::text('daily_withdrawal_limit',  null, ['class'=> form_validation($errors, 'daily_withdrawal_limit'),
                'id' => 'daily_withdrawal_limit', 'placeholder' => __('ex: 25')]) }}
                <span class="invalid-feedback" data-name="daily_withdrawal_limit">{{ $errors->first('daily_withdrawal_limit') }}</span>
            </div>

            {{--withdrawal_fee--}}
            <div class="form-group">
                <label for="withdrawal_fee" class="control-label required">{{ __('Withdrawal Fee') }}</label>
                <div class="input-group">
                    {{ Form::text('withdrawal_fee', null, ['class'=> form_validation($errors, 'withdrawal_fee'),
                    'id' => 'withdrawal_fee', 'placeholder' => __('ex: 0.01')]) }}
                    <span class="input-group-addon">
                                    {{ Form::select('withdrawal_fee_type', fee_types(),  null, ['class'=>'form-control no-select',
                                    'id' => 'withdrawal_fee_type']) }}
                                </span>
                </div>
                <span class="invalid-feedback" data-name="withdrawal_fee">{{ $errors->first('withdrawal_fee') }}</span>
            </div>

            {{--withdrawal_status--}}
            <div class="form-group">
                <label for="withdrawal_status" class="control-label required">{{ __('Withdrawal Status') }}</label>
                <div>
                    <div class="lf-switch">
                        {{ Form::radio('withdrawal_status', ACTIVE, null, ['id' => 'withdrawal_status' . '-active',
                        'class' => 'lf-switch-input']) }}
                        <label
                            for="{{ 'withdrawal_status' }}-active"
                            class="lf-switch-label lf-switch-label-on">{{ __('Enable') }}
                        </label>

                        {{ Form::radio('withdrawal_status', INACTIVE, null, ['id' => 'withdrawal_status' . '-inactive',
                        'class' => 'lf-switch-input']) }}
                        <label
                            for="{{ 'withdrawal_status' }}-inactive"
                            class="lf-switch-label lf-switch-label-off">{{ __('Disable') }}
                        </label>
                    </div>
                    <span class="invalid-feedback" data-name="withdrawal_status">{{ $errors->first('withdrawal_status') }}</span>
                </div>
            </div>
            {{--submit button--}}
            <div class="form-group my-3">
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
            var form =$('#withdrawalSettingForm').cValidate({
                rules : {
                    'minimum_withdrawal_amount' : 'required|numeric|between:0.00000001,99999999999.99999999|decimalScale:11,8',
                    'daily_withdrawal_limit' : 'numeric|between:0.00000001,99999999999.99999999|decimalScale:11,8',
                    'withdrawal_fee' : 'required|numeric|min:0|decimalScale:6,8',
                    'withdrawal_status' : 'required',
                }
            });
        });
    </script>
@endsection
