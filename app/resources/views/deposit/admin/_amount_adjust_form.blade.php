<div class="modal lf-toggle-bg-card" tabindex="-1" role="dialog" id="deposit-amount-adjust-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            {{ Form::open(['route'=>['admin.adjust.bank-deposits', $deposit], 'method' => 'post', 'class'=>'form-horizontal validator', 'id' => 'adjust-amount-form']) }}
            <div class="modal-header">
                <h5 class="modal-title text-dark">{{ __('Adjust Amount') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert" id="form-message"></div>
                {{--amount--}}
                <div class="form-group">
                    <label for="amount" class="control-label required text-dark bg-">{{ __('Amount') }}</label>
                    <div>
                        {{ Form::text('amount',  $deposit->amount, ['class'=>form_validation($errors, 'amount', 'lf-light-input'), 'id' => 'amount', 'placeholder' => __('ex: 20.99')]) }}
                        <span class="invalid-feedback" data-name="amount">{{ $errors->first('amount') }}</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" type="button">{{ __('Close') }}</button>
                <button class="btn btn-primary form-submission-button" type="submit">{{ __('Save changes') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
