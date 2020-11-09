<div class="row">
    <div class="col-sm-6">
        {{--coin--}}
        <div class="form-group my-3 {{ $errors->has('trade_coin') ? 'has-error' : '' }}">
            <label for="trade_coin" class="control-label required">{{ __('Coin') }}</label>
            {{ Form::select('trade_coin', $coins, null, ['class' => form_validation($errors, 'trade_coin'), 'id' => 'coin', 'placeholder' => __('Select Coin')]) }}
            <span class="invalid-feedback" data-name="trade_coin">{{ $errors->first('trade_coin') }}</span>
        </div>
    </div>

    <div class="col-sm-6">
        {{--base_coin--}}
        <div class="form-group my-3 {{ $errors->has('base_coin') ? 'has-error' : '' }}">
            <label for="base_coin" class="control-label required">{{ __('Base Coin') }}</label>
            {{ Form::select('base_coin', $coins, null,['class' => form_validation($errors, 'base_coin'),'id' => 'base_coin', 'placeholder' => __('Select Base Coin')]) }}

            <span class="invalid-feedback" data-name="base_coin">{{ $errors->first('base_coin') }}</span>
        </div>
    </div>
</div>

{{--last_price--}}
<div class="form-group my-3 {{ $errors->has('last_price') ? 'has-error' : '' }}">
    <label for="last_price" class="control-label required">{{ __('Last Price') }}</label>
    {{ Form::text('last_price',  null, ['class'=>form_validation($errors, 'last_price'), 'id' => 'last_price', 'placeholder' => __('ex: 0.00150000')]) }}
    <span class="invalid-feedback" data-name="last_price">{{ $errors->first('last_price') }}</span>
</div>

{{--is_active--}}
<div class="form-group my-3 {{ $errors->has('is_active') ? 'has-error' : '' }}">
    <label for="is_active" class="control-label required">{{ __('Active Status') }}</label>
    <div>
        <div class="lf-switch">
            {{ Form::radio('is_active', ACTIVE, true, ['id' => 'is_active-active', 'class' => 'lf-switch-input']) }}
            <label for="is_active-active" class="lf-switch-label lf-switch-label-on">{{ __('Active') }}</label>

            {{ Form::radio('is_active', INACTIVE, false, ['id' => 'is_active-inactive', 'class' => 'lf-switch-input']) }}
            <label for="is_active-inactive" class="lf-switch-label lf-switch-label-off">{{ __('Inactive') }}</label>
        </div>
        <span class="invalid-feedback" data-name="is_active">{{ $errors->first('is_active') }}</span>
    </div>
</div>

{{--is_default--}}
@if(!isset($coinPair))
    <div class="form-group my-3 {{ $errors->has('is_default') ? 'has-error' : '' }}">
        <label for="is_default" class="control-label required">{{ __('Is Default') }}</label>
        <div>
            <div class="lf-switch">
                {{ Form::radio('is_default', ACTIVE, false, ['id' => 'is_default-active', 'class' => 'lf-switch-input']) }}
                <label for="is_default-active" class="lf-switch-label lf-switch-label-on">{{ __('Yes') }}</label>
                {{ Form::radio('is_default', INACTIVE, true, ['id' => 'is_default-inactive', 'class' => 'lf-switch-input']) }}
                <label for="is_default-inactive" class="lf-switch-label lf-switch-label-off">{{ __('No') }}</label>
            </div>
            <span class="invalid-feedback" data-name="is_default">{{ $errors->first('is_default') }}</span>
        </div>
    </div>
@endif

{{--submit button--}}
<div class="form-group my-3">
    {{ Form::submit(__('Save'),['class'=>'btn btn-info form-btn form-submission-button']) }}
    {{ Form::reset(__('Reset'),['class'=>'btn btn-danger form-btn']) }}
</div>
