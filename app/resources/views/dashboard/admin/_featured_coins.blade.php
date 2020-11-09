<div class="position-relative min-height-114 my-3" v-bind:class="{'d-none' : hideFeaturedCoin}">
    <div class="cart-loader border lf-toggle-border-color lf-toggle-bg-card" v-bind:class="{hide : hideFeaturedCoinLoader}">
        <div class="lds-cart m-auto">
            <div class="lf-toggle-bg-reverse"></div>
            <div class="lf-toggle-bg-reverse"></div>
            <div class="lf-toggle-bg-reverse"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-6 col-12 my-2" v-for="coin in featuredCoins">
            <!-- coin box -->
            <div class="coin-box coin-box-info">
                <div class="lf-toggle-bg-card lf-toggle-border-color border d-flex p-3 position-relative">
                    <div class="icon my-auto">
                        <img :src="coin.icon"
                             alt="coin icon" class="lf-w-80px">
                    </div>
                    <div class="content my-auto ml-auto text-right">
                        <h4 class="my-0 coin-name">@{{ coin.symbol }}</h4>
                        <p class="my-0 available-coin"> @{{ coin.primary_balance }}</p>
                    </div>
                </div>
                @if(has_permission('coins.revenue-graph'))
                    <button type="button"
                            class="box-action-btn dropdown-toggle"
                            data-toggle="dropdown"
                            aria-expanded="false">
                        <i class="fa fa-ellipsis-v"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right text-center" role="menu">
                        <a :href="coin.revenue_cart_url" class="dropdown-item small"><i class="fa fa-line-chart"></i> {{ __('Revenue Graph') }}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
