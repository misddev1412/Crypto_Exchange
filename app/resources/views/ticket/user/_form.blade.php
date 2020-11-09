{{--title--}}
<div class="form-group">
    <label for="title" class="required">{{ __('Heading') }}</label>
    {{ Form::text('title', null, ['class' => form_validation($errors, 'title'), 'id' => 'title']) }}
    <span class="invalid-feedback" data-name="title">{{ $errors->first('title') }}</span>
</div>

{{--content--}}
<div class="form-group">
    <label for="content_textarea" class="required">{{ __('Description') }}</label>
    {{ Form::textarea('content', null, ['class' => form_validation($errors, 'content'), 'rows'=>3, 'id' => 'content_textarea']) }}
    <span class="invalid-feedback" data-name="content">{{ $errors->first('content') }}</span>
</div>

{{--Previous Ref Id--}}
<div class="form-group">
    <label for="previous_id" class="required">{{ __('Reference Ticket ID') }}</label>
    {{ Form::text('previous_id', null, ['class' => form_validation($errors, 'previous_id'), 'id' => 'previous_id']) }}
    <span class="invalid-feedback">{{ $errors->first('previous_id') }}</span>
</div>

{{--file--}}
<div class="form-group">
    <div class="fileinput fileinput-new" data-provides="fileinput">
        <span class="btn btn-file lf-toggle-text-color border lf-toggle-border-color">
            <span class="fileinput-new">
                <i class="fa fa-paperclip fa-rotate-90"></i>
                {{ __('Attachment') }}
            </span>
            <span class="fileinput-exists">{{ __('Change') }}</span>
            <input type="file" name="attachment">
        </span>
        <span class="fileinput-filename"></span>
        <a href="#" class="close fileinput-exists font-size-16 pt-1" data-dismiss="fileinput" style="float: none">&times;</a>
    </div>

    <span class="invalid-feedback" data-name="attachment">{{ $errors->first('attachment') }}</span>
</div>

{{--submit button--}}
<div class="form-group">
    {{ Form::submit($buttonText.' Ticket',['class'=>'btn btn-info form-submission-button']) }}
</div>
