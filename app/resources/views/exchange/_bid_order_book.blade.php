<div class="card lf-toggle-border-card lf-toggle-bg-card lf-toggle-text-color h-100">
    <div class="card-header px-3 py-2 d-flex justify-content-between">
        <h3 class="font-size-14 line-height-medium">{{ __('BID') }}</h3>
        <div>
            <span class="font-size-10 lf-toggle-text-color-50 font-weight-normal m-0">{{ __('Total') }}:</span>
            <span class="font-size-10 font-weight-bold"><span v-text="bidOrderDetail.totalBaseCoinBidOrder"></span> <span v-text="pairDetail.baseCoin"></span></span>
        </div>
    </div>
    <div class="card-body p-0 overflow-hidden">
        <table class="table table-borderless lf-toggle-text-color w-100 font-size-10 mb-0" id="bid-order-table">
            <thead>
            <tr>
                <th class="font-weight-light py-1 lf-toggle-text-color w-30">{{ __('Price') }} (<span v-text="pairDetail.baseCoin"></span>)</th>
                <th class="font-weight-light py-1 text-center w-30">{{ __('Amount') }} (<span v-text="pairDetail.tradeCoin"></span>)</th>
                <th class="font-weight-light py-1 text-right w-30">{{ __('Total') }} (<span v-text="pairDetail.baseCoin"></span>)</th>
            </tr>
            </thead>
        </table>
    </div>
</div>
