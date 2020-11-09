{{--title--}}
<div class="form-group mb-3">
    <label for="title" class="control-label required">{{ __('Post Title') }}</label>
    {{ Form::text('title', null, ['class'=> form_validation($errors, 'title'),
    'id' => 'title']) }}
    <span class="invalid-feedback" data-name="title">{{ $errors->first('title') }}</span>
</div>

{{--Category post--}}
<div class="form-group mb-3">
    <label for="category_slug" class="control-label required">{{ __('Post Category') }}</label>
    {{ Form::select('category_slug', $postCategories, null,['class' => form_validation($errors,
    'category_slug'),
    'id' => 'category_slug', 'placeholder' => __('Select Post Category')]) }}
    <span class="invalid-feedback">{{ $errors->first('category_slug') }}</span>
</div>

{{--is_featured--}}
<div class="form-group mb-3">
    <label for="is_featured" class="control-label required">{{ __('Featured Status') }}</label>
    <div>
        <div class="lf-switch">
            {{ Form::radio('is_featured', ACTIVE, true, ['id' => 'is_featured-active', 'class' => 'lf-switch-input']) }}
            <label for="is_featured-active" class="lf-switch-label">{{ __('Active') }}</label>
            {{ Form::radio('is_featured', INACTIVE, false, ['id' => 'is_featured-inactive', 'class' => 'lf-switch-input']) }}
            <label for="is_featured-inactive" class="lf-switch-label">{{ __('Inactive') }}</label>
        </div>
        <span class="invalid-feedback">{{ $errors->first('is_featured') }}</span>
    </div>
</div>

{{--editor_content--}}
<div class="form-group mb-3">
    <label for="editor_content" class="control-label required">{{ __('Content') }}</label>
    {{ Form::textarea('editor_content',  old('editor_content', $post->content ?? null), ['class'=> form_validation($errors, 'editor_content'), 'id' => 'editor_content']) }}

    <span class="invalid-feedback" data-name="editor_content">{{ $errors->first('editor_content') }}</span>
</div>

{{--featured_image--}}
<div class="form-group my-3">
    <div>
        <div class="fileinput fileinput-new" data-provides="fileinput">
            <div class="fileinput-new thumbnail border">
                <div class="d-flex h-100 align-self-center">
                    <div class="m-auto">
                        @if(isset($post) && !is_null($post->featured_image))
                            <img src="{{ get_featured_image($post->featured_image) }}" alt="{{ __('Featured Image') }}" class="img-fluid">
                        @else
                            <div>
                                <img src="{{ get_featured_image() }}" alt="{{ __('Featured Image') }}" class="img-fluid">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="fileinput-preview fileinput-exists thumbnail"></div>
            <div class="mt-2">
                <span class="btn btn-sm btn-success btn-file">
                    <span class="fileinput-new">{{ __('Select Featured Image') }}</span>
                    <span class="fileinput-exists">{{ __('Change') }}</span>
                    {{ Form::file('featured_image', ['class' => 'btn btn-default border','id' => 'featured_image']) }}
                </span>
                <a href="javascript:" class="btn btn-sm btn-danger fileinput-exists"
                   data-dismiss="fileinput">{{ __('Remove') }}</a>
            </div>
        </div>
        <p class="help-block text-muted">{{ __('Upload coin featured_image 1280x768 and less than or equal 2MB.') }}</p>
        <span class="invalid-feedback" data-name="featured_image">{{ $errors->first('featured_image') }}</span>
    </div>
</div>

{{--is_published--}}
<div class="form-group mb-3">
    <label for="is_published" class="control-label required">{{ __('Publish Status') }}</label>
    <div>
        <div class="lf-switch">
            {{ Form::radio('is_published', ACTIVE, true, ['id' => 'is_published-active', 'class' => 'lf-switch-input']) }}
            <label for="is_published-active" class="lf-switch-label">{{ __('Published') }}</label>
            {{ Form::radio('is_published', INACTIVE, false, ['id' => 'is_published-inactive', 'class' => 'lf-switch-input']) }}
            <label for="is_published-inactive" class="lf-switch-label">{{ __('Unpublished') }}</label>
        </div>
        <span class="invalid-feedback" data-name="is_published">{{ $errors->first('is_published') }}</span>
    </div>
</div>

{{--submit buttn--}}
<div class="form-group">
    {{ Form::submit(__('Save'),['class'=>'btn btn-info form-submission-button']) }}
    {{ Form::button('<i class="fa fa-undo"></i>',['class'=>'btn btn-danger', 'type' => 'reset']) }}
</div>
