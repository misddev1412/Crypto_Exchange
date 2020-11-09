{{--name--}}
<div class="form-group mb-3">
    <label for="name" class="control-label required">{{ __('Name') }}</label>
    {{ Form::text('name',  null, ['class'=> form_validation($errors, 'name'), 'id' => 'name']) }}
    <span class="invalid-feedback" data-name="name">{{ $errors->first('name') }}</span>
</div>

{{--is_active--}}
<div class="form-group mb-3">
    <label for="is_active" class="control-label required">{{ __('Active Status') }}</label>
    <div>
        <div class="lf-switch">
            {{ Form::radio('is_active', ACTIVE, true, ['id' => 'is_active-active', 'class' => 'lf-switch-input']) }}
            <label for="is_active-active" class="lf-switch-label">{{ __('Active') }}</label>

            {{ Form::radio('is_active', INACTIVE, false, ['id' => 'is_active-inactive', 'class' => 'lf-switch-input']) }}
            <label for="is_active-inactive" class="lf-switch-label">{{ __('Inactive') }}</label>
        </div>
        <span class="invalid-feedback" data-name="is_active">{{ $errors->first('is_active') }}</span>
    </div>
</div>

{{--submit buttn--}}
<div class="form-group">
    {{ Form::submit(__('Save'),['class'=>'btn btn-info form-submission-button']) }}
    {{ Form::button('<i class="fa fa-undo"></i>',['class'=>'btn btn-danger', 'type' => 'reset']) }}
</div>
