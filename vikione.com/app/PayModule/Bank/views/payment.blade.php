@php
$bank = get_pm($transaction->payment_method);
@endphp
<a href="#" class="modal-close" data-dismiss="modal"><em class="ti ti-close"></em></a>
<div class="popup-body">
    <h4 class="popup-title">{{__('Confirmation Your Payment')}}</h4>
    <div class="popup-content">
        <p class="lead-lg text-primary">{!! __('Your Order no. :orderid has been placed successfully.', ['orderid' => '<strong>'.$transaction->tnx_id.'</strong>' ]) !!}</p>
        <p>{!! __('Please make your payment of :amount :currency through bank to the below bank address. The token balance will appear in your account only after your transaction gets approved by our team.', ['amount' => '<strong class="text-primary">'.to_num($transaction->amount, 'max').'</strong>', 'currency' => '<strong class="text-primary">'.strtoupper($transaction->currency).'</strong>']) !!}</p>
        <div class="gaps-1x"></div>
        <form action="{{ route('payment.bank.update') }}" method="POST" id="payment-confirm" class="validate-modern" autocomplete="off">
            @csrf
            <input type="hidden" name="trnx_id" value="{{ $transaction->id }}">
            <h5 class="text-head mgb-0-5x"><strong>{{__('Bank Details for Payment')}}</strong></h5>
            <table class="table table-flat">
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
            <div class="gaps-0-5x"></div>
            <ul class="d-flex flex-wrap align-items-center guttar-30px">
                <li><a class="btn btn-auto btn-sm btn-primary" href="{{ route('user.transactions') }}">{{__('View Transaction')}}</a></li> 
                <li class="pdt-1x pdb-1x"><button type="submit" name="action" value="cancel" class="btn btn-cancel btn-danger-alt payment-cancel-btn payment-btn btn-simple">{{__('Cancel Order')}}</button></li>
            </ul>
            <div class="gaps-2-5x"></div>
            <div class="note note-info note-plane">
                <em class="fas fa-info-circle"></em> 
                <p>{!! __('Use this transaction id (#:orderid) as reference. Make your payment within 24 hours, If we will not received your payment within 24 hours, then we will cancel the transaction.', ['orderid' => '<strong>'.$transaction->tnx_id.'</strong>' ]) !!}</p>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    (function($) {
        var $_p_form = $('form#payment-confirm');
        if ($_p_form.length > 0) { purchase_form_submit($_p_form); }
        $('.close-modal, .modal-close').on('click', function(e){
            e.preventDefault(); var $link = $(this).attr('href'); $(this).parents('.modal').modal('hide'); window.location.reload();
        });
    })(jQuery);
</script>