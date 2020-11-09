{{--name--}}
<div class="form-group">
    {{ Form::label('name', __('Name')) }}
    {{ Form::text('name', null, ['class' => form_validation($errors, 'name', 'lf-toggle-bg-input lf-toggle-border-color')]) }}
    <span class="invalid-feedback" data-name="name">{{ $errors->first('name') }}</span>
</div>

{{--short_code--}}
<div class="form-group">
    {{ Form::label('short_code', __('Short Code')) }}
    {{ Form::text('short_code', null, ['class' => form_validation($errors, 'short_code', 'lf-toggle-bg-input lf-toggle-border-color')]) }}
    <span class="invalid-feedback" data-name="short_code">{{ $errors->first('short_code') }}</span>
</div>

{{--icon--}}
<div class="form-group">
    {{ Form::label('icon', __('Icon'), ['class' => 'd-block']) }}
    <div class="fileinput fileinput-new" data-provides="fileinput">
        @if(isset($language) && $language->icon)
            <div class="fileinput-new img-thumbnail lf-w-120px lf-h-80px">
                <img  alt="..."
                     src="{{ get_language_icon($language->icon) }}">
            </div>
        @else
            <div class="fileinput-new img-thumbnail lf-w-120px lf-h-80px">
                <i class="fa fa-image fa-5x"></i>
            </div>
        @endif
            <div class="fileinput-preview fileinput-exists img-thumbnail lf-w-120px lf-h-80px"></div>
        <div>
            <span id="button-group" class="btn btn-sm btn-outline-secondary btn-file">
                <span class="fileinput-new">{{ __('Select Icon') }}</span>
                <span class="fileinput-exists">{{ __('Change') }}</span>
                    {{ Form::file('icon', ['id' => 'icon','class' => form_validation($errors, 'icon')]) }}
            </span>

            <a href="#" id="remove" class="btn btn-sm btn-outline-danger fileinput-exists"
               data-dismiss="fileinput">{{ __('Remove') }}</a>
            <span class="invalid-feedback" data-name="icon">{{ $errors->first('icon') }}</span>
        </div>
    </div>
</div>

@isset($language)
{{--status--}}
    <div class="form-group">
        {{ Form::label('is_active', __('Status')) }}
        <div class="lf-select">
        {{ Form::select('is_active', active_status(), null, ['class' => form_validation($errors, 'is_active', 'lf-toggle-bg-input lf-toggle-border-color')]) }}
        </div>
        <span class="invalid-feedback" data-name="is_active">{{ $errors->first('is_active') }}</span>
    </div>
@endisset

<div class="form-group">
    {{ Form::submit($buttonText,  ['class' => 'btn btn-info form-btn form-submission-button']) }}
    {{ Form::button('<i class="fa fa-undo"></i>',['class'=>'btn btn-danger reset-button form-btn']) }}
</div>
