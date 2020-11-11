<div class="modal fade" id="adjustment" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class=" sd modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-md">
                <h3 class="popup-title">Approve Transaction of {{$trnx->tnx_id }}</h3>

                <form action="javascript:void(0)" method="POST" autocomplete="off" id="adjust_token_cal">
                    @csrf
                    <input class="input-bordered" type="hidden" name="tnx_id" value="{{ $trnx->id}}">
                    <input class="input-bordered" type="hidden" name="req_type" value="approved">
                    <div class="row">
                        <div class="col-12">
                             <p>User requested to purchase <strong>{{$trnx->total_tokens.' '.token_symbol()}}</strong> Token and payment amount <strong>{{ to_num($trnx->amount, 'max').' '.strtoupper($trnx->currency) }}</strong>. Please update received amount accrodingly if you received less or more than payment amount.</p>
                             <div class="gaps-1-5x"></div>  
                         </div>
                         <div class="col-sm-6">
                            <div class="input-item input-with-label w-sm-60">
                                <label class="input-item-label">Received Amount <strong class="h5 text- text-info text-uppercase">({{$trnx->currency}})</strong></label>
                                <input class="input-bordered" required="" id="receive_amount" type="number" value="{{ to_num($trnx->amount, 'max') }}" name="receive_amount">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label w-sm-60">
                                <label class="input-item-label">Token to adjust</label>
                                <input class="input-bordered" type="number" name="total_tokens" id="total_tokens" value="{{ $trnx->total_tokens }}" readonly>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-item">
                                <input type="checkbox" disabled="" value="1" class="chk_adjust input-checkbox input-checkbox-md" id="token_amount_adjust">
                                <label for="token_amount_adjust">Check this to confirm adjusted token.</label>
                            </div>
                        </div>
                    </div>
                    <div class="gaps-1x"></div>
                    <button type="submit" class="btn btn-md btn-auto btn-primary tnx-action" data-type="approved" data-id="{{ $trnx->id }}"><em class="far fa-check-square"></em><span>Approve</span></button>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    (function($) {

    //adjust and calculate on field
    var adjust_form = $('#adjust_token_cal'), receive_amount = $('#receive_amount'), adjust_token = $('#total_tokens'), $chk = adjust_form.find('.chk_adjust'),
        rec_amount = adjust_form.find(receive_amount).val();
    $('#adjustment').find('.tnx-action').data('_amount',rec_amount);

    adjust_form.find(":input").bind('keyup change', function() {

        var rec_amount = adjust_form.find(receive_amount).val(),
        adj_token = adjust_form.find(adjust_token).val(),
        tnx_id = {!! $trnx->id !!},
        currency = '{!! $trnx->currency !!}',
        currency_rate = {!! $trnx->currency_rate !!},
        bonus_on_base = {!! $trnx->bonus_on_base !!},
        bonus_on_token = {!! $trnx->bonus_on_token !!},
        old_amount = {!! $trnx->total_tokens !!} - bonus_on_token -bonus_on_base,
        all_currency_rate = {!! $trnx->all_currency_rate !!};
        
        $_token = parseInt(rec_amount / currency_rate);
        _base_bonus = parseInt((bonus_on_base / old_amount )*$_token); 
        _token_bonus = parseInt((bonus_on_token / old_amount )*$_token); 
        $_adjusted = $_token+ _base_bonus + _token_bonus;
        adjust_token.val($_adjusted);

        if(rec_amount <=0){
            $chk.is(':checked')?$chk.click():'';
            $chk.attr('disabled', true);
        }
        else{
            $chk.removeAttr('disabled');
        }
        
        var chk_adjust = (adjust_form.find('.chk_adjust').is(':checked')) ? 1:0;

        $('#adjustment').find('.tnx-action').data('_b_bonus',_base_bonus).data('_t_bonus',_token_bonus).data('_adjusted',$_adjusted).data('_amount',rec_amount).data('_chk',chk_adjust).data('token', $_token);

    });

})(jQuery);
</script>
