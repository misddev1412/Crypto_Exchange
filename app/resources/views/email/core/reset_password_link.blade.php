@component('mail::message')
# {{ __('Hello') }}, {{ $user->profile->full_name }}

{{ __("Please click on the following link to reset password -" ) }}

@component('mail::button', ['url' => url()->temporarySignedRoute('reset-password.index', now()->addMinutes(30), ['user' => $user->id])])
    {{ __('Reset Password') }}
@endcomponent

{{ __('Thanks a lot for being with us,') }}<br>
{{ company_name() }}
@endcomponent
