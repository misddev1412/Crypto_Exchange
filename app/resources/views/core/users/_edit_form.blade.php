{{--user group field--}}
<div class="form-group row">
    <label for="assigned_role" class="col-md-4 control-label required">{{ __('User Role') }}</label>
    <div class="col-md-8">
        @if(!$user->is_super_admin && $user->id != Auth::user()->id)
            <div class="lf-select">
                {{ Form::select('assigned_role', $roles, $user->assigned_role,['class' => form_validation($errors, 'assigned_role'),
                'id' => 'assigned_role','placeholder' => __('Select Role')]) }}
            </div>
            <span class="invalid-feedback" data-name="assigned_role">{{ $errors->first('assigned_role') }}</span>
        @else
            <p class="form-control">{{ $roles[$user->assigned_role] }}</p>
        @endif
    </div>
</div>

{{--first name--}}
<div class="form-group row">
    <label for="first_name" class="col-md-4 control-label required">{{ __('First Name') }}</label>
    <div class="col-md-8">
        {{ Form::text('first_name', null, ['class'=> form_validation($errors, 'first_name'), 'id' => 'first_name']) }}
        <span class="invalid-feedback" data-name="first_name">{{ $errors->first('first_name') }}</span>
    </div>
</div>

{{--last name--}}
<div class="form-group row">
    <label for="last_name" class="col-md-4 control-label required">{{ __('Last Name') }}</label>
    <div class="col-md-8">
        {{ Form::text('last_name', null, ['class'=>form_validation($errors, 'last_name'), 'id' => 'last_name']) }}
        <span class="invalid-feedback" data-name="last_name">{{ $errors->first('last_name') }}</span>
    </div>
</div>

{{--email--}}
<div class="form-group row">
    <label class="col-md-4 control-label required">{{ __('Email') }}</label>
    <div class="col-md-8">
        <p class="form-control form-control-sm text-muted">{{ $user->email }}</p>
    </div>
</div>

{{--username--}}
<div class="form-group row">
    <label class="col-md-4 control-label required">{{ __('Username') }}</label>
    <div class="col-md-8">
        <p class="form-control form-control-sm text-muted">{{ $user->username }}</p>
    </div>
</div>

{{--address--}}
<div class="form-group row">
    <label for="address" class="col-md-4 control-label">{{ __('Address') }}</label>
    <div class="col-md-8">
        {{ Form::textarea('address',  null, ['class'=>form_validation($errors, 'address'), 'id' => 'address', 'rows'=>2]) }}
        <span class="invalid-feedback" data-name="address">{{ $errors->first('address') }}</span>
    </div>
</div>

{{--submit button--}}
<div class="form-group row">
    <div class="offset-md-4 col-md-8">
        {{ Form::submit(__('Update Information'),['class'=>'btn btn-info btn-left form-submission-button']) }}
        {{ Form::button('<i class="fa fa-undo"></i>',['class'=>'btn btn-danger', 'type' => 'reset']) }}
    </div>
</div>
