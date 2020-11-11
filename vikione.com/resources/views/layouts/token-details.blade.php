@if ($details==true) 
    <div class="card-head d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0">{{__('Transaction Details')}}</h4>
        <div class="trans-status">
            @if($transaction->status == 'approved')
            <span class="badge badge-success ucap">{{__('Approved')}}</span>
            @elseif($transaction->status == 'pending')
            <span class="badge badge-warning ucap">{{__('Pending')}}</span>
            @elseif($transaction->status == 'onhold')
            <span class="badge badge-info ucap">{{__('Progress')}}</span>
            @else
            <span class="badge badge-danger ucap">{{__('Rejected')}}</span>
            @endif
        </div>
    </div>

    @if($transaction->tnx_type=='purchase')
    <div class="trans-details">
        <div class="gaps-1x"></div>
        @if($transaction->status == 'approved')
        <p class="lead-lg text-primary"><strong>{{ __('You have successfully paid this transaction') }}</strong> ({{ ucfirst($transaction->payment_method) }} <small>- {{ gateway_type($transaction->payment_method) }}</small>).</p>
        @endif
        <p>{!! __('The order no. :orderid was placed on :datetime.', ['orderid' => '<strong class="text-primary">'.$transaction->tnx_id.'</strong>', 'datetime' => _date($transaction->tnx_time)]) !!}</p>
        @if($transaction->checked_time != NUll && ($transaction->status == 'rejected' || $transaction->status == 'canceled'))
        <p class="text-danger fs-14">{!! __('Sorry! Your order has been :status due to payment.', ['status' => '<strong>'.$transaction->status.'</strong>']) !!}</p>
        @endif
        <div class="gaps-0-5x"></div>
    </div>
    @endif

@endif

<div class="gaps-1x"></div>
<h6 class="card-sub-title">{{ __('Token Details') }}</h6>
<ul class="data-details-list">
    <li>
        <div class="data-details-head">{{__('Types')}}</div>
        <div class="data-details-des">{{ ucfirst($transaction->tnx_type) }}</div>
    </li>
    @if(!empty($transaction->ico_stage))
    <li>
        <div class="data-details-head">{{__('Token of Stage')}}</div>
        <div class="data-details-des"><strong>{{ $transaction->ico_stage->name }}</strong></div>
    </li>
    @else 
    <li>
        <div class="data-details-head">{{__('Token of Stage')}}</div>
        <div class="data-details-des"><strong>Transaction</strong></div>
    </li>
    @endif
    @if($transaction->tnx_type!='refund')
    <li>
        <div class="data-details-head">{{__('Token Amount (T)')}}</div>
        <div class="data-details-des">
            <span>{{ $transaction->tokens }} {{ token_symbol() }}</span>
        </div>
    </li>
    @endif
    @if($transaction->tnx_type=='purchase')
    <li>
        <div class="data-details-head">{{__('Bonus Token (B)')}}</div>
        <div class="data-details-des">
            <span>{{ to_num($transaction->total_bonus, 'zero', '', false) }} {{ token_symbol() }}</span>
            <span>({{ $transaction->bonus_on_token }} + {{ $transaction->bonus_on_base }})</span>
        </div>
    </li>
    <li>
        <div class="data-details-head">{{__('Total Token')}}</div>
        <div class="data-details-des">
            <span><strong>{{ to_num($transaction->total_tokens, 'zero', '', false) }} {{ token_symbol() }}</strong></span>
            <span>(T+B)</span>
        </div>
    </li>
    <li>
        <div class="data-details-head">{{__('Total Payment')}}</div>
        <div class="data-details-des">
            <span><strong>{{ to_num($transaction->receive_amount, 'max') }} {{ strtoupper($transaction->receive_currency) }}</strong></span>
            <span><em class="fas fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="1 {{ token('symbol') }} = {{ $transaction->base_currency_rate.' '.strtoupper($transaction->base_currency) }}"></em> {{ to_num($transaction->base_amount, 'auto') }} {{ strtoupper($transaction->base_currency) }}</span>
        </div>
    </li>
    @endif
    @if($transaction->tnx_type=='refund')
    <li>
        <div class="data-details-head">{{__('Refunded Token')}}</div>
        <div class="data-details-des">
            <span><strong class="text-danger">{{ round($transaction->total_tokens, min_decimal()) }} {{ token_symbol() }}</strong></span>
        </div>
    </li>
    <li>
        <div class="data-details-head">{{__('Refunded Amount')}}</div>
        <div class="data-details-des">
            <span><strong class="text-danger">{{ round($transaction->receive_amount, max_decimal()) }} {{ strtoupper($transaction->receive_currency) }}</strong></span>
            <span><em class="fas fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="1 {{ token('symbol') }} = {{ $transaction->base_currency_rate.' '.strtoupper($transaction->base_currency) }}"></em> {{ to_num($transaction->base_amount, 'auto') }} {{ base_currency(true) }}</span>
        </div>
    </li>
    @endif
    @if($transaction->tnx_type=='transfer')
    <li>
        <div class="data-details-head">{{ ($transaction->extra=='sent') ? __('Token Send To') : __('Token Receive From') }}</div>
        <div class="data-details-des">
            <span>{{ $transaction->payment_to }}
        </div>
    </li>
    @endif
    @if($transaction->details && ($transaction->tnx_type!='purchase')) 
    <li>
        <div class="data-details-head">{{ ($transaction->tnx_type=='refund' || $transaction->tnx_type=='transfer') ? __('Notes') : __('Details') }}</div>
        <div class="data-details-des">
            <span>{!! $transaction->details !!}
        </div>
    </li>
    @endif
    @if($transaction->tnx_type=='referral')
    <li>
        <div class="data-details-head">{{__('Referral Bonus For')}}</div>
        <div class="data-details-des">
            @php 
            $referral = (get_meta($transaction->extra, 'who')) ? $transaction->user(get_meta($transaction->extra, 'who')) : '';
            @endphp
            <span>{{ $referral->name }}
        </div>
    </li>
    @endif
    @php 
        $trnx_extra = (is_json($transaction->extra, true) ?? $transaction->extra);
    @endphp
    @if(!empty($trnx_extra->message))
    <li>
        <div class="data-details-head">{{__('Refund Note')}}</div>
        <div class="data-details-des">
            <span>{{ $trnx_extra->message }}</span>
        </div>
    </li>
    @endif
</ul>
@if($transaction->checked_time != NUll && $transaction->status == 'approved')
    <p class="text-primary fs-12 pt-3"><em>{!! __('Transaction has been approved at :time.', ['time'=>_date($transaction->checked_time)])  !!}</em></p>
@endif
@if($transaction->status == 'pending')
    <p class="text-primary fs-12 pt-3">{{ __('The transaction is currently under review. We will send you an email once our review is complete.') }}</p>
@elseif($transaction->status == 'rejected' || $transaction->status == 'canceled')
    @if($transaction->tnx_type=='purchase')
        @if($transaction->checked_time != NUll)
            <p class="text-danger fs-12 pt-3">{!! __('The transaction was canceled by Administrator at :time.', ['time'=>_date($transaction->checked_time)])  !!}</p>
        @elseif($transaction->status == 'canceled')
            <p class="text-danger fs-13 pt-3"><em>{{ __('You have canceled this transaction.') }}</em></p>
        @endif
    @elseif($transaction->tnx_type=='transfer')
        <p class="text-danger fs-13 pt-3">{!! __('The transfer request was canceled at :time.', ['time'=>_date($transaction->checked_time)])  !!}</p>
    @endif
@endif