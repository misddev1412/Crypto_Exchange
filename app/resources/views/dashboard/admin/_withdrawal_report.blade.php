<div class="position-relative">
    <div class="cart-loader border lf-toggle-border-color lf-toggle-bg-card" v-bind:class="{hide : hideWithdrawalReportLoader}">
        <div class="lds-cart m-auto">
            <div class="lf-toggle-bg-reverse"></div>
            <div class="lf-toggle-bg-reverse"></div>
            <div class="lf-toggle-bg-reverse"></div>
        </div>
    </div>
    <div class="card lf-toggle-bg-card lf-toggle-border-color border-top-0 min-height-354" v-html="recentWithdrawalView">

    </div>
</div>
