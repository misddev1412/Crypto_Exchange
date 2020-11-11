@php
$data = json_decode($transaction->extra);

if($transaction->payment_method == 'paypal'){
    $pay_url = (isset($data->url) ? $data->url : route('user.token'));
}else{
    $pay_url = route('user.token');
}
@endphp
<div class="modal fade" id="transaction-details" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            @if($transaction)
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            @endif

            <div class="popup-body">
            @if($transaction)
                @if($transaction->status=='pending' || $transaction->status == 'onhold')
                    <h4 class="popup-title">{{__('Confirmation Your Payment')}}</h4>
                    <div class="content-area popup-content">
                        <form action="{{ route('payment.bank.update') }}" method="POST" id="payment-confirm" class="validate" autocomplete="off">
                            @csrf
                            <input type="hidden" name="trnx_id" value="{{ $transaction->id }}">
                            <p class="lead-lg text-primary">{!! __('Your Order no. :orderid has been placed & waiting for payment.', ['orderid' => '<strong>'.$transaction->tnx_id.'</strong>' ]) !!}</p>
                            <p class="lead">{!! __('To receiving :token :symbol token, please make your payment of :amount :currency through :gateway. The token balance will appear in your account once we received your payment.', ['amount' => '<strong class="text-primary">'.to_num($transaction->amount, 'max').'</strong>', 'currency' => '<strong class="text-primary">'.strtoupper($transaction->currency).'</strong>', 'token' => '<strong><span class="token-total">'.$transaction->total_tokens.'<span></strong>', 'symbol' => '<strong class="text-primary">'.token('symbol').'</strong>', 'gateway' => '<strong>'.ucfirst($transaction->payment_method.'</strong>')]) !!}</p>
                            <div class="gaps-2x"></div>
                            <ul class="d-flex flex-wrap align-items-center guttar-30px">
                                <li><a href="{{ $pay_url }}" target="_blank" class="btn btn-info">{{ __('Make Payment') }}</a></li> 
                                <li class="pdt-1x pdb-1x"><button type="submit" name="action" value="cancel" class="btn btn-cancel btn-danger-alt payment-cancel-btn payment-btn btn-simple">{{__('Cancel Order')}}</button></li>
                            </ul>
                        </form>
                    </div>
                @else
                    <div class="content-area popup-content">
                        @include('layouts.token-details', ['transaction' => $transaction, 'details' => true])
                    </div>
                @endif
            @else
            <div class="content-area popup-content text-center">
                <div class="status status-error">
                    <em class="ti ti-alert"></em>
                </div>
                <h3>{{__('Oops!!!')}}</h3>
                <p>{!! __('Sorry, seems there is an issues occurred and we couldn’t process your request. Please contact us with your order no. :orderid, if you continue to having the issues.', ['orderid' => '<strong>'.$transaction->tnx_id.'</strong>']) !!}</p>
                <div class="gaps-2x"></div>
                <a href="#" data-dismiss="modal" data-toggle="modal" class="btn btn-light-alt">{{__('Close')}}</a>
                <div class="gaps-3x"></div>
            </div>
            @endif

            </div>
        </div>
    </div>
    <script type="text/javascript">
        (function($) {
            var $_p_form = $('form#payment-confirm');
            if ($_p_form.length > 0) {
                purchase_form_submit($_p_form);
            }
        })(jQuery);
    </script>
</div>