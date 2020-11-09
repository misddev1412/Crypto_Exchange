@component('mail::message')
# {{ __('Hello') }}, {{ $profile->full_name }}

{{ __('Thank you for registering on :companyName.',['companyName' => config('app.name')]) }}

{{ __('You are one step away. Click the following link to verify your account.') }}

@component('mail::button', ['url' => url()->temporarySignedRoute('account.verification',now()->addMinutes(30),['user_id'=>$profile->user_id])])
{{ __('Verify') }}
@endcomponent

{{ __('Thanks a lot for being with us,') }}<br>
{{ company_name() }}
@endcomponent
