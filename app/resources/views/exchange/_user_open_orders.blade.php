<div class="row mt-4">
    <div class="col">
        <h3 class="lf-toggle-text-color font-size-14">{{ __('My Open Orders') }}</h3>

        <table class="table table-borderless lf-toggle-border-card lf-toggle-text-color font-size-10 w-100 mb-1" id="user-open-order-table">
            <thead class="lf-toggle-bg-card border-bottom lf-toggle-border-color">
                <tr>
                    <th class="text-left all py-2 px-3 lf-w-86px border-bottom lf-toggle-border-color">{{ __('Date') }}</th>
                    <th class="text-center min-phone-l py-2 px-3 border-bottom lf-toggle-border-color">{{ __('Type') }}</th>
                    <th class="text-center all py-2 px-3 border-bottom lf-toggle-border-color">{{ __('Price') }} (<span v-text="pairDetail.baseCoin"></span>)</th>
                    <th class="text-center min-phone-l py-2 px-3 border-bottom lf-toggle-border-color">{{ __('Amount') }} (<span v-text="pairDetail.tradeCoin"></span>)</th>
                    <th class="text-center all py-2 px-3 border-bottom lf-toggle-border-color">{{ __('Open') }} (<span v-text="pairDetail.tradeCoin"></span>)</th>
                    <th class="text-center min-phone-l py-2 px-3 border-bottom lf-toggle-border-color">{{ __('Filled') }} (<span v-text="pairDetail.tradeCoin"></span>)</th>
                    <th class="text-center min-phone-l py-2 px-3 border-bottom lf-toggle-border-color">{{ __('Total') }} (<span v-text="pairDetail.baseCoin"></span>)</th>
                    <th class="text-center min-phone-l py-2 px-3 border-bottom lf-toggle-border-color">{{ __('Trigger Conditions') }}</th>
                    <th class="text-right all py-2 px-3 border-bottom lf-toggle-border-color">{{ __('Action') }}</th>
                </tr>
            </thead>
        </table>

        <div class="text-right">
            <a href="{{ route('user.open.order') }}" class="font-size-12 text-info">{{ __('View Complete Open Orders') }}</a>
        </div>
    </div>
</div>
