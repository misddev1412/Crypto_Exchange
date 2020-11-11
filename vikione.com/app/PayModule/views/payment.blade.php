@php 
$pm_check = (!empty($methods) ? true : false);
$dot_1 =  '.'; $dot_2 = '';
if ($data->total_bonus > 0) {
    $dot_1 =  ''; $dot_2 = '.';
}
@endphp
<a href="#" class="modal-close" data-dismiss="modal"><em class="ti ti-close"></em></a>
<div class="popup-body">
    <div class="popup-content">
        <form class="validate-modern" action="{{ route('user.ajax.payment') }}" method="POST" id="online_payment">
            @csrf
            <input type="hidden" name="pp_token" id="token_amount" value="{{ $data->token }}">
            <input type="hidden" name="pp_currency" id="pay_currency" value="{{ $data->currency }}">
            <h4 class="popup-title">{{ __('Payment Process')}}</h4>
            <p class="lead">{!! ($data->total_bonus > 0) ? __('Please make payment of :amount to receive :token_amount token including bonus :token_bonus token.', ['amount' => '<strong>'.to_num($data->amount, 'max').' <span class="pay-currency ucap">'.$data->currency.'</span></strong>', 'token_amount'=> '<strong><span class="token-total">'.$data->total_tokens.' '.token('symbol').'</span></strong>', 'token_bonus'=> '<strong><span class="token-bonuses">'.$data->total_bonus.' '.token('symbol').'</span></strong>']) : __('Please make payment of :amount to receive :token_amount token.', ['amount' => '<strong>'.to_num($data->amount, 'max').' <span class="pay-currency ucap">'.$data->currency.'</span></strong>', 'token_amount'=> '<strong><span class="token-total">'.$data->total_tokens.' '.token('symbol').'</span></strong>']) !!}
            </p> 
            @if($pm_check)
                <p>{{__('You can choose any of following payment method to make your payment. The token balance will appear in your account after successful payment.')}}</p>
                <h5 class="mgt-1-5x font-mid">{{__('Select payment method:')}}</h5>
                <ul class="pay-list guttar-12px">
                    @foreach($methods as $method)
                        {{ $method }}
                    @endforeach
                </ul>
                <p class="text-light font-italic mgb-1-5x"><small>* {{__('Payment gateway may charge you a processing fees.')}}</small></p>
                <div class="pdb-0-5x">
                    <div class="input-item text-left">
                        <input type="checkbox" data-msg-required="{{ __("You should accept our terms and policy.") }}" class="input-checkbox input-checkbox-md" id="agree-terms" name="agree" required>
                        <label for="agree-terms">{{ __('I hereby agree to the token purchase agreement and token sale term.') }}</label>
                    </div>
                </div>
                <ul class="d-flex flex-wrap align-items-center guttar-30px">
                    <li><button type="submit" class="btn btn-alt btn-primary payment-btn"> {{ __('Buy Token Now') }} <em class="ti ti-arrow-right mgl-2x"></em></button></li>
                </ul>
                <div class="gaps-3x"></div>
                <div class="note note-plane note-light">
                    <em class="fas fa-info-circle"></em>
                    <p class="text-light">{{__('Our payment address will appear or redirect you for payment after the order is placed.')}}</p>
                </div>
            @else
                <div class="gaps-4x"></div>
                <div class="alert alert-danger text-center">{{ __('Sorry! There is no payment method available for this currency. Please choose another currency or contact our support team.') }}</div>
                <div class="gaps-5x"></div>
            @endif
            
        </form>
    </div>
</div>
<script type="text/javascript">
    (function($) {
        var $_p_form = $('form#online_payment');
        if ($_p_form.length > 0) { purchase_form_submit($_p_form); }
    })(jQuery);
</script>