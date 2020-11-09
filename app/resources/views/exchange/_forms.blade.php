<div class="card lf-toggle-text-color overflow-hidden bg-transparent border-0 h-100">
    <div class="card-header p-0 border-bottom lf-toggle-border-color bg-transparent">
        <ul class="nav card-nav">
            <li
                class="nav-item lf-toggle-border-card border-bottom-0 "
                :class="{ 'active': marketFormTab.tab === 'limit-form-tab' }"
                @click="marketFormTab.tab = 'limit-form-tab'"
            >
                <a
                    class="nav-link font-size-12 py-2 px-3 lf-toggle-text-color"
                    href="javascript:;"
                >
                    {{ __('Limit') }}
                </a>
            </li>
            <li
                class="nav-item lf-toggle-border-card border-bottom-0 border-left-0"
                :class="{ 'active': marketFormTab.tab === 'market-form-tab' }"
                @click="marketFormTab.tab = 'market-form-tab'"
            >
                <a class="nav-link font-size-12 lf-toggle-text-color py-2 px-3" href="javascript:;">
                    {{ __('Market') }}
                </a>
            </li>
            <li
                class="nav-item lf-toggle-border-card border-bottom-0 border-left-0"
                :class="{ 'active': marketFormTab.tab === 'stop-limit-form-tab' }"
                @click="marketFormTab.tab = 'stop-limit-form-tab'"
            >
                <a class="nav-link font-size-12 lf-toggle-text-color py-2 px-3" href="javascript:;">
                    {{ __('Stop Limit') }}
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body lf-toggle-border-card lf-toggle-bg-card border-top-0 px-3 py-2 overflow-hidden">
        <div class="row" v-show="marketFormTab.tab === 'limit-form-tab'">
            @include('exchange._limit_forms')
        </div>
        <div class="row"  v-show="marketFormTab.tab === 'market-form-tab'">
            @include('exchange._market_forms')
        </div>
        <div class="row"  v-show="marketFormTab.tab === 'stop-limit-form-tab'">
            @include('exchange._stop_limit_forms')
        </div>
    </div>
</div>
