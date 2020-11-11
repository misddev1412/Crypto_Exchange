@php
$data = json_decode($transaction->extra);
$j = json_decode($transaction->checked_by);
$transaction_cur = $transaction->currency;
$bank = get_pm($transaction->payment_method);
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
                        <p class="lead-lg text-primary">{!! __('Your Order no. :orderid has been placed & waiting for payment.', ['orderid' => '<strong>'.$transaction->tnx_id.'</strong>' ]) !!}</p>
                        <p>{!! __('To receiving :token :symbol token, please make your payment of :amount :currency through bank to the below bank address. The token balance will appear in your account only after your transaction gets approved by our team.', ['amount' => '<strong class="text-primary">'.to_num($transaction->amount, 'max').'</strong>', 'currency' => '<strong class="text-primary">'.strtoupper($transaction->currency).'</strong>', 'token' => '<strong><span class="token-total">'.$transaction->total_tokens.'<span></strong>', 'symbol' => '<strong class="text-primary">'.token('symbol').'</strong>']) !!}</p>
                        <div class="gaps-1x"></div>
                        <form action="{{ route('payment.bank.update') }}" method="POST" id="payment-confirm" class="validate" autocomplete="off">
                            @csrf
                            <input type="hidden" name="trnx_id" value="{{ $transaction->id }}">
                            <h5 class="text-head mgb-0-5x"><strong>{{__('Bank Details for Payment')}}</strong></h5>
                            <table class="table table-flat">
                                <thead>
                                    <th colspan="2"></th>
                                </thead>
                                <tbody>
                                    @if(isset($bank->bank_account_name) && !empty($bank->bank_account_name))
                                    <tr>
                                        <th>{{__('Account Name')}}</th>
                                        <td>{{ $bank->bank_account_name }}</td>
                                    </tr>
                                    @endif
                                    @if(isset($bank->bank_account_number) && !empty($bank->bank_account_number))
                                    <tr>
                                        <th>{{__('Account Number')}}</th>
                                        <td>{{ $bank->bank_account_number }}</td>
                                    </tr>
                                    @endif
                                    @if(isset($bank->bank_holder_address) && !empty($bank->bank_holder_address))
                                    <tr>
                                        <th>{{__('Account Holder Address')}}</th>
                                        <td>{{ $bank->bank_holder_address }}</td>
                                    </tr>
                                    @endif
                                    @if(isset($bank->bank_name) && !empty($bank->bank_name))
                                    <tr>
                                        <th>{{__('Bank Name')}}</th>
                                        <td>{{ $bank->bank_name }}</td>
                                    </tr>
                                    @endif
                                    @if(isset($bank->bank_address) && !empty($bank->bank_address))
                                    <tr>
                                        <th>{{__('Bank Address')}}</th>
                                        <td>{{ $bank->bank_address }}</td>
                                    </tr>
                                    @endif
                                    @if(isset($bank->routing_number) && !empty($bank->routing_number))
                                    <tr>
                                        <th>{{__('Routing Number')}}</th>
                                        <td>{{ $bank->routing_number }}</td>
                                    </tr>
                                    @endif
                                    @if(isset($bank->iban) && !empty($bank->iban))
                                    <tr>
                                        <th>{{__('IBAN')}}</th>
                                        <td>{{ $bank->iban }}</td>
                                    </tr>
                                    @endif
                                    @if(isset($bank->swift_bic) && !empty($bank->swift_bic))
                                    <tr>
                                        <th>{{__('Swift/BIC')}}</th>
                                        <td>{{ $bank->swift_bic }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            <button type="submit" name="action" value="cancel" class="btn btn-cancel btn-danger-alt payment-cancel-btn payment-btn">{{__('Cancel Order')}}</button>
                            <div class="gaps-2-5x"></div>
                            <div class="note note-info note-plane">
                                <em class="fas fa-info-circle"></em> 
                                <p>{!! __('Use this transaction id (#:orderid) as reference. Make your payment within 24 hours, If we will not received your payment within 24 hours, then we will cancel the transaction.', ['orderid' => '<strong>'.$transaction->tnx_id.'</strong>' ]) !!}</p>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="content-area popup-content">
                        @include('layouts.token-details', ['transaction' => $transaction, 'details' => true])
                    </div>
                @endif
            @else
                <div class="content-area text-center popup-content">
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