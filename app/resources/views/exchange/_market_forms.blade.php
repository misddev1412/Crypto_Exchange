<div class="col-md-6 lf-border-md-right lf-border-sm-bottom lf-toggle-border-color py-3">
    @include('exchange._user_base_coin_balance')

    <form id="market-buy-form">
        <div class="form-group row">
            <label class="col-sm-3 col-form-label font-size-10">{{ __('Price') }}</label>
            <div class="col-sm-9">
                <div class="input-group input-group-sm">
                    <p class="form-control-plaintext border-radius-0 font-size-11 p-1 px-2 lf-toggle-bg-input lf-toggle-border-color border-right-0 lf-toggle-text-color">
                        {{ __('Market') }}</p>
                    <div class="input-group-append">
                            <span class="input-group-text font-size-11 border-radius-0 lf-toggle-bg-input lf-toggle-border-color lf-toggle-text-color p-1 px-2">
                                <span v-text="pairDetail.baseCoin"></span>
                            </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label for="market-buy-amount" class="col-sm-3 col-form-label font-size-10">{{ __('Total') }}</label>
            <div class="col-sm-9">
                <div class="input-group input-group-sm">
                    <input type="text"
                           class="form-control border-radius-0 font-size-11 p-1 px-2 border-right-0 lf-toggle-text-color"
                           name="total"
                           v-model="marketBuyFormData.total"
                           id="market-buy-total-element"
                    >
                    <div class="input-group-append">
                        <span class="input-group-text font-size-11 border-radius-0 lf-toggle-bg-input lf-toggle-border-color lf-toggle-text-color p-1 px-2">
                            <span v-text="pairDetail.baseCoin"></span>
                        </span>
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-between" role="group">
                    <button type="button"
                            class="lf-toggle-border-card lf-toggle-text-color lf-toggle-bg-input font-size-10 p-1 px-2"
                            @auth
                            @click="onClickProduceAmountByPercentHandler('marketBuyFormData', 25, 'buy')"
                            @endauth
                    >
                        {{ __('25%') }}
                    </button>
                    <button type="button"
                            class="lf-toggle-border-card lf-toggle-text-color lf-toggle-bg-input font-size-10 p-1 px-2"
                            @auth
                            @click="onClickProduceAmountByPercentHandler('marketBuyFormData', 50, 'buy')"
                            @endauth
                    >
                        {{ __('50%') }}
                    </button>
                    <button type="button"
                            class="lf-toggle-border-card lf-toggle-text-color lf-toggle-bg-input font-size-10 p-1 px-2"
                            @auth
                            @click="onClickProduceAmountByPercentHandler('marketBuyFormData', 75, 'buy')"
                            @endauth
                    >
                        {{ __('75%') }}
                    </button>
                    <button type="button"
                            class="lf-toggle-border-card lf-toggle-text-color lf-toggle-bg-input font-size-10 p-1 px-2"
                            @auth
                            @click="onClickProduceAmountByPercentHandler('marketBuyFormData', 100, 'buy')"
                            @endauth
                    >
                        {{ __('100%') }}
                    </button>
                </div>
            </div>
        </div>

        @guest
            <div class="font-size-12 p-2 lf-toggle-border-card text-center">
                <a href="{{ route('login') }}" class="text-green">{{ __('Log In') }}</a> {{ __('or') }} <a href="{{ route('register.index') }}" class="text-green">{{ __('Register') }}</a> {{ __('to trade') }}
            </div>
        @endguest

        @auth
            <button
                type="submit"
                class="btn btn-block font-size-14 p-2 lf-toggle-border-card bg-green text-white font-weight-bold form-submission-button"
                @click="placeOrder('market', 'buy', 'marketBuyForm')"
                :disabled="marketBuyFormData.placingOrder"
            >
                <span v-if="marketBuyFormData.placingOrder">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    {{ __('Placing Order...') }}
                </span>
                <span v-else>
                    {{ __('BUY') }} <span v-text="pairDetail.tradeCoin"></span>
                </span>
            </button>
        @endauth

    </form>
