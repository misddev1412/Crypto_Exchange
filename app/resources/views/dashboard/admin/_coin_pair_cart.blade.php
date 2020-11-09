<div class="position-relative">
    <div class="cart-loader border lf-toggle-border-color lf-toggle-bg-card" v-bind:class="{hide : hideCoinPairCartLoader}">
        <div class="lds-cart m-auto">
            <div class="lf-toggle-bg-reverse"></div>
            <div class="lf-toggle-bg-reverse"></div>
            <div class="lf-toggle-bg-reverse"></div>
        </div>
        </div>
    @component('components.card', ['class' => 'lf-toggle-border-color lf-toggle-bg-card', 'headerClass' => 'lf-toggle-border-color', 'footerClass' => 'lf-toggle-border-color'])
        @slot('header')
            <h5 class="card-title text-center">{{ __('This Week') }} @{{ coinPairName }} {{__('Revenue Cart') }}</h5>
        @endslot
        <canvas id="tradeCart" height="125px"></canvas>
        @slot('footer')
            <div class="row text-center pb-1">
                <div class="col-md-6 border-right lf-toggle-border-color">
                    <h5>@{{ totalCoinPairTrade }}</h5>
                    <span>{{ __("Weekly Total Trading") }}</span>
                </div>
                <div class="col-md-6 lf-toggle-border-color">
                    <h5>@{{ totalRevenue }}</h5>
                    <span>{{ __("Weekly Total Gross Revenue") }}</span>
                </div>
            </div>
        @endslot
    @endcomponent
</div>
