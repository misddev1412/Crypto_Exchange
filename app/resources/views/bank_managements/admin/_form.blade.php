<div class="form-row">
    <div class="col">
        {{--reference_number--}}
        <div class="form-group">
            <label for="reference_number" class="control-label required">{{ __('Reference Number') }}</label>
            {{ Form::text('reference_number',  null, ['class'=>form_validation($errors, 'reference_number'), 'id' => 'reference_number']) }}
            <span class="invalid-feedback" data-name="reference_number">{{ $errors->first('reference_number') }}</span>
        </div>
    </div>

    <div class="col">
        {{--bank_name--}}
        <div class="form-group mb-3">
            <label for="bank_name" class="control-label required">{{ __('Bank Name') }}</label>
            {{ Form::text('bank_name',  null, ['class'=> form_validation($errors, 'bank_name'), 'id' => 'bank_name']) }}
            <span class="invalid-feedback" data-name="bank_name">{{ $errors->first('bank_name') }}</span>
        </div>
    </div>
</div>

<div class="form-row">
    <div class="col">
        {{--iban--}}
        <div class="form-group mb-3">
            <label for="iban" class="control-label required">{{ __('IBAN') }}</label>
            {{ Form::text('iban',  null, ['class'=> form_validation($errors, 'iban'), 'id' => 'iban']) }}
            <span class="invalid-feedback" data-name="iban">{{ $errors->first('iban') }}</span>
        </div>
    </div>

    <div class="col">
        {{--swift--}}
        <div class="form-group mb-3">
            <label for="swift" class="control-label required">{{ __('SWIFT') }}</label>
            {{ Form::text('swift', null, ['class'=> form_validation($errors, 'swift'), 'id' => 'swift']) }}
            <span class="invalid-feedback" data-name="swift">{{ $errors->first('swift') }}</span>
        </div>
    </div>
</div>

    {{--bank_address--}}
    <div class="form-group mb-3">
        <label for="bank_address" class="control-label required">{{ __('Bank Address') }}</label>
        {{ Form::textarea('bank_address',  null, ['class'=> form_validation($errors, 'bank_address'), 'id' => 'bank_address', 'rows' => 3]) }}
        <span class="invalid-feedback" data-name="bank_address">{{ $errors->first('bank_address') }}</span>
    </div>

    {{--account_holder--}}
    <div class="form-group mb-3">
        <label for="account_holder" class="control-label required">{{ __('Account Holder') }}</label>
        {{ Form::text('account_holder',  null, ['class'=> form_validation($errors, 'account_holder'), 'id' => 'account_holder']) }}
        <span class="invalid-feedback" data-name="account_holder">{{ $errors->first('account_holder') }}</span>
    </div>
    {{--account_holder_address--}}
    <div class="form-group mb-3">
        <label for="account_holder_address" class="control-label required">{{ __('Account Holder Address') }}</label>
        {{ Form::textarea('account_holder_address',  null, ['class'=> form_validation($errors, 'account_holder_address'), 'id' => 'account_holder_address', 'rows' => 3]) }}
        <span class="invalid-feedback"
              data-name="account_holder_address">{{ $errors->first('account_holder_address') }}</span>
    </div>

    {{--country_id--}}
    <div class="form-group mb-3">
        <label for="country_id" class="control-label required">{{ __("Bank's Country") }}</label>
        {{ Form::select('country_id', $countries, null, ['class' => form_validation($errors, 'country_id') ,'id' => 'country_id']) }}
        <span class="invalid-feedback" data-name="country_id">{{ $errors->first('country_id') }}</span>
    </div>

    {{--is_active--}}
    <div class="form-group mb-3">
        <label for="is_active" class="control-label required d-block">{{ __('Active Status') }}</label>
        <div class="lf-switch">
            {{ Form::radio('is_active', ACTIVE, true, ['id' => 'is_active' . '-active', 'class' => 'lf-switch-input']) }}
            <label for="{{ 'is_active' }}-active" class="lf-switch-label">{{ __('Active') }}</label>

            {{ Form::radio('is_active', INACTIVE, false, ['id' => 'is_active' . '-inactive', 'class' => 'lf-switch-input']) }}
            <label for="{{ 'is_active' }}-inactive" class="lf-switch-label">{{ __('Inactive') }}</label>
        </div>
        <span class="invalid-feedback" data-name="is_active">{{ $errors->first('is_active') }}</span>
    </div>

    {{--submit buttn--}}
    <div class="form-group">
        {{ Form::submit(__('Save'),['class'=>'btn form-btn btn-info form-submission-button']) }}
        {{ Form::button('<i class="fa fa-undo"></i>',['class'=>'btn form-btn btn-danger', 'type' => 'reset']) }}
    </div>
