<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <h4 class="text-green">{{ __('Google Authentication is Enabled.') }}</h4>
        {!! Form::open(['route'=>['profile.google-2fa.destroy'], 'class'=>'form-horizontal validator', 'id' => 'googleTwoFaForm']) !!}
        @method('put')
        <p class="text-muted text-justify small">
            {{ __('If you want to turn off Google 2FA, input your account password and the six-digit code provided by the Google Authenticator app below, then submit.') }}
        </p>
        {{--password--}}
        <div class="form-group">
            <label for="password" class="control-label required">{{ __('Current Password') }}</label>
            {{ Form::password('password', ['class'=> form_validation($errors, 'password'),
            'placeholder' => __('Enter current password'), 'id' => 'password']) }}
            <span class="invalid-feedback" data-name="password">{{ $errors->first('password') }}</span>
        </div>

        {{--google app code--}}
        <div class="form-group">
            <label for="google_app_code" class="control-label required">{{ __('Enter G2FA App Code') }}</label>
            {{ Form::text('google_app_code', null, ['class'=> form_validation($errors, 'google_app_code'), 'placeholder' => __('Enter G2FA App Code'), 'id' => 'google_app_code']) }}
            <p class="help-block small text-justify">
                {{ __('Important: When you disable 2FA, The 16 digit code will no longer be valid.') }}
            </p>
            <span class="invalid-feedback" data-name="google_app_code">{{ $errors->first('google_app_code') }}</span>
        </div>

        {{--back_up--}}
        <div class="form-group">
            <div class="lf-checkbox">
                {{ Form::checkbox('back_up', ACTIVE, INACTIVE,['id' => 'back_up']) }}
                <label for="back_up" class="text-muted">{{ __('Yes, I understand.') }}
                </label>
                <span class="invalid-feedback">{{ $errors->first('back_up') }}</span>
            </div>
        </div>

        {{--submit button--}}
        <div class="form-group">
            {{ Form::submit(__('Disable Google Authentication'), ['class'=>'btn btn-info form-submission-button btn-block']) }}
        </div>
        {!! Form::close() !!}
    </div>
</div>
