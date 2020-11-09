<div class="card lf-toggle-border-card lf-toggle-bg-card lf-toggle-text-color overflow-hidden lf-lg-fixed-summary-height position-relative">
    <div class="section-loading lf-toggle-bg-card d-flex justify-content-center align-items-center" v-if="pairDetail.loading">{{ __('Loading...') }}</div>
    <div class="card-body p-0">
        <div class="row">
            <div class="col-sm-12 col-md-2 align-self-center">
                <div class="card-title mb-0 py-3 pl-3 pr-0 text-center text-md-left lf-toggle-border-color lf-border-sm-bottom text-nowrap">
                    <h1 class="m-0 mb-2 font-size-16 font-weight-bold">
                        <span v-text="pairDetail.name"></span>
                    </h1>
                    <div class="media d-inline-flex">
                        <img
                            :src="pairDetail.tradeCoinIcon"
                            class="mr-1 lf-w-20px"
                        >
                        <div class="media-body align-self-center"><span class="font-weight-bold" v-text="pairDetail.tradeCoinName"></span></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-10">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-3 border-left border-bottom border-sm-top lf-toggle-border-color">
                        <div class="py-3 text-center text-md-left">
                            <h3 class="font-size-12 lf-toggle-text-color-50 font-weight-normal m-0">{{ __('Last Price') }}</h3>
                            <span class="font-size-12 font-weight-bold d-block w-100 overflow-hidden text-nowrap" v-text="pairDetail.lastPrice"></span>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 border-left border-bottom lf-toggle-border-color border-sm-top">
                        <div class="py-3 text-center text-md-left">
                            <h3 class="font-size-12 lf-toggle-text-color-50 font-weight-normal m-0">{{ __('24h Change') }}</h3>
                            <span class="font-size-12 font-weight-bold" :class="pairDetail.changeColorText" v-text="pairDetail.change24hr"></span>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 border-left border-bottom lf-toggle-border-color">
                        <div class="py-3 text-center text-md-left">
                            <h3 class="font-size-12 lf-toggle-text-color-50 font-weight-normal m-0">{{ __('24h High') }}</h3>
                            <span class="font-size-12 font-weight-bold d-block w-100 overflow-hidden text-nowrap" v-text="pairDetail.high24hr"></span>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 border-left border-bottom lf-toggle-border-color">
                        <div class="py-3 text-center text-md-left pr-1">
                            <h3 class="font-size-12 lf-toggle-text-color-50 font-weight-normal m-0">{{ __('24h Low') }}</h3>
                            <span class="font-size-12 font-weight-bold d-block w-100 overflow-hidden text-nowrap" v-text="pairDetail.low24hr"></span>
                        </div>
                    </div>
                    <div class="col-12 text-center border-left lf-toggle-border-color">
                        <div class="py-3">
                            <h3 class="font-size-12 lf-toggle-text-color-50 m-0 font-weight-normal d-inline-block">{{ __('24h Volume') }} : </h3>
                            <span class="font-size-12 font-weight-bold">
                                <span v-text="pairDetail.baseCoinVolume"></span> <span v-text="pairDetail.baseCoin"></span> /
                                <span v-text="pairDetail.tradeCoinVolume"></span> <span v-text="pairDetail.tradeCoin"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
