@component('mail::message')
# {{ __('Dear') }}, {{ $withdrawal->user->profile->full_name }}

{{ __('You have requested to withdraw :amount :coin from account :email. Please click on the Confirm Withdrawal button below to complete the withdrawal.',['amount' => $withdrawal->amount, 'coin' => $withdrawal->symbol, 'email' => $withdrawal->user->email]) }}

**{{ __('Your withdrawal request details are:') }}**

**{{ __('Request ID:') }}** {{ $withdrawal->id }}<br>
**{{ __('Wallet:') }}** {{ $withdrawal->symbol }}<br>
**{{ __('Amount:') }}** {{ $withdrawal->amount }}<br>
**{{ __('Address:') }}** {{ $withdrawal->address }}<br>
**{{ __('Date:') }}** {{ $withdrawal->created_at }}<br>

<p style="text-align: center; font-weight: bold;margin-top: 30px">{{ __("This link will expire in :min minutes.",['min' => settings('withdrawal_confirmation_link_expire_in', 30)]) }}</p>
@component('mail::button', ['url' => url()->temporarySignedRoute('user.wallets.withdrawals.confirmation',now()->addMinutes(settings('withdrawal_confirmation_link_expire_in', 30)),['wallet'=>$withdrawal->symbol, 'withdrawal' => $withdrawal->id])])
        {{ __('Confirm Withdrawal') }}
@endcomponent

{{ __("Please contact the support team with the Request ID: :id for any further assistance.", ['id' => $withdrawal->id]) }}

{{ __('Thanks a lot for being with us,') }}<br>
{{ company_name() }}
@endcomponent
