<div class="position-relative min-height-475">
    <div class="cart-loader border lf-toggle-border-color lf-toggle-bg-card" v-bind:class="{hide : hideOthersReportLoader}">
        <div class="lds-cart m-auto">
            <div class="lf-toggle-bg-reverse"></div>
            <div class="lf-toggle-bg-reverse"></div>
            <div class="lf-toggle-bg-reverse"></div>
        </div>
    </div>
    <div class="row my-3">
        <div class="col-lg-6">
                <!-- Coin -->
                <div class="card lf-toggle-bg-card count-box lf-toggle-border-color position-relative mb-2">
                    <div class="card-body my-auto">
                        <div class="d-flex">
                            <div class="icon my-auto mr-3">
                                <img src="{{ get_dashboard_icon('coin.png') }}"
                                     alt="users" class="lf-w-budget0px">
                            </div>
                            <div class="content my-auto text-left">
                                <p class="my-1 font-size-15 font-weight-bold"><strong>{{ __('Total Coin') }}</strong> : @{{ totalCoin }}</p>
                                <p class="my-1"><strong class="text-info">{{ __('Active') }} : </strong> @{{ totalActiveCoin }}</p>
                            </div>
                        </div>
                    </div>
                    @if(has_permission('coins.index'))
                        <button type="button"
                                class="box-action-btn dropdown-toggle"
                                data-toggle="dropdown"
                                aria-expanded="false">
                            <i class="fa fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right text-center" role="menu">
                            <a href="{{ route('coins.index') }}" class="dropdown-item small"><i class="fa fa-users"></i> {{ __('All Coins') }}</a>
                        </div>
                    @endif
                </div>
                <!-- CoinPair -->
                <div class="card lf-toggle-bg-card count-box d-flex lf-toggle-border-color position-relative mb-2">
                    <div class="card-body my-auto">
                        <div class="d-flex">
                            <div class="icon my-auto mr-3">
                                <img src="{{ get_dashboard_icon('coin-pair.png') }}"
                                     alt="trades" class="lf-w-budget0px">
                            </div>
                            <div class="content my-auto text-left">
                                <p class="my-1 font-size-15 font-weight-bold"><strong>{{ __('Total CoinPair') }} :</strong>@{{ totalCoinPair }}</p>
                                <p class="my-1"><strong class="text-info">{{ __('Active') }} : </strong> @{{ totalActiveCoinPair }}</p>
                            </div>
                        </div>
                    </div>
                    @if(has_permission('coin-pairs.index'))
                        <button type="button"
                                class="box-action-btn dropdown-toggle"
                                data-toggle="dropdown"
                                aria-expanded="false">
                            <i class="fa fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right text-center" role="menu">
                            <a href="{{ route('coin-pairs.index') }}" class="dropdown-item small"><i class="fa fa-line-chart"></i> {{ __('All CoinPair') }}</a>
                        </div>
                    @endif
                </div>
                <!-- post and comment -->
                <div class="card lf-toggle-bg-card count-box d-flex lf-toggle-border-color position-relative mb-2">
                    <div class="card-body my-auto">
                        <div class="d-flex">
                            <div class="icon my-auto mr-3">
                                <img src="{{ get_dashboard_icon('post-and-comment.png') }}"
                                     alt="withdrawals" class="lf-w-budget0px">
                            </div>
                            <div class="content my-auto text-left">
                                <p class="my-1 font-size-15 font-weight-bold"><strong>{{ __('Total Post') }} : </strong> @{{ totalPost }}</p>
                                <p class="my-1"><strong>{{ __('Total Comment') }} : </strong> @{{ totalComment }}</p>
                            </div>
                        </div>
                    </div>
                    @if(has_permission('posts.index'))
                        <button type="button"
                                class="box-action-btn dropdown-toggle"
                                data-toggle="dropdown"
                                aria-expanded="false">
                            <i class="fa fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right text-center" role="menu">
                            <a href="{{ route('posts.index') }}" class="dropdown-item small"><i class="fa fa-upload"></i> {{ __('All Post') }}</a>
                        </div>
                    @endif
                </div>
            </div>

        <div class="col-lg-6">
            <!-- withdrawal -->
            <div class="card lf-toggle-bg-card count-box d-flex lf-toggle-border-color position-relative mb-2">
                <div class="card-body my-auto">
                    <div class="d-flex">
                        <div class="icon my-auto mr-3">
                            <img src="{{ get_dashboard_icon('withdrawal.png') }}"
                                 alt="trades" class="lf-w-budget0px">
                        </div>
                        <div class="content my-auto text-left">
                            <p class="my-1 font-size-15 font-weight-bold">{{ __('Pending Withdrawal Review') }}</p>
                            <p class="my-1 font-size-15 font-weight-bold">@{{ totalPendingWithdrawal }}</p>
                        </div>
                    </div>
                </div>
                @if(has_permission('admin.review.withdrawals.index'))
                    <button type="button"
                            class="box-action-btn dropdown-toggle"
                            data-toggle="dropdown"
                            aria-expanded="false">
                        <i class="fa fa-ellipsis-v"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right text-center" role="menu">
                        <a href="{{ route('admin.review.withdrawals.index') }}" class="dropdown-item small"><i class="fa fa-line-chart"></i> {{ __('All Pending Withdrawal') }}</a>
                    </div>
                @endif
            </div>
            <!-- total deposit -->
            <div class="card lf-toggle-bg-card count-box d-flex lf-toggle-border-color position-relative mb-2">
                <div class="card-body my-auto">
                    <div class="d-flex">
                        <div class="icon my-auto mr-3">
                            <img src="{{ get_dashboard_icon('deposit.png') }}"
                                 alt="deposits" class="lf-w-budget0px">
                        </div>
                        <div class="content my-auto text-left">
                            <p class="my-1 font-size-15 font-weight-bold">{{ __('Pending Deposit Review') }}</p>
                            <p class="my-1 font-size-15 font-weight-bold">@{{ totalPendingDeposit }}</p>
                        </div>
                    </div>
                </div>
                @if(has_permission('admin.history.deposits.index'))
                    <button type="button"
                            class="box-action-btn dropdown-toggle"
                            data-toggle="dropdown"
                            aria-expanded="false">
                        <i class="fa fa-ellipsis-v"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right text-center" role="menu">
                        <a href="{{ route('admin.history.deposits.index') }}" class="dropdown-item small"><i class="fa fa-download"></i> {{ __('All Deposit History') }}</a>
                    </div>
                @endif
            </div>
            <!-- KYC -->
            <div class="card lf-toggle-bg-card count-box d-flex lf-toggle-border-color position-relative mb-2">
                <div class="card-body my-auto">
                    <div class="d-flex">
                        <div class="icon my-auto mr-3">
                            <img src="{{ get_dashboard_icon('kyc.png') }}"
                                 alt="deposits" class="lf-w-budget0px">
                        </div>
                        <div class="content my-auto text-left">
                            <p class="my-1 font-size-15 font-weight-bold">{{ __('Pending KYC') }}</p>
                            <p class="my-1 font-size-15 font-weight-bold">@{{ totalPendingReviewKyc }}</p>
                        </div>
                    </div>
                </div>
                @if(has_permission('admin.history.deposits.index'))
                    <button type="button"
                            class="box-action-btn dropdown-toggle"
                            data-toggle="dropdown"
                            aria-expanded="false">
                        <i class="fa fa-ellipsis-v"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right text-center" role="menu">
                        <a href="{{ route('admin.history.deposits.index') }}" class="dropdown-item small"><i class="fa fa-download"></i> {{ __('View Deposit History') }}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