</div>
<div class="col-md-6 py-3">
    @include('exchange._user_trade_coin_balance')

    <form id="market-sell-form">
        <div class="form-group row">
            <label class="col-sm-3 col-form-label font-size-10">{{ __('Price') }}</label>
            <div class="col-sm-9">
                <div class="input-group input-group-sm">
                    <p class="form-control-plaintext border-radius-0 font-size-11 p-1 px-2 lf-toggle-bg-input lf-toggle-border-color border-right-0 lf-toggle-text-color">
                        {{ __('Market') }}</p>
                    <div class="input-group-append">
                            <span class="input-group-text font-size-11 border-radius-0 lf-toggle-bg-input lf-toggle-border-color lf-toggle-text-color p-1 px-2">
                                <span v-text="pairDetail.baseCoin"></span>
                            </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label for="market-sell-amount" class="col-sm-3 col-form-label font-size-10">{{ __('Amount') }}</label>
            <div class="col-sm-9">
                <div class="input-group input-group-sm">
                    <input type="text"
                           class="form-control border-radius-0 font-size-11 p-1 px-2 border-right-0 lf-toggle-text-color"
                           v-model="marketSellFormData.amount"
                           name="amount"
                           id="market-sell-amount-element"
                    >
                    <div class="input-group-append">
                        <span class="input-group-text font-size-11 border-radius-0 lf-toggle-bg-input lf-toggle-border-color lf-toggle-text-color p-1 px-2">
                            <span v-text="pairDetail.tradeCoin"></span>
                        </span>
                    </div>
                </div>


                <div class="mt-3 d-flex justify-content-between" role="group">
                    <button type="submit"
                            class="lf-toggle-border-card lf-toggle-text-color lf-toggle-bg-input font-size-10 p-1 px-2"
                            @auth
                            @click="onClickProduceAmountByPercentHandler('marketSellFormData', 25, 'sell')"
                            @endauth
                    >
                        {{ __('25%') }}
                    </button>
                    <button type="button"
                            class="lf-toggle-border-card lf-toggle-text-color lf-toggle-bg-input font-size-10 p-1 px-2"
                            @auth
                            @click="onClickProduceAmountByPercentHandler('marketSellFormData', 50, 'sell')"
                            @endauth
                    >
                        {{ __('50%') }}
                    </button>
                    <button type="button"
                            class="lf-toggle-border-card lf-toggle-text-color lf-toggle-bg-input font-size-10 p-1 px-2"
                            @auth
                            @click="onClickProduceAmountByPercentHandler('marketSellFormData', 75, 'sell')"
                            @endauth
                    >
                        {{ __('75%') }}
                    </button>
                    <button type="button"
                            class="lf-toggle-border-card lf-toggle-text-color lf-toggle-bg-input font-size-10 p-1 px-2"
                            @auth
                            @click="onClickProduceAmountByPercentHandler('marketSellFormData', 100, 'sell')"
                            @endauth
                    >
                        {{ __('100%') }}
                    </button>
                </div>
            </div>
        </div>

        @guest
            <div class="font-size-12 p-2 lf-toggle-border-card text-center">
                <a href="{{ route('login') }}" class="text-pink">{{ __('Log In') }}</a> {{ __('or') }} <a href="{{ route('register.index') }}" class="text-pink">{{ __('Register') }}</a> {{ __('to trade') }}
            </div>
        @endguest

        @auth
            <button
                type="submit"
                class="btn btn-block font-size-14 p-2 lf-toggle-border-card bg-pink text-white font-weight-bold form-submission-button"
                @click="placeOrder('market', 'sell', 'marketSellForm')"
                :disabled="marketSellFormData.placingOrder"
            >
                <span v-if="marketSellFormData.placingOrder">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    {{ __('Placing Order...') }}
                </span>
                <span v-else>
                    {{ __('SELL') }} <span v-text="pairDetail.tradeCoin"></span>
                </span>
            </button>
        @endauth
    </form>
</div>
