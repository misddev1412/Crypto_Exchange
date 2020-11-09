@component('mail::message')
# {{ __('Dear') }}, {{ $withdrawal->user->profile->full_name }}

{{ __('We approved your request to withdraw funds from :company. The funds will be deposited in your bank account within 4 business days.', ['company' => company_name()]) }}

**{{ __('Withdrawal details:') }}**

**{{ __('Amount:') }}** {{ $withdrawal->amount }} {{ $withdrawal->symbol }}<br>
**{{ __('Transaction ID:') }}** {{ $withdrawal->txn_id }}<br>

{{ __("Please contact the support team with the Request ID: :id for any further assistance.", ['id' => $withdrawal->txn_id]) }}

{{ __('Thanks a lot for being with us,') }}<br>
{{ company_name() }}
@endcomponent
