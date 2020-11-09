{{--title--}}
<div class="form-group mb-3">
    <label for="title"
           class="control-label required">{{ __('Title') }}</label>
    {{ Form::text('title',  null, ['class'=> form_validation($errors, 'title'), 'id' => 'title']) }}
    <span class="invalid-feedback" data-name="title">{{ $errors->first('title') }}</span>
</div>

{{--meta_description--}}
<div class="form-group mb-3">
    <label for="meta_description"
           class="control-label">{{ __('Meta Description') }}</label>
    {{ Form::text('meta_description',  null, ['class'=> form_validation($errors, 'meta_description'), 'id' => 'meta_description']) }}
    <span class="invalid-feedback" data-name="meta_description">{{ $errors->first('meta_description') }}</span>
</div>

{{--meta_keywords--}}
<div class="form-group mb-3">
    <label for="meta_keywords"
           class="control-label">{{ __('Meta Keys') }}</label>

    <select class="form-control meta_keywords"
            multiple="multiple"
            name="meta_keywords[]" id="meta_keywords">
        @foreach(old('meta_keywords', isset($page) ? $page->meta_keywords : []) as $metaKeyword)
            <option value="{{ $metaKeyword }}"
                    selected>{{ $metaKeyword }}</option>
        @endforeach
    </select>
    <span class="invalid-feedback" data-name="meta_keywords">{{ $errors->first('meta_keywords') }}</span>
</div>

{{--content--}}
<div class="form-group mb-3">
    <label for="editor_content"
           class="control-label required">{{ __('Content') }}</label>
    {{ Form::textarea('editor_content',  old('editor_content', isset($page) ? $page->content : null), ['class'=> form_validation($errors, 'editor_content'), 'id' => 'editor_content']) }}
    <span class="invalid-feedback" data-name="editor_content">{{ $errors->first('editor_content') }}</span>
</div>

{{--is_published--}}
<div class="form-group my-3">
    <label for="is_published" class="control-label required">{{ __('Publish Status') }}</label>
    <div>
        <div class="lf-switch">
            {{ Form::radio('is_published', ACTIVE, true, ['id' => 'is_published-active', 'class' => 'lf-switch-input']) }}
            <label for="is_published-active" class="lf-switch-label lf-switch-label-on">{{ __('Publish') }}</label>

            {{ Form::radio('is_published', INACTIVE, false, ['id' => 'is_published-inactive', 'class' => 'lf-switch-input']) }}
            <label for="is_published-inactive" class="lf-switch-label lf-switch-label-off">{{ __('Unpublished') }}</label>
        </div>
        <span class="invalid-feedback" data-name="is_published">{{ $errors->first('is_published') }}</span>
    </div>
</div>

{{--submit buttn--}}
<div class="form-group">
    {{ Form::submit(__('Save'),['class'=>'btn btn-info form-submission-button']) }}
    {{ Form::button('<i class="fa fa-undo"></i>',['class'=>'btn btn-danger', 'type' => 'reset']) }}
</div>
