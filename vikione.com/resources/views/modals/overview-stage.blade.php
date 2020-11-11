<div class="modal fade" id="stage-overview" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-md">
                <h3 class="popup-title">{{ __('Overview of') }} '{{ $stage->name }}'</h3>
                <div class="popup-content">
                    <div class="card-bordered nopd">
                        <div class="card-innr">
                            <div class="row guttar-vr-20px align-items-center">
                                <div class="col-sm-6">
                                    <div class="total-block">
                                        <h6 class="total-title ucap">{{ __('Token Issued') }}</h6>
                                        <span class="total-amount-lead">{{ to_num_token($stage->total_tokens).' '.$symbol }}</span>
                                        <p class="total-note">{{ __('In based price') }} <span>{{ to_num(($stage->total_tokens * $stage->base_price)).' '.$base_symbol }}</span></p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="total-block">
                                        <h6 class="total-title ucap">{{ __('Token Sold') }}</h6>
                                        <span class="total-amount-lead">{{ to_num_token($overview->sold).' '.$symbol }} <span class="ml-2 badge badge-auto badge-primary badge-xs fs-12 align-middle" data-toggle="tooltip" data-placement="right" title="{{ __('Sold Out') }}">{{ $overview->percent }}%</span></span>
                                        <p class="total-note">{{ __('Unsold') }} <span>{{ to_num_token($overview->unsold) }}</span> {{ __('Token') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($overview->pending > 0 || $overview->sold > 0)
                        <div class="sap sap-light"></div>
                        <div class="card-innr">
                            @if($overview->pending > 0)
                            <div class="total-block">
                                <h5 class="total-title-sm">{{ __('In Progress') }}</h5>
                                <span class="total-amount">{{ to_num_token($overview->pending).' '.$symbol }}</span>
                                @if($overview->pending > 0)
                                <p class="total-note">{{ __('Waiting for Approve (Purchase only)') }}</p>
                                @endif
                            </div>
                            @endif
                            @if($overview->sold > 0)
                            <div class="total-block total-block-lg">
                                <h5 class="total-title-xs ucap text-dark">{{ __('Sold Details') }}</h5>
                                <ul class="list total-wg">
                                    <li>
                                        <span class="total-title-xs">{{ __('Total Purchased') }}</span>
                                        <span class="total-amount-sm">{{ to_num_token($overview->purchase) }}</span>
                                    </li>
                                    <li>
                                        <span class="total-title-xs">{{ __('Included Bonus') }}</span>
                                        <span class="total-amount-sm">{{ to_num_token($overview->purchase_bonus) }}</span>
                                    </li>
                                    @if($overview->purchase_bonus > 0)
                                    <li>
                                        <span class="total-title-xs">{{ __('Based Bonus') }}</span>
                                        <span class="total-amount-sm">{{ to_num_token($overview->token_bonus_bb) }}</span>
                                    </li>
                                    <li>
                                        <span class="total-title-xs">{{ __('Amount Bonus') }}</span>
                                        <span class="total-amount-sm">{{ to_num_token($overview->token_bonus_ta) }}</span>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="total-block total-block-md">
                                <ul class="list total-wg">
                                    <li>
                                        <span class="total-title-xs">{{ __('Total Referrals') }}</span>
                                        <span class="total-amount-sm">{{ to_num_token($overview->referral) }}</span>
                                    </li>
                                    <li>
                                        <span class="total-title-xs">{{ __('Total Bonuses') }}</span>
                                        <span class="total-amount-sm">{{ to_num_token($overview->bonus) }}</span>
                                    </li>
                                </ul>
                            </div>
                            @endif
                        </div>
                        @endif
                        <div class="sap sap-light"></div>
                        <div class="card-innr">
                            <div class="total-block">
                                <h5 class="total-title-sm">{{ __('Total Collected') }}</h5>
                                <span class="total-amount">{{ to_num($overview->contribute, 'max').' '.$base_symbol }} <em class="fas text-light fa-info-circle fs-11 align-middle" data-toggle="tooltip" data-placement="right" title="Combined calculation of all transactions in base currency."></em></span>
                            </div>
                            @if(!empty($overview->contribute_in))
                            <div class="total-block total-block-lg">
                                <h6 class="total-title-xs ucap text-dark">{{ __('In Currency') }}</h6>
                                <ul class="list total-wg">
                                    @foreach($overview->contribute_in as $cur => $amt)
                                    <li>
                                        <span class="total-amount-sm">{{ to_num($amt, 'max') }}</span>
                                        <span class="total-title-xs">{{ strtoupper($cur) }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>
                    </div>                    
                </div> {{-- .popup-content --}}
            </div>
        </div>
    </div>
</div>