@extends('layouts.admin')
@section('title', 'Transaction Details')

@section('content')
<div class="page-content">
    <div class="container">
        <div class="card content-area">
            <div class="card-innr">
                <div class="card-head d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Transaction Details <em class="ti ti-angle-right fs-14"></em> <small class="tnx-id">{{ $trnx->tnx_id }}</small></h4>
                    <a href="{{ (url()->previous()) ? url()->previous() : route('admin.transactions') }}" class="btn btn-sm btn-auto btn-primary"><em class="fas fa-arrow-left"></em><span class="d-sm-inline-block d-none">Back</span></a>
                </div>
                <div class="gaps-1-5x"></div>
                <div class="data-details d-md-flex">
                    <div class="fake-class">
                        <span class="data-details-title">Transaction Date</span>
                        <span class="data-details-info">{{ _date($trnx->tnx_time) }}</span>
                    </div>
                    <div class="fake-class">
                        <span class="data-details-title">Transaction Status</span>
                        <span class="badge badge-{{ __status($trnx->status, 'status') }} ucap">{{ $trnx->status }}</span>
                    </div>
                    <div class="fake-class">
                        <span class="data-details-title">Transaction by</span>

                        <span class="data-details-info"><strong>{{ transaction_by($trnx->added_by) }}</strong></span>
                    </div>
                    <div class="fake-class">
                        @if($trnx->tnx_type=='refund')
                            @php 
                            $trnx_extra = (is_json($trnx->extra, true) ?? $trnx->extra);
                            @endphp
                            <span class="data-details-title">Refund Note</span>
                            <span class="data-details-info">{{ $trnx_extra->message }}</span>
                        @else
                            <span class="data-details-title">Transaction Note</span>
                            @if($trnx->checked_by != NULL)
                            <span class="data-details-info">{{ ucfirst($trnx->status) }} By <strong>{{ ucfirst(approved_by($trnx->checked_by)) }}</strong> <br> at {{ _date($trnx->checked_time) }}</span>
                            @elseif($trnx->status == 'canceled')
                            <span class="data-details-info">Canceled by User</span>
                            @else
                            <span class="data-details-info">Not Reviewed yet.</span>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="gaps-3x"></div>
                <h6 class="card-sub-title">Transaction Info</h6>
                <ul class="data-details-list">
                    <li>
                        <div class="data-details-head">Transaction Type</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->tnx_type) }}</strong></div>
                    </li>
                    @if(($trnx->tnx_type=='referral'||$trnx->tnx_type=='bonus') && $trnx->added_by==set_added_by('0'))
                    <li>
                        <div class="data-details-head">{{ ($trnx->tnx_type=='bonus') ? 'Referred By' : 'Referral Bonus For' }}</div>
                        <div class="data-details-des">
                            <span>{{ set_id(referral_info($trnx->extra, 'id')) }} <small> - {{ explode_user_for_demo( referral_info($trnx->extra, 'email'), auth()->user()->type) }}</small></span>
                            <span><strong><a href="{{ route('admin.transactions.view', get_tnx_id($trnx->extra)) }}">{{ get_meta($trnx->extra, 'tnx_id') }}</a></strong></span>
                        </div>
                    </li>
                    <li>
                        <div class="data-details-head">Bonus Apply</div>
                        <div class="data-details-des">
                            <span>{{ get_meta($trnx->extra, 'bonus') }}{{ (get_meta($trnx->extra, 'calc') =='percent' ? '%' : ' (Fixed)') }}{{ (get_meta($trnx->extra, 'level') ? ' - '.strtoupper(get_meta($trnx->extra, 'level')) : '' ) }}</span>
                            <span>{{ get_meta($trnx->extra, 'tokens').' '.token_symbol() }}</span>
                        </div>
                    </li>
                    @endif

                    @if($trnx->tnx_type=='purchase')
                    <li>
                        <div class="data-details-head">Payment Gateway</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->payment_method) }} <small>- {{ gateway_type($trnx->payment_method) }}</small></strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">Deposit From</div>
                        <div class="data-details-des"><strong>{!! $trnx->wallet_address ? $trnx->wallet_address : '~' !!}</strong></div>
                    </li>
                    @if($trnx->payment_to)
                    <li>
                        <div class="data-details-head">Deposit To ({{ ( ($trnx->payment_method=='manual') ? short_to_full($trnx->currency) : ucfirst($trnx->payment_method) ) }})</div>
                        <div class="data-details-des"><span>{!! $trnx->payment_to ? $trnx->payment_to : '~' !!}</span></div>
                    </li>
                    @endif
                    <li>
                        <div class="data-details-head">Payable Amount</div>
                        <div class="data-details-des">
                            <span><strong>{{ to_num($trnx->amount, 'max').' '.strtoupper($trnx->currency) }}</strong></span>
                        </div>
                    </li>
                    <li>
                        <div class="data-details-head">Received Amount</div>
                        <div class="data-details-des">
                            <span><strong>{{ to_num($trnx->receive_amount, 'max').' '.strtoupper($trnx->currency) }}</strong></span>
                        </div>
                    </li>
                    @endif

                    @if($trnx->tnx_type=='refund')
                    <li>
                        <div class="data-details-head">Refund Amount</div>
                        <div class="data-details-des">
                            <span><strong class="text-danger">{{ to_num($trnx->amount, 'max').' '.strtoupper($trnx->currency) }}</strong></span>
                        </div>
                    </li>
                    @endif
                    <li>
                        <div class="data-details-head">Details</div>
                        <div class="data-details-des">{!! $trnx->details ? $trnx->details : '&nbsp;' !!}</div>
                    </li>
                </ul>{{-- .data-details --}}
                <div class="gaps-3x"></div>
                <h6 class="card-sub-title">Token Details</h6>
                <ul class="data-details-list">
                    @if($trnx->ico_stage)
                    <li>
                        <div class="data-details-head">Stage Name</div>
                        <div class="data-details-des"><strong>{{ $trnx->ico_stage->name }}</strong></div>
                    </li>
                    @endif
                    @if($trnx->tnx_type=='purchase')
                    <li>
                        <div class="data-details-head">Contribution</div>
                        <div class="data-details-des">
                            <span><strong>{{ to_num($trnx->amount, 'max').' '.strtoupper($trnx->currency) }}</strong> <em class="fas fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="1 {{ token('symbol') }} = {{ to_num($trnx->currency_rate, 'max').' '.strtoupper($trnx->currency) }}"></em></span>
                            <span><em class="fas fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="1 {{ token('symbol') }} = {{ to_num($trnx->base_currency_rate, 'max').' '.strtoupper($trnx->base_currency) }}"></em> {{ to_num($trnx->base_amount, 'max') }} {{ strtoupper($trnx->base_currency) }}</span>
                        </div>
                    </li>
                    @endif
                    <li>
                        <div class="data-details-head">Token {{ ($trnx->tnx_type=='refund') ? 'Refund' : 'Added' }} To</div>
                        <div class="data-details-des">
                            <strong>{{ set_id($trnx->user) }} 
                                <small> - {{ isset($trnx->tnxUser) ? explode_user_for_demo($trnx->tnxUser->email, auth()->user()->type) : '...' }}</small>
                            </strong>
                        </div>
                    </li>
                    @if($trnx->tnx_type!='refund')
                    <li>
                        <div class="data-details-head">Token (T)</div>
                        <div class="data-details-des">
                            <span>{{ to_num($trnx->tokens, 'min', '', false) }} {{ token_symbol() }}</span>
                        </div>
                    </li>
                    @endif
                    @if($trnx->tnx_type=='purchase')
                    <li>
                        <div class="data-details-head">Bonus Tokens (B)</div>
                        <div class="data-details-des">
                            <span>{{ to_num($trnx->total_bonus, 'min', '', false) }} {{ token_symbol() }}</span>
                            <span>({{ $trnx->bonus_on_token }} + {{ $trnx->bonus_on_base }})</span>
                        </div>
                    </li>
                    <li>
                        <div class="data-details-head">Total Token</div>
                        <div class="data-details-des">
                            <span><strong>{{ to_num($trnx->total_tokens, 'min', '', false) }} {{ token_symbol() }}</strong></span>
                            <span>(T+B)</span>
                        </div>
                    </li>
                    @endif
                    @if($trnx->tnx_type=='refund')
                    <li>
                        <div class="data-details-head">Refund Token</div>
                        <div class="data-details-des">
                            <span><strong class="text-danger">{{ round($trnx->total_tokens, min_decimal()) }} {{ token_symbol() }}</strong></span>
                        </div>
                    </li>
                    @endif

                </ul>{{-- .data-details --}}
                <div class="gaps-0-5x"></div>
            </div>
        </div>{{-- .card --}}
    </div>{{-- .container --}}
</div>{{-- .page-content --}}
@endsection

