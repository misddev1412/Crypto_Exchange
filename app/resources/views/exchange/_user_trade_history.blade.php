<div class="row">
    <div class="col">
        <h3 class="lf-toggle-text-color font-size-14">{{ __('My Trade History') }}</h3>

        <table class="table table-borderless lf-toggle-border-card lf-toggle-text-color font-size-10 w-100 mb-1" id="user-trade-history-table">
            <thead class="lf-toggle-bg-card">
            <tr>
                <th class="text-left all py-2 px-3 w-86px border-bottom lf-toggle-border-color">{{ __('Date') }}</th>
                <th class="text-center min-phone-l py-2 px-3 border-bottom lf-toggle-border-color">{{ __('Type') }}</th>
                <th class="text-center all py-2 px-3 border-bottom lf-toggle-border-color">{{ __('Price') }} (<span v-text="pairDetail.baseCoin"></span>)</th>
                <th class="text-center all py-2 px-3 border-bottom lf-toggle-border-color">{{ __('Amount') }} (<span v-text="pairDetail.tradeCoin"></span>)</th>
                <th class="text-center min-phone-l py-2 px-3 border-bottom lf-toggle-border-color">{{ __('Total') }} (<span v-text="pairDetail.baseCoin"></span>)</th>
            </tr>
            </thead>
        </table>

        <div class="text-right">
            <a href="{{ route('my-trade-history') }}" class="font-size-12 text-info">{{ __('View Complete Trade History') }}</a>
        </div>
    </div>
</div>
