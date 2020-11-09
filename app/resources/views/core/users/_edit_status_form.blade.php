{{--user email status--}}
<div class="form-group row">
    <label for="is_email_verified" class="col-md-4 control-label required">{{ __('Email Status') }}</label>
    <div class="col-md-8">
        <div class="lf-select">
        {{ Form::select('is_email_verified', verified_status(), $user->is_email_verified, ['class' => form_validation($errors,
        'is_email_verified'),'id' => 'is_email_verified','placeholder' => __('Select Status')]) }}
        </div>
        <span class="invalid-feedback">{{ $errors->first('is_email_verified') }}</span>
    </div>
</div>

{{--user status--}}
<div class="form-group row">
    <label for="status" class="col-md-4 control-label required">{{ __('Account Status') }}</label>
    <div class="col-md-8">
        <div class="lf-select">
        {{ Form::select('status', account_status(), $user->status, ['class' => form_validation($errors, 'status'),
        'id' => 'role','placeholder' => __('Select Status')]) }}
        </div>
        <span class="invalid-feedback">{{ $errors->first('status') }}</span>
    </div>
</div>

{{--user financial status--}}
<div class="form-group row">
    <label for="is_financial_active" class="col-md-4 control-label required">{{ __('Financial Status') }}</label>
    <div class="col-md-8">
        <div class="lf-select">
        {{ Form::select('is_financial_active', financial_status(), $user->is_financial_active, ['class' => form_validation($errors,
        'is_financial_active'),'id' => 'is_financial_active','placeholder' => __('Select Status')]) }}
        </div>
        <span class="invalid-feedback">{{ $errors->first('is_financial_active') }}</span>
    </div>
</div>

{{--user maintenance accessible status--}}
<div class="form-group row">
    <label for="is_accessible_under_maintenance" class="col-md-4 control-label required">{{ __('Maintenance Access Status') }}</label>
    <div class="col-md-8">
        <div class="lf-select">
        {{ Form::select('is_accessible_under_maintenance', maintenance_accessible_status(), $user->is_accessible_under_maintenance,
        ['class' => form_validation($errors, 'is_accessible_under_maintenance'),'id' => 'is_accessible_under_maintenance',
        'placeholder' => __('Select Status')]) }}
        </div>
        <span class="invalid-feedback">{{ $errors->first('is_accessible_under_maintenance') }}</span>
    </div>
</div>
{{--submit buttn--}}
<div class="form-group row">
    <div class="offset-md-4 col-md-8">
        {{ Form::submit(__('Update Status'),['class'=>'btn btn-info btn-left btn-sm-block form-submission-form']) }}
        {{ Form::button('<i class="fa fa-undo"></i>',['class'=>'btn btn-danger', 'type' => 'reset']) }}
    </div>
</div>
