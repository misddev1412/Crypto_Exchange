{{--address--}}
<div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
    <label for="'address"
           class="control-label required">{{ __('Address') }}</label>
    <div>
        {{ Form::text('address',  old('address', null), ['class'=>'form-control lf-toggle-bg-input lf-toggle-border-color', 'id' => 'address', 'placeholder' => __('ex: address')]) }}
        <span class="invalid-feedback" data-name="address">{{ $errors->first('address') }}</span>
    </div>
</div>
