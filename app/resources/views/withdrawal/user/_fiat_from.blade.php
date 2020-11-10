{{--api--}}
<div class="form-group {{ $errors->has('api') ? 'has-error' : '' }}">
    <label for="api"
           class="control-label required">{{ __('Withdrawal with') }}</label>
    <div>
        {{ Form::select('api', $apis, old('api', null), ['class'=>'form-control lf-toggle-bg-input lf-toggle-border-color', 'id' => 'api', 'placeholder' => __('Select API'), '@change' => 'changePaymentMethod']) }}

        <span class="invalid-feedback" data-name="api">{{ $errors->first('api') }}</span>
    </div>
</div>

<div v-if="showBank">
    {{--bank_account_id--}}
    <div class="form-group {{ $errors->has('bank_account_id') ? 'has-error' : '' }}">
        <label for="bank_account_id"
               class="control-label required">{{ __('Select a Bank') }}</label>
        <div>
            @forelse($bankAccounts as $bankAccountId => $bankAccountName)
                <div class="lf-radio">
                    {{ Form::radio('bank_account_id', $bankAccountId, old('bank_account_id', null), ['id' => 'bank-' . $bankAccountId, 'class' => 'form-check-input']) }}

                    <label class="form-check-label"
                           for="bank-{{ $bankAccountId }}">{{ $bankAccountName }}</label>
                </div>
            @empty
                <div class="text-warning">
                    {{ __('No Bank Account is available.') }} <a
                        href="{{ route('bank-accounts.create') }}" class="text-info">{{ __('Please add bank.') }}</a>
                </div>
            @endforelse

            <span class="invalid-feedback" data-name="bank_account_id">{{ $errors->first('bank_account_id') }}</span>
        </div>
    </div>
</div>
<div v-else>
    {{--address--}}
    <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
        <label for="address"
               class="control-label required">{{ __('Address') }}</label>
        <div>
            {{ Form::text('address',  old('address', null), ['class'=>'form-control lf-toggle-bg-input lf-toggle-border-color', 'id' => 'address', 'placeholder' => __('ex: address')]) }}
            <span class="invalid-feedback" data-name="address">{{ $errors->first('address') }}</span>
        </div>
    </div>
</div>
