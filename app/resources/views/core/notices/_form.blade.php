{{--title--}}
<div class="form-group">
    <label for="title" class="col-form-label required">{{ __('Title') }}</label>
    <div>
        {{ Form::text('title',  null, ['class'=> form_validation($errors, 'title', 'lf-toggle-bg-input lf-toggle-border-color'), 'id' => 'title']) }}
        <span class="invalid-feedback" data-name="title">{{ $errors->first('title') }}</span>
    </div>
</div>

{{--description--}}
<div class="form-group">
    <label for="description"
           class="col-form-label required">{{ __('Description') }}</label>
    <div>
        {{ Form::textarea('description',  null, ['class'=>form_validation($errors, 'description', 'lf-toggle-bg-input lf-toggle-border-color'), 'id' => 'description']) }}
        <span class="invalid-feedback" data-name="description">{{ $errors->first('description') }}</span>
    </div>
</div>

{{--type--}}
<div class="form-group">
    <label for="type" class="col-form-label required">{{ __('Type') }}</label>
    <div class="lf-select">
        {{ Form::select('type', notices_types(), null, ['class'=>form_validation($errors, 'type', 'lf-toggle-bg-input lf-toggle-border-color'), 'placeholder'=> __('Select type'), 'id' => 'type']) }}
    </div>
    <span class="invalid-feedback" data-name="type">{{ $errors->first('type') }}</span>
</div>

{{--visible_type--}}
<div class="form-group">
    <label for="visible_type" class="col-form-label required">{{ __('Visibility') }}</label>
    <div class="lf-select">
        {{ Form::select('visible_type', notices_visible_types(), null, ['class'=>form_validation($errors, 'visible_type', 'lf-toggle-bg-input lf-toggle-border-color'), 'placeholder'=> __('Select visible type'), 'id' => 'visible_type']) }}
    </div>
    <span class="invalid-feedback" data-name="visible_type">{{ $errors->first('visible_type') }}</span>
</div>

{{--Start Time--}}
<div class="form-group">
    <label for="start_time" class="col-form-label required">{{ __('Start Time') }}</label>
    <div>
        {{ Form::text('start_at',  null, ['class'=>form_validation($errors, 'start_at', 'lf-toggle-bg-input lf-toggle-border-color'), 'id' => 'start_time']) }}
        <span class="invalid-feedback" data-name="start_at">{{ $errors->first('start_at') }}</span>
    </div>
</div>

{{--End Time--}}
<div class="form-group">
    <label for="end_time" class="col-form-label required">{{ __('End Time') }}</label>
    <div>
        {{ Form::text('end_at',  null, ['class'=>form_validation($errors, 'end_at', 'lf-toggle-bg-input lf-toggle-border-color'), 'id' => 'end_time']) }}
        <span class="invalid-feedback" data-name="end_time">{{ $errors->first('end_at') }}</span>
    </div>
</div>

{{--Stauts--}}
<div class="form-group">
    <label for="is_active" class="col-form-label required">{{ __('Status') }}</label>
    <div class="lf-select">
        {{ Form::select('is_active', active_status(), null, ['class'=>form_validation($errors, 'is_active', 'lf-toggle-bg-input lf-toggle-border-color'), 'id' => 'is_active']) }}
    </div>
    <span class="invalid-feedback" data-name="is_active">{{ $errors->first('is_active') }}</span>
</div>

{{--submit buttn--}}
<div class="form-group">
    {{ Form::submit($buttonText,['class'=>'btn form-btn btn-info form-submission-button']) }}
    {{ Form::button('<i class="fa fa-undo"></i>',['class'=>'btn form-btn btn-danger', 'type' => 'reset']) }}
</div>
