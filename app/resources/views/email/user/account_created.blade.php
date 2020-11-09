@component('mail::message')
# {{ __('Hello') }}, {{ $profile->full_name }}


{{ __('Welcome to :companyName. Please use the following credentials to log in your account on our platform :', ['companyName' => company_name()]) }}

<ul style="list-style: none">
<li>{{ __('Email') }} : {{ $profile->user->email }}</li>
<li>{{ __('Username') }} : {{ $profile->user->username }}</li>
<li>{{ __('Password') }} : {{ $password }}</li>
</ul>

{{ __('The password has been generated automatically. We are recommending to change your password after login your account.') }}

@if(!$profile->user-> is_email_verified)
{{ __('Click the following link to verify your account.') }}


@component('mail::button', ['url' => url()->temporarySignedRoute('account.verification',now()->addMinutes(30),['user_id'=>$profile->user_id])])
{{ __('Verify') }}
@endcomponent
@else
{{ __('Click the following link to login your account.') }}


@component('mail::button', ['url' => url('login')])
    {{ __('Login') }}
@endcomponent
@endif

{{ __('Thanks a lot for being with us,') }}<br>
{{ company_name() }}
@endcomponent
