<div class="mt-4">
    {{ Form::open(['route' => ['tickets.comment.store',$ticket->id], 'id' => 'ticketCommentForm', 'files' => true]) }}
    <div class="form-group">
        {{ Form::textarea('content', null, ['class' => form_validation($errors, 'content', 'lf-toggle-border-color'),'rows'=>'3',
        'placeholder'=>'Type your message here...']) }}
        <span class="invalid-feedback my-1" data-name="content">{{ $errors->first('content') }}</span>
        <div class="form-group mt-2">
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
                <a href="#" class="close fileinput-exists font-size-16 pt-1" data-dismiss="fileinput"
                   style="float: none">&times;</a>
            </div>
            <span class="invalid-feedback my-1" data-name="attachment">{{ $errors->first('attachment') }}</span>
        </div>
    </div>
    {{ Form::submit('Submit',  ['class' => 'btn bg-info text-light px-5 form-submission-button']) }}
    {{ Form::close() }}
</div>
