<a href="#" class="modal-close" data-dismiss="modal"><em class="ti ti-close"></em></a>

<div class="popup-body">
    <h2 class="popup-title">Buy {{ strtoupper(token_symbol()) }} Tokens</h2>
    <div class="tranx-payment-details">
        <form action="{{ route('user.ajax.token.access') }}" method="POST" id="token_payment">
            @csrf
            <input type="hidden" name="currency" id="pay_currency" value="{{ $currency }}">
            <input type="hidden" name="req_type" id="pay_currency" value="{{ $get }}">
            
            <div class="gaps-1x"></div>
            <div class="payment-input">
                <input name="token_amount" placeholder="Enter number of token" class="input-bordered token-number" type="number" id="token-number" value="" min="{{ active_stage()->min_purchase }}" max="{{ active_stage()->max_purchase }}"><span class="payment-get-cur payment-cal-cur">{{ token_symbol() }}</span>
            </div>
            <div class="gaps-1x"></div>
            <input type="hidden" class="input-checkbox" id="agree" name="agree" value="1">
            <div class="gaps-2x"></div>
            <button type="submit" class="btn  btn-alt btn-primary payment-btn">Proceed<em class="ti-arrow-right"></em></button>   
            <div class="gaps-3x"></div>
        </form>
    </div>
</div>


<script type="text/javascript">

    (function($) {
        var $_p_form = $('form#token_payment');
        if ($_p_form.length > 0) {
            purchase_form_submit($_p_form);
        }

    })(jQuery);
</script>