<div class="alert alert-warning text-center mb-4">
    <h4 class="text-warning">{{ __('Google Authentication is Disabled.') }}</h4>
</div>
<div class="row">
    <div class="col-lg-6 text-center mb-md-4">
        <figure class="qr-code-group m-auto">
            {{ view_html($inlineUrl) }}
            <div class="input-group copy-group">
                <input type="text" class="form-control form-control-sm" id="link" readonly value="{{ $secretKey }}">
                <button class="btn btn-sm btn-dark" title="{{ __('Copy Link') }}" data-toggle="tooltip" type="button" onclick="copyLink()"><i class="fa fa-clipboard text-aqua"></i></button>
            </div>
            <figcaption class="help-block small text-justify my-2">
                {{ __('NOTE: This code changes each time you enable 2FA. If you disable 2FA this code will no longer be valid.') }}
            </figcaption>
        </figure>
    </div>
    <div class="col-lg-6">
        {!! Form::open(['route'=>['profile.google-2fa.store', $secretKey], 'class'=>'validator', 'id' => 'googleTwoFaForm']) !!}
        @method('put')
        {{--email--}}
        <div class="form-group">
            <label class="control-label">{{ __('Email') }}</label>
            <input class="form-control" readonly value="{{ $user->email }}">
        </div>

        {{--password--}}
        <div class="form-group">
            <label for="password" class="control-label">{{ __('Current Password') }}</label>
            {{ Form::password('password', ['class'=> form_validation($errors, 'password'),
            'placeholder' => __('Enter current password'), 'id' => 'password']) }}
            <span class="invalid-feedback" data-name="password">{{ $errors->first('password') }}</span>
        </div>

        {{--google app code--}}
        <div class="form-group">
            <label for="google_app_code"
                  class="control-label">{{ __('Enter G2FA App Code') }}</label>
            {{ Form::text('google_app_code', null, ['class'=> form_validation($errors, 'google_app_code',), 'placeholder' => __('Enter G2FA App Code'), 'id' => 'google_app_code']) }}
            <span class="invalid-feedback" data-name="google_app_code">{{ $errors->first('google_app_code') }}</span>
        </div>

        <p class="text-justify text-muted small">
            {{ __('Before turning on 2FA, write down or print a copy of your 16-digit key and put it in a safe place. If your phone gets lost, stolen, or erased, you will need this key to get back into your account!') }}
        </p>

        {{--back_up--}}
        <div class="lf-checkbox">
                {{ Form::checkbox('back_up', ACTIVE, INACTIVE,['id' => 'back_up']) }}
            <label for="back_up">
                {{ __('I have backed up my 16-digit key.') }}
            </label>
            <span class="invalid-feedback d-inline-block mb-4">{{ $errors->first('back_up') }}</span>
        </div>

        {{--submit button--}}
        <div class="form-group">
           {{ Form::submit(__('Set Google Authentication'), ['class'=>'btn btn-info form-submission-button btn-block']) }}
       </div>
        {!! Form::close() !!}
    </div>
</div>
