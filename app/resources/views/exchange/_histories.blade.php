<div class="card lf-toggle-text-color lf-toggle-border-card lf-toggle-bg-card overflow-hidden h-100">
    <div class="card-header p-0">
        <h3 class="font-size-14 lf-toggle-text-color m-0 py-2 px-3">{{ __('Market Trade History') }}</h3>
    </div>
    <div class="card-body p-0">
        {{-- market trade history--}}
        <table class="table table-borderless lf-toggle-text-color w-100 font-size-10 mb-0"
               id="market-trade-history-table">
            <thead class="border-bottom lf-toggle-border-color lf-toggle-bg-card">
            <tr>
                <th class="font-weight-light py-1 w-30 lf-toggle-text-color">{{ __('Price') }} (<span
                        v-text="pairDetail.baseCoin"></span>)
                </th>
                <th class="font-weight-light py-1 w-30 text-center">{{ __('Amount') }} (<span
                        v-text="pairDetail.tradeCoin"></span>)
                </th>
                <th class="font-weight-light py-1 w-30 text-right">{{ __('Date') }}</th>
            </tr>
            </thead>
        </table>
    </div>
</div>
